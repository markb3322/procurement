CREATE TABLE members (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    user_id UUID NOT NULL,
    fullname TEXT NOT NULL,
    nickname TEXT NOT NULL,
    suffix TEXT,
    birthdate DATE NOT NULL,
    id_number TEXT NOT NULL,
    status TEXT DEFAULT 'Active',
    emergency_contact_name TEXT,
    emergency_contact_number TEXT,
    address TEXT,
    profile_image TEXT,
    background_image TEXT,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

CREATE INDEX idx_members_user_id ON members(user_id);
CREATE INDEX idx_members_id_number ON members(id_number);

CREATE OR REPLACE FUNCTION update_member_timestamp()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_update_member_timestamp
    BEFORE UPDATE ON members
    FOR EACH ROW
    EXECUTE FUNCTION update_member_timestamp();

ALTER TABLE members ENABLE ROW LEVEL SECURITY;

CREATE POLICY members_all_access ON members
    FOR ALL
    USING (true)
    WITH CHECK (true);

INSERT INTO members (
    user_id,
    fullname,
    nickname,
    suffix,
    birthdate,
    id_number,
    status,
    emergency_contact_name,
    emergency_contact_number,
    address,
    profile_image,
    background_image
) VALUES (
    gen_random_uuid(),
    'Juan Dela Cruz',
    'Juan',
    'Jr.',
    '1990-05-15',
    'BEPO-001',
    'Active',
    'Maria Dela Cruz',
    '09123456789',
    '123 Main Street, Barangay Santolan, Pasig City',
    'base64_profile_image_data',
    'base64_background_image_data'
);

UPDATE members 
SET 
    fullname = 'Juan Dela Cruz Updated',
    nickname = 'JuanD',
    suffix = 'Sr.',
    birthdate = '1990-05-15',
    status = 'Inactive',
    emergency_contact_name = 'Pedro Dela Cruz',
    emergency_contact_number = '09987654321',
    address = '456 Secondary Road, Barangay Ugong, Pasig City',
    profile_image = 'new_base64_profile_image_data',
    background_image = 'new_base64_background_image_data'
WHERE id_number = 'BEPO-001';

UPDATE members 
SET status = 'Active' 
WHERE id_number = 'BEPO-001';

DELETE FROM members 
WHERE id_number = 'BEPO-001';

DELETE FROM members 
WHERE user_id IN (SELECT user_id FROM members WHERE status = 'Inactive');

CREATE VIEW view_active_members AS
SELECT 
    id,
    user_id,
    fullname,
    nickname,
    suffix,
    birthdate,
    id_number,
    status,
    emergency_contact_name,
    emergency_contact_number,
    address,
    created_at,
    updated_at
FROM members
WHERE status = 'Active';

CREATE VIEW view_member_details AS
SELECT 
    id,
    user_id,
    fullname,
    nickname,
    suffix,
    birthdate,
    id_number,
    status,
    emergency_contact_name,
    emergency_contact_number,
    address,
    profile_image,
    background_image,
    created_at,
    updated_at,
    EXTRACT(YEAR FROM AGE(CURRENT_DATE, birthdate)) AS age
FROM members;

SELECT * FROM members;
SELECT * FROM members WHERE id_number = 'BEPO-001';
SELECT * FROM members WHERE status = 'Active';
SELECT * FROM members ORDER BY created_at DESC;
SELECT * FROM view_active_members;
SELECT * FROM view_member_details WHERE id_number = 'BEPO-001';

SELECT 
    id,
    fullname,
    nickname,
    id_number,
    status,
    created_at
FROM members
WHERE fullname ILIKE '%Juan%';

SELECT 
    id,
    fullname,
    id_number,
    EXTRACT(YEAR FROM AGE(CURRENT_DATE, birthdate)) AS age,
    status
FROM members
ORDER BY age ASC;

SELECT 
    status,
    COUNT(*) AS member_count
FROM members
GROUP BY status;

SELECT 
    id,
    fullname,
    nickname,
    id_number,
    emergency_contact_name,
    emergency_contact_number,
    address
FROM members
WHERE id_number = 'BEPO-001';