-- Initialize database for SMCLab authentication
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    site VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create index for faster lookups
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_site ON users(site);

-- Insert demo users for each site
INSERT INTO users (username, email, password_hash, site) VALUES
    ('demo1user', 'demo1@smclab.net', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'demo1'),
    ('demo2user', 'demo2@smclab.net', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'demo2'),
    ('demo3user', 'demo3@smclab.net', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'demo3'),
    ('demo4user', 'demo4@smclab.net', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'demo4'),
    ('admin', 'admin@smclab.net', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'all');

-- Create sessions table for session management
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    site VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL
);

CREATE INDEX idx_sessions_expires ON sessions(expires_at);

-- Gamification Tables

-- Player progression and stats
CREATE TABLE IF NOT EXISTS player_stats (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    total_xp INTEGER DEFAULT 0,
    level INTEGER DEFAULT 1,
    rank VARCHAR(50) DEFAULT 'Junior Technician',
    lab1_xp INTEGER DEFAULT 0,
    lab2_xp INTEGER DEFAULT 0,
    lab3_xp INTEGER DEFAULT 0,
    lab4_xp INTEGER DEFAULT 0,
    challenges_completed INTEGER DEFAULT 0,
    achievements_unlocked INTEGER DEFAULT 0,
    longest_streak INTEGER DEFAULT 0,
    current_streak INTEGER DEFAULT 0,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Achievement system
CREATE TABLE IF NOT EXISTS achievements (
    id SERIAL PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    icon VARCHAR(10) NOT NULL,
    points INTEGER DEFAULT 50,
    category VARCHAR(50) NOT NULL,
    unlock_condition TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Player achievements (unlocked badges)
CREATE TABLE IF NOT EXISTS player_achievements (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    achievement_id INTEGER REFERENCES achievements(id) ON DELETE CASCADE,
    unlocked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, achievement_id)
);

-- Challenge tracking
CREATE TABLE IF NOT EXISTS challenges (
    id SERIAL PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    lab VARCHAR(10) NOT NULL,
    difficulty VARCHAR(20) DEFAULT 'medium',
    base_points INTEGER DEFAULT 100,
    time_limit INTEGER DEFAULT 300,
    success_criteria TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Player challenge attempts
CREATE TABLE IF NOT EXISTS challenge_attempts (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    challenge_id INTEGER REFERENCES challenges(id) ON DELETE CASCADE,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    success BOOLEAN DEFAULT FALSE,
    points_earned INTEGER DEFAULT 0,
    time_taken INTEGER DEFAULT 0,
    hints_used INTEGER DEFAULT 0,
    multiplier DECIMAL(3,2) DEFAULT 1.0
);

-- Leaderboards
CREATE TABLE IF NOT EXISTS leaderboard_entries (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    lab VARCHAR(10) NOT NULL,
    score INTEGER NOT NULL,
    rank INTEGER DEFAULT 0,
    period VARCHAR(20) DEFAULT 'all_time',
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Daily challenges
CREATE TABLE IF NOT EXISTS daily_challenges (
    id SERIAL PRIMARY KEY,
    challenge_date DATE UNIQUE NOT NULL,
    lab VARCHAR(10) NOT NULL,
    challenge_type VARCHAR(50) NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    bonus_multiplier DECIMAL(3,2) DEFAULT 2.0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes for performance
CREATE INDEX idx_player_stats_user ON player_stats(user_id);
CREATE INDEX idx_player_achievements_user ON player_achievements(user_id);
CREATE INDEX idx_challenge_attempts_user ON challenge_attempts(user_id);
CREATE INDEX idx_challenge_attempts_challenge ON challenge_attempts(challenge_id);
CREATE INDEX idx_leaderboard_lab ON leaderboard_entries(lab, score DESC);

-- Insert default achievements
INSERT INTO achievements (code, name, description, icon, points, category, unlock_condition) VALUES
('fire_fighter', 'Fire Fighter', 'Resolve 10 critical server incidents', '🔥', 200, 'systems', 'complete_incidents:10'),
('security_guardian', 'Security Guardian', 'Complete all security challenges', '🛡️', 500, 'security', 'complete_security_challenges:all'),
('speed_demon', 'Speed Demon', 'Complete 5 challenges under time limit', '⚡', 300, 'performance', 'fast_completions:5'),
('problem_solver', 'Problem Solver', 'Fix 3 complex multi-step issues', '🧠', 400, 'problem_solving', 'complex_fixes:3'),
('lab_master_1', 'Lab 1 Master', 'Achieve 100% completion in Lab 1', '👑', 1000, 'mastery', 'lab_completion:1:100'),
('lab_master_2', 'Lab 2 Master', 'Achieve 100% completion in Lab 2', '👑', 1000, 'mastery', 'lab_completion:2:100'),
('lab_master_3', 'Lab 3 Master', 'Achieve 100% completion in Lab 3', '👑', 1000, 'mastery', 'lab_completion:3:100'),
('lab_master_4', 'Lab 4 Master', 'Achieve 100% completion in Lab 4', '👑', 1000, 'mastery', 'lab_completion:4:100'),
('first_login', 'Welcome to SMC', 'Complete your first login', '🎯', 50, 'milestone', 'first_login'),
('streak_5', 'Consistent Player', 'Login 5 days in a row', '📅', 150, 'engagement', 'login_streak:5'),
('streak_30', 'Dedicated Engineer', 'Login 30 days in a row', '🏆', 500, 'engagement', 'login_streak:30'),
('network_ninja', 'Network Ninja', 'Master all network challenges', '🌐', 600, 'networking', 'complete_network_challenges:all'),
('database_guru', 'Database Guru', 'Excel in all database challenges', '💾', 600, 'database', 'complete_database_challenges:all'),
('code_crusher', 'Code Crusher', 'Write perfect queries 10 times', '💻', 350, 'development', 'perfect_queries:10');

-- Insert default challenges
INSERT INTO challenges (code, name, description, lab, difficulty, base_points, time_limit, success_criteria) VALUES
('server_down_critical', 'Critical Server Down', 'Diagnose and restore a failed web server within 5 minutes', 'lab1', 'hard', 200, 300, 'restore_service_time:<300'),
('memory_leak_hunt', 'Memory Leak Detective', 'Find and fix the process consuming excessive memory', 'lab1', 'medium', 150, 180, 'identify_process_and_fix'),
('performance_tuning', 'Performance Optimizer', 'Optimize server response time to under 2 seconds', 'lab1', 'medium', 100, 240, 'response_time:<2000'),
('network_trace_maze', 'Network Troubleshooting Maze', 'Follow packet routes to identify network failures', 'lab2', 'medium', 150, 300, 'trace_complete_path'),
('security_breach_response', 'Security Breach Response', 'Identify and block malicious network traffic', 'lab2', 'hard', 200, 120, 'block_threats_quickly'),
('load_balancer_master', 'Load Balancer Master', 'Keep all servers healthy while balancing traffic', 'lab2', 'easy', 100, 180, 'maintain_server_health'),
('sql_query_race', 'SQL Query Racing', 'Write optimal database queries faster than the clock', 'lab3', 'medium', 125, 120, 'query_performance_and_speed'),
('data_corruption_detective', 'Data Detective', 'Find and fix data corruption in customer records', 'lab3', 'hard', 175, 240, 'identify_corrupted_records'),
('backup_recovery_hero', 'Backup Recovery Hero', 'Restore database from corrupted backup files', 'lab3', 'hard', 200, 360, 'successful_data_recovery'),
('vulnerability_hunter', 'Vulnerability Hunter', 'Identify security flaws in application code', 'lab4', 'hard', 200, 300, 'find_critical_vulnerabilities'),
('password_fortress', 'Password Fortress', 'Create unbreakable authentication system', 'lab4', 'medium', 150, 240, 'withstand_brute_force'),
('digital_forensics', 'Digital Forensics Investigation', 'Investigate security incident and find the culprit', 'lab4', 'hard', 250, 600, 'solve_security_incident');

-- Initialize player stats for existing users
INSERT INTO player_stats (user_id)
SELECT id FROM users
WHERE NOT EXISTS (
    SELECT 1 FROM player_stats WHERE player_stats.user_id = users.id
);

-- Note: Default password for all demo users is 'password'
-- Password hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi