CREATE TABLE IF NOT EXISTS procurement_field_settings (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  category_id UUID REFERENCES procurement_categories(id) ON DELETE CASCADE,
  enabled_fields TEXT[] DEFAULT '{}',
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

ALTER TABLE procurement_field_settings ENABLE ROW LEVEL SECURITY;
CREATE POLICY procurement_field_settings_all ON procurement_field_settings FOR ALL USING (true) WITH CHECK (true);