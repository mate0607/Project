const express = require("express");
const router = express.Router();
const db = require("../db");
const { auth } = require("./auth");

// Időpont foglalás
router.post("/", auth, async (req, res) => {
  const { car_id, date, time, service } = req.body;
  const user_id = req.user.id;

  try {
    const [existing] = await db.query(
      "SELECT id FROM appointments WHERE date = ? AND time = ?",
      [date, time]
    );
    
    if (existing.length > 0) {
      return res.status(409).json({ error: "Ez az időpont már foglalt. Kérjük, válasszon másik időpontot." });
    }

    const [result] = await db.query(
      "INSERT INTO appointments (user_id, car_id, date, time, service) VALUES (?,?,?,?,?)",
      [user_id, car_id, date, time, service]
    );
    res.json({ msg: "Foglalás rögzítve", id: result.insertId });
  } catch (err) {
    return res.status(500).json({ error: "DB error", err });
  }
});

router.get("/my", auth, async (req, res) => {
  try {
    const [rows] = await db.query(
      `SELECT 
        a.*, 
        u.name as user_name, 
        u.email as user_email,
        c.make_model as car_name,
        c.vin as car_vin
      FROM appointments a
      LEFT JOIN users u ON a.user_id = u.id
      LEFT JOIN cars c ON a.car_id = c.id
      WHERE a.user_id=?
      ORDER BY a.date DESC, a.time DESC`,
      [req.user.id]
    );
    res.json(rows);
  } catch (err) {
    res.status(500).json({ error: "DB error" });
  }
});

router.get("/admin", auth, async (req, res) => {
  if (req.user.role !== "admin") return res.sendStatus(403);

  try {
    const [rows] = await db.query(
      `SELECT 
        a.*, 
        u.name as user_name, 
        u.email as user_email,
        u.phone as user_phone,
        c.make_model as car_name,
        c.vin as car_vin
      FROM appointments a
      LEFT JOIN users u ON a.user_id = u.id
      LEFT JOIN cars c ON a.car_id = c.id
      ORDER BY a.date DESC, a.time DESC`
    );
    res.json(rows);
  } catch (err) {
    res.status(500).json({ error: "DB error" });
  }
});

router.put("/:id/status", auth, async (req, res) => {
  if (req.user.role !== "admin") return res.sendStatus(403);

  const { status } = req.body;
  const id = req.params.id;

  try {
    await db.query("UPDATE appointments SET status=? WHERE id=?", [status, id]);
    res.json({ msg: "Státusz frissítve" });
  } catch (err) {
    res.status(500).json({ error: "DB error" });
  }
});

// Delete appointment
router.delete("/:id", auth, async (req, res) => {
  const id = req.params.id;

  try {
    const [rows] = await db.query("SELECT user_id FROM appointments WHERE id=?", [id]);
    if (!rows.length) return res.status(404).json({ error: "Foglalás nem található" });
    
    if (rows[0].user_id !== req.user.id && req.user.role !== "admin") {
      return res.status(403).json({ error: "Nincs jogosultság" });
    }

    await db.query("DELETE FROM appointments WHERE id=?", [id]);
    res.json({ msg: "Foglalás törölve" });
  } catch (err) {
    res.status(500).json({ error: "DB error" });
  }
});

router.put("/:id", auth, async (req, res) => {
  const id = req.params.id;
  const { date, time, service } = req.body;

  try {
    const [rows] = await db.query("SELECT user_id FROM appointments WHERE id=?", [id]);
    if (!rows.length) return res.status(404).json({ error: "Foglalás nem található" });
    
    if (rows[0].user_id !== req.user.id && req.user.role !== "admin") {
      return res.status(403).json({ error: "Nincs jogosultság" });
    }

    const [existing] = await db.query(
      "SELECT id FROM appointments WHERE date = ? AND time = ? AND id != ?",
      [date, time, id]
    );
    
    if (existing.length > 0) {
      return res.status(409).json({ error: "Ez az időpont már foglalt." });
    }

    await db.query(
      "UPDATE appointments SET date=?, time=?, service=? WHERE id=?",
      [date, time, service, id]
    );
    res.json({ msg: "Foglalás frissítve" });
  } catch (err) {
    res.status(500).json({ error: "DB error" });
  }
});

module.exports = router;