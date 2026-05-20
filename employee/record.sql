CREATE TABLE IF NOT EXISTS procurement_records (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  category_id UUID REFERENCES procurement_categories(id),
  event UUID REFERENCES events(id),
  date DATE,
  quarter TEXT,
  menu TEXT,
  unit_pax TEXT,
  description TEXT,
  quantity INTEGER,
  item UUID REFERENCES procurement_items(id),
  supplier TEXT,
  mode_of_payment TEXT,
  scope_of_work TEXT,
  bid_doc_status TEXT,
  remarks TEXT,
  time TIME,
  pr_number TEXT,
  nc_number TEXT,
  po_number TEXT,
  padmo_number TEXT,
  go_finance_number TEXT,
  documents TEXT[] DEFAULT '{}',
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Enable Row Level Security
ALTER TABLE procurement_records ENABLE ROW LEVEL SECURITY;

-- Allow all operations for all users
CREATE POLICY procurement_records_all ON procurement_records
  FOR ALL
  USING (true)
  WITH CHECK (true);