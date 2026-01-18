require("dotenv").config();
const express = require("express");
const cors = require("cors");
const bcrypt = require("bcrypt");
const jwt = require("jsonwebtoken");
const pool = require("./db");
const { auth } = require("./routes/auth");
const { recommend } = require("./recommender");
const imageUploadRoutes = require("./routes/imageUpload");

const app = express();
const allowedOrigins = ["http://localhost:5173", "http://localhost:5175"];

app.use(cors({
  origin: "http://localhost:5173",
  credentials: true, 
}));
app.use(express.json());
app.use('/uploads', express.static('uploads'));

app.use("/api/appointments", require("./routes/appointments"));
app.use("/api/cars", imageUploadRoutes);

app.get("/api/health", (req, res) => res.json({ ok: true }));

// REGISTER
app.post("/api/auth/register", async (req, res) => {
  const { name, email, password } = req.body || {};
  if (!name || !email || !password) return res.status(400).json({ error: "Minden mező kötelező." });

  const e = String(email).trim().toLowerCase();
  const [exists] = await pool.query("SELECT id FROM users WHERE email = ?", [e]);
  if (exists.length) return res.status(409).json({ error: "Ez az email már foglalt." });

  const hash = await bcrypt.hash(password, 12);
  const [result] = await pool.query(
    "INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)",
    [String(name).trim(), e, hash]
  );

  const role = "user";
  const token = jwt.sign(
    { id: result.insertId, email: e, name: String(name).trim(), role },
    process.env.JWT_SECRET,
    { expiresIn: "7d" }
  );
  res.json({ token, user: { id: result.insertId, email: e, name: String(name).trim(), role } });
});

// LOGIN
app.post("/api/auth/login", async (req, res) => {
  const { email, password } = req.body || {};
  if (!email || !password) return res.status(400).json({ error: "Email és jelszó kötelező." });

  const e = String(email).trim().toLowerCase();
  let user;

  try {
    const [rows] = await pool.query("SELECT id, name, email, password_hash, role FROM users WHERE email = ?", [e]);
    if (!rows.length) return res.status(401).json({ error: "Hibás email vagy jelszó." });
    user = rows[0];
  } catch (err) {
    const [rows] = await pool.query("SELECT id, name, email, password_hash FROM users WHERE email = ?", [e]);
    if (!rows.length) return res.status(401).json({ error: "Hibás email vagy jelszó." });
    user = { ...rows[0], role: "user" };
  }

  const ok = await bcrypt.compare(password, user.password_hash);
  if (!ok) return res.status(401).json({ error: "Hibás email vagy jelszó." });

  const token = jwt.sign(
    { id: user.id, email: user.email, name: user.name, role: user.role || "user" },
    process.env.JWT_SECRET,
    { expiresIn: "7d" }
  );
  res.json({ token, user: { id: user.id, email: user.email, name: user.name, role: user.role || "user" } });
});

//token ellenőrzés
app.get("/api/me", auth, async (req, res) => {
  res.json({ user: req.user });
});

// USER PROFILE - get
app.get("/api/auth/profile", auth, async (req, res) => {
  try {
    const [rows] = await pool.query(
      "SELECT id, name, email, COALESCE(phone, '') as phone FROM users WHERE id = ?",
      [req.user.id]
    );
    if (!rows.length) return res.status(404).json({ error: "Felhasználó nem található" });
    res.json(rows[0]);
  } catch (err) {
    console.error("Profile fetch error:", err);
    try {
      const [rows] = await pool.query(
        "SELECT id, name, email FROM users WHERE id = ?",
        [req.user.id]
      );
      if (!rows.length) return res.status(404).json({ error: "Felhasználó nem található" });
      res.json({ ...rows[0], phone: "" });
    } catch (err2) {
      res.status(500).json({ error: "DB error" });
    }
  }
});

// USER PROFILE - update
app.put("/api/auth/profile", auth, async (req, res) => {
  const { name, email, phone } = req.body || {};
  if (!name || !email) return res.status(400).json({ error: "Név és email kötelező." });

  try {
    const e = String(email).trim().toLowerCase();
    const [existing] = await pool.query(
      "SELECT id FROM users WHERE email = ? AND id != ?",
      [e, req.user.id]
    );
    if (existing.length) return res.status(409).json({ error: "Ez az email már foglalt." });

    try {
      await pool.query(
        "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?",
        [String(name).trim(), e, phone ? String(phone).trim() : null, req.user.id]
      );
    } catch (err) {
      await pool.query(
        "UPDATE users SET name = ?, email = ? WHERE id = ?",
        [String(name).trim(), e, req.user.id]
      );
    }
    
    res.json({ msg: "Profil frissítve" });
  } catch (err) {
    console.error("Profile update error:", err);
    res.status(500).json({ error: "DB error" });
  }
});

// CARS - list
app.get("/api/cars", auth, async (req, res) => {
  const [cars] = await pool.query(
    "SELECT id, make_model, vin, year, image_url, created_at FROM cars WHERE user_id = ? ORDER BY created_at DESC",
    [req.user.id]
  );
  res.json({ cars });
});

// CARS - create
app.post("/api/cars", auth, async (req, res) => {
  const { make_model, vin, year } = req.body || {};
  if (!make_model || !vin) return res.status(400).json({ error: "Autó típusa és VIN kötelező." });

  const cleanVin = String(vin).trim().toUpperCase();
  if (cleanVin.length !== 17) return res.status(400).json({ error: "A VIN tipikusan 17 karakter." });

  try {
    const [result] = await pool.query(
      "INSERT INTO cars (user_id, make_model, vin, year) VALUES (?, ?, ?, ?)",
      [req.user.id, String(make_model).trim(), cleanVin, year ? Number(year) : null]
    );
    res.status(201).json({ id: result.insertId });
  } catch (e) {
    if (String(e).includes("Duplicate")) return res.status(409).json({ error: "Ez a VIN már szerepel." });
    throw e;
  }
});

// CARS - delete
app.delete("/api/cars/:carId", auth, async (req, res) => {
  const carId = req.params.carId;

  try {
    const [rows] = await pool.query("SELECT user_id FROM cars WHERE id = ?", [carId]);
    if (!rows.length) return res.status(404).json({ error: "Autó nem található" });
    if (rows[0].user_id !== req.user.id) return res.status(403).json({ error: "Nincs jogosultság" });
    await pool.query("DELETE FROM appointments WHERE car_id = ?", [carId]);
    await pool.query("DELETE FROM cars WHERE id = ?", [carId]);
    res.json({ msg: "Autó törölve" });
  } catch (err) {
    res.status(500).json({ error: "DB error" });
  }
});

// CARS - update
app.put("/api/cars/:carId", auth, async (req, res) => {
  const carId = req.params.carId;
  const { make_model, vin, year } = req.body || {};
  
  if (!make_model || !vin) return res.status(400).json({ error: "Autó típusa és VIN kötelező." });

  const cleanVin = String(vin).trim().toUpperCase();
  if (cleanVin.length !== 17) return res.status(400).json({ error: "A VIN tipikusan 17 karakter." });

  try {
    const [rows] = await pool.query("SELECT user_id FROM cars WHERE id = ?", [carId]);
    if (!rows.length) return res.status(404).json({ error: "Autó nem található" });
    if (rows[0].user_id !== req.user.id) return res.status(403).json({ error: "Nincs jogosultság" });

    await pool.query(
      "UPDATE cars SET make_model = ?, vin = ?, year = ? WHERE id = ?",
      [String(make_model).trim(), cleanVin, year ? Number(year) : null, carId]
    );
    res.json({ msg: "Autó frissítve" });
  } catch (e) {
    if (String(e).includes("Duplicate")) return res.status(409).json({ error: "Ez a VIN már szerepel." });
    res.status(500).json({ error: "DB error" });
  }
});

// CAR detail + issues
app.get("/api/cars/:carId", auth, async (req, res) => {
  const carId = Number(req.params.carId);

  const [cars] = await pool.query("SELECT id, make_model, vin, year, image_url FROM cars WHERE id = ? AND user_id = ?", [
    carId,
    req.user.id,
  ]);
  if (!cars.length) return res.status(404).json({ error: "Nincs ilyen autó." });

  const [issues] = await pool.query(
    `SELECT i.id, i.category, i.description, i.urgency, i.created_at,
            r.service_name
     FROM issues i
     LEFT JOIN recommendations r ON r.issue_id = i.id
     WHERE i.car_id = ?
     ORDER BY i.created_at DESC`,
    [carId]
  );

  res.json({ car: cars[0], issues });
});

// ISSUE create + recommendation auto
app.post("/api/cars/:carId/issues", auth, async (req, res) => {
  const carId = Number(req.params.carId);
  const { category, description, urgency } = req.body || {};
  if (!category || !description) return res.status(400).json({ error: "Kategória és leírás kötelező." });

  const [cars] = await pool.query("SELECT id FROM cars WHERE id = ? AND user_id = ?", [carId, req.user.id]);
  if (!cars.length) return res.status(404).json({ error: "Nincs ilyen autó." });

  const [result] = await pool.query(
    "INSERT INTO issues (car_id, category, description, urgency) VALUES (?, ?, ?, ?)",
    [carId, String(category), String(description).trim(), urgency || "medium"]
  );

  const rec = recommend({ category, description });

  await pool.query(
    `INSERT INTO recommendations (issue_id, service_name, explanation, estimated_price_min, estimated_price_max)
     VALUES (?, ?, ?, ?, ?)`,
    [result.insertId, rec.service, rec.explanation, rec.min ?? null, rec.max ?? null]
  );

  res.status(201).json({ issueId: result.insertId });
});

// Recommendation get
app.get("/api/recommendations/:issueId", auth, async (req, res) => {
  const issueId = Number(req.params.issueId);

  const [rows] = await pool.query(
    `SELECT i.id AS issue_id, i.category, i.description, i.urgency, i.created_at,
            c.id AS car_id, c.make_model, c.vin, c.year,
            r.service_name, r.explanation, r.estimated_price_min, r.estimated_price_max
     FROM issues i
     JOIN cars c ON c.id = i.car_id
     JOIN recommendations r ON r.issue_id = i.id
     WHERE i.id = ? AND c.user_id = ?`,
    [issueId, req.user.id]
  );

  if (!rows.length) return res.status(404).json({ error: "Nincs ilyen ajánlás." });
  res.json({ data: rows[0] });
});

const port = Number(process.env.PORT || 4000);
app.listen(port, () => console.log(`Backend: http://localhost:${port}`));