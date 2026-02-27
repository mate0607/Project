require("dotenv").config();
const pool = require("./db");

async function runMigrations() {
  console.log("Running migrations...");
  
  try {
    // Create sales table
    const salesTableSQL = `
      CREATE TABLE IF NOT EXISTS sales (
        id INT AUTO_INCREMENT PRIMARY KEY,
        car_id INT NOT NULL UNIQUE,
        user_id INT NOT NULL,
        price DECIMAL(10, 2),
        description TEXT,
        car_condition VARCHAR(50),
        mileage INT,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX (user_id),
        INDEX (is_active),
        INDEX (created_at)
      )
    `;
    
    await pool.query(salesTableSQL);
    console.log("✓ Sales table created/verified");

    // Create messages table
    const messagesTableSQL = `
      CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sale_id INT NOT NULL,
        sender_id INT NOT NULL,
        receiver_id INT NOT NULL,
        message TEXT NOT NULL,
        is_read BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
        FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX (sale_id),
        INDEX (sender_id),
        INDEX (receiver_id),
        INDEX (created_at)
      )
    `;

    await pool.query(messagesTableSQL);
    console.log("✓ Messages table created/verified");
    
  } catch (err) {
    console.error("Migration error:", err);
    process.exit(1);
  }
  
  process.exit(0);
}

runMigrations();

runMigrations();
