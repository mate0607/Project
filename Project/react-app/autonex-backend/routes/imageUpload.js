const express = require('express');
const multer = require('multer');
const path = require('path');
const fs = require('fs');
const router = express.Router();
const { verifyToken } = require('../middleware/auth');
const db = require('../db');

const uploadsDir = path.join(__dirname, '../uploads/cars');
if (!fs.existsSync(uploadsDir)) {
  fs.mkdirSync(uploadsDir, { recursive: true });
}

const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, uploadsDir);
  },
  filename: (req, file, cb) => {
    const ext = path.extname(file.originalname);
    const name = path.basename(file.originalname, ext);
    const filename = `${req.body.carId}_${Date.now()}${ext}`;
    cb(null, filename);
  }
});

const upload = multer({
  storage,
  limits: {
    fileSize: 5 * 1024 * 1024 // 5MB
  },
  fileFilter: (req, file, cb) => {
    const allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
    if (allowedMimes.includes(file.mimetype)) {
      cb(null, true);
    } else {
      cb(new Error('Csak képfájlok engedélyezettek'));
    }
  }
});

router.post('/upload-image', verifyToken, upload.single('image'), async (req, res) => {
  try {
    const { carId } = req.body;
    const userId = req.user.id;

    if (!carId) {
      return res.status(400).json({ error: 'Car ID szükséges' });
    }

    const [cars] = await db.query(
      'SELECT id FROM cars WHERE id = ? AND user_id = ?',
      [carId, userId]
    );

    if (cars.length === 0) {
      return res.status(404).json({ error: 'Autó nem található' });
    }

    if (!req.file) {
      return res.status(400).json({ error: 'Nincs fájl kiválasztva' });
    }

    const imageUrl = `/uploads/cars/${req.file.filename}`;
    
    await db.query(
      'UPDATE cars SET image_url = ? WHERE id = ?',
      [imageUrl, carId]
    );

    res.json({
      success: true,
      message: 'Kép sikeresen feltöltve',
      imageUrl
    });
  } catch (error) {
    console.error('Image upload error:', error);

    if (req.file) {
      fs.unlink(req.file.path, (err) => {
        if (err) console.error('File delete error:', err);
      });
    }

    res.status(500).json({
      error: error.message || 'Feltöltés sikertelen'
    });
  }
});

router.delete('/:carId/image', verifyToken, async (req, res) => {
  try {
    const { carId } = req.params;
    const userId = req.user.id;

    const [cars] = await db.query(
      'SELECT image_url FROM cars WHERE id = ? AND user_id = ?',
      [carId, userId]
    );

    if (cars.length === 0) {
      return res.status(404).json({ error: 'Autó nem található' });
    }

    const imageUrl = cars[0].image_url;

    if (imageUrl) {
      const filePath = path.join(__dirname, '..', imageUrl);
      fs.unlink(filePath, (err) => {
        if (err) console.error('File delete error:', err);
      });
    }

    await db.query(
      'UPDATE cars SET image_url = NULL WHERE id = ?',
      [carId]
    );

    res.json({
      success: true,
      message: 'Kép sikeresen törölve'
    });
  } catch (error) {
    console.error('Image delete error:', error);
    res.status(500).json({
      error: error.message || 'Törlés sikertelen'
    });
  }
});

module.exports = router;
