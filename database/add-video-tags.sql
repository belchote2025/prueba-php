-- Añadir campo tags a la tabla videos
ALTER TABLE videos ADD COLUMN IF NOT EXISTS tags VARCHAR(500) DEFAULT NULL AFTER categoria;
CREATE INDEX IF NOT EXISTS idx_tags ON videos(tags(255));

