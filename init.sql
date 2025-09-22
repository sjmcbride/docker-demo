-- Initialize demo database with sample tables
CREATE TABLE IF NOT EXISTS demo_sites (
    id SERIAL PRIMARY KEY,
    site_name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS visitors (
    id SERIAL PRIMARY KEY,
    site_name VARCHAR(50) NOT NULL,
    ip_address INET,
    user_agent TEXT,
    visited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO demo_sites (site_name, description) VALUES
    ('demo1', 'First demo website - showcasing basic functionality'),
    ('demo2', 'Second demo website - featuring advanced components'),
    ('demo3', 'Third demo website - demonstrating integrations');

-- Create index for performance
CREATE INDEX idx_visitors_site_name ON visitors(site_name);
CREATE INDEX idx_visitors_visited_at ON visitors(visited_at);