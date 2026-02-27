ALTER TABLE cars ADD COLUMN image_url VARCHAR(255) DEFAULT NULL AFTER year;
ALTER TABLE cars ADD COLUMN image_uploaded_at TIMESTAMP DEFAULT NULL AFTER image_url;
CREATE INDEX idx_cars_image ON cars(image_url);
