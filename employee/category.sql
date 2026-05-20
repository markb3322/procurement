-- Create table
CREATE TABLE IF NOT EXISTS procurement_categories (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  name TEXT NOT NULL,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Create updated_at trigger
CREATE OR REPLACE FUNCTION update_procurement_category_timestamp()
RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = NOW();
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_update_procurement_category_timestamp
  BEFORE UPDATE ON procurement_categories
  FOR EACH ROW
  EXECUTE FUNCTION update_procurement_category_timestamp();

-- Enable RLS
ALTER TABLE procurement_categories ENABLE ROW LEVEL SECURITY;

-- Create policy for all operations
CREATE POLICY procurement_categories_all ON procurement_categories 
  FOR ALL 
  USING (true) 
  WITH CHECK (true);

-- ── INSERT ──
INSERT INTO procurement_categories (name) VALUES 
  ('Office Supplies'),
  ('Heavy Equipment'),
  ('ICT Devices'),
  ('Furniture'),
  ('Fabrication and Installation'),
  ('Appliances'),
  ('Fixtures'),
  ('Catering');

-- ── VIEW (All) ──
SELECT * FROM procurement_categories ORDER BY created_at DESC;

-- ── VIEW (Single) ──
SELECT * FROM procurement_categories WHERE id = (SELECT id FROM procurement_categories LIMIT 1);

-- ── VIEW (Search) ──
SELECT * FROM procurement_categories WHERE name ILIKE '%office%';

-- ── UPDATE ──
UPDATE procurement_categories 
SET name = 'Office Supplies & Stationery' 
WHERE id = (SELECT id FROM procurement_categories WHERE name = 'Office Supplies' LIMIT 1);

-- ── DELETE ──
DELETE FROM procurement_categories WHERE id = (SELECT id FROM procurement_categories WHERE name = 'Catering' LIMIT 1);

-- ── DELETE ALL ──
DELETE FROM procurement_categories;

-- ── COUNT ──
SELECT COUNT(*) AS total_categories FROM procurement_categories;

-- ── VIEW: Categories with record count (if linked to procurement_records) ──
CREATE VIEW view_categories_summary AS
SELECT 
  pc.id,
  pc.name,
  pc.created_at,
  pc.updated_at
FROM procurement_categories pc
ORDER BY pc.name ASC;

SELECT * FROM view_categories_summary;