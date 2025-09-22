-- Initialize demo database
CREATE TABLE IF NOT EXISTS demo_info (
    id SERIAL PRIMARY KEY,
    key VARCHAR(50) NOT NULL UNIQUE,
    value TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS visitors (
    id SERIAL PRIMARY KEY,
    ip_address INET,
    user_agent TEXT,
    visited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    page_url VARCHAR(255)
);

-- Insert demo data
INSERT INTO demo_info (key, value) VALUES
    ('site_name', 'Demo1 SMCLab.net'),
    ('version', '1.0.0'),
    ('environment', 'production'),
    ('ssl_enabled', 'true'),
    ('database_engine', 'PostgreSQL 15'),
    ('last_deployed', CURRENT_TIMESTAMP::text)
ON CONFLICT (key) DO UPDATE SET
    value = EXCLUDED.value,
    updated_at = CURRENT_TIMESTAMP;

-- Create indexes for performance
CREATE INDEX IF NOT EXISTS idx_visitors_visited_at ON visitors(visited_at);
CREATE INDEX IF NOT EXISTS idx_visitors_ip_address ON visitors(ip_address);

-- Create a function to update the updated_at timestamp
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Create trigger for demo_info table
DROP TRIGGER IF EXISTS update_demo_info_updated_at ON demo_info;
CREATE TRIGGER update_demo_info_updated_at
    BEFORE UPDATE ON demo_info
    FOR EACH ROW
    EXECUTE FUNCTION update_updated_at_column();