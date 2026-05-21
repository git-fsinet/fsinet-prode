-- Prode Mundial 2026 FSInet Database Schema

CREATE DATABASE IF NOT EXISTS prode_mundial;
USE prode_mundial;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    pin VARCHAR(255) NOT NULL,
    full_name VARCHAR(255),
    is_fan TINYINT(1) DEFAULT 0,
    points INT DEFAULT 0,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Stages table (Grupos, Octavos, etc.)
CREATE TABLE IF NOT EXISTS stages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    is_open TINYINT(1) DEFAULT 1, -- Can users bet on this stage?
    display_order INT DEFAULT 0
);

-- Matches table
CREATE TABLE IF NOT EXISTS matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    team1 VARCHAR(100) NOT NULL,
    team2 VARCHAR(100) NOT NULL,
    team1_flag VARCHAR(255),
    team2_flag VARCHAR(255),
    stage_id INT,
    group_name CHAR(1), -- 'A', 'B', 'C', etc.
    matchday INT, -- 1, 2, 3
    stadium VARCHAR(255),
    match_date DATETIME,
    result1 INT DEFAULT NULL,
    result2 INT DEFAULT NULL,
    status ENUM('pending', 'finished') DEFAULT 'pending',
    FOREIGN KEY (stage_id) REFERENCES stages(id)
);

-- Predictions table
CREATE TABLE IF NOT EXISTS predictions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    match_id INT,
    score1 INT,
    score2 INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY user_match (user_id, match_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (match_id) REFERENCES matches(id)
);

-- Initial Data
INSERT INTO stages (name, is_open, display_order) VALUES 
('Fase de Grupos', 1, 1),
('Dieciseisavos de Final', 0, 2),
('Octavos de Final', 0, 3),
('Cuartos de Final', 0, 4),
('Semifinales', 0, 5),
('Final', 0, 6);

-- Example Matches (World Cup 2026 Opener)
INSERT INTO matches (team1, team2, stage_id, group_name, matchday, stadium, match_date) VALUES 
-- Grupo A
('México', 'Por definir', 1, 'A', 1, 'Estadio Azteca', '2026-06-11 20:00:00'),
('Canadá', 'Por definir', 1, 'A', 1, 'BMO Field', '2026-06-12 18:00:00'),
-- Grupo B
('Estados Unidos', 'Por definir', 1, 'B', 1, 'SoFi Stadium', '2026-06-12 21:00:00'),
('Argentina', 'Brasil', 1, 'B', 1, 'MetLife Stadium', '2026-06-15 16:00:00');
