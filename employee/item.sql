CREATE TABLE IF NOT EXISTS procurement_items (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  category_id UUID REFERENCES procurement_categories(id) ON DELETE SET NULL,
  item_name TEXT NOT NULL,
  quantity INTEGER DEFAULT 0,
  unit TEXT,
  price NUMERIC DEFAULT 0,
  brand TEXT,
  description TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

ALTER TABLE procurement_items ENABLE ROW LEVEL SECURITY;
CREATE POLICY procurement_items_all ON procurement_items FOR ALL USING (true) WITH CHECK (true);