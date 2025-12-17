DROP DATABASE IF EXISTS PIF_25_26;
CREATE DATABASE PIF_25_26;
USE PIF_25_26;

CREATE TABLE users (
    user_id        INT AUTO_INCREMENT PRIMARY KEY,
    username       VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
    full_name      VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
    password       VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    email          VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
    is_admin       BOOLEAN
);

CREATE TABLE stations (
    station_id     INT AUTO_INCREMENT PRIMARY KEY,
    serial_number  VARCHAR(20) COLLATE utf8mb4_general_ci NOT NULL UNIQUE,
    name           VARCHAR(100) COLLATE utf8mb4_general_ci,
    description    TEXT COLLATE utf8mb4_general_ci,
    owner_id       INT NOT NULL,
    created_by     INT NOT NULL,
    FOREIGN KEY (owner_id) REFERENCES users(user_id),
    FOREIGN KEY (created_by) REFERENCES users(user_id)
);

CREATE TABLE collections (
    collection_id  INT AUTO_INCREMENT PRIMARY KEY,
    creator_id     INT NOT NULL,
    name           VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
    description    TEXT,
    FOREIGN KEY (creator_id) REFERENCES users(user_id)
);

CREATE TABLE measurements (
    measurement_id INT AUTO_INCREMENT PRIMARY KEY,
    station_id     INT NOT NULL,
    timestamp      DATETIME NOT NULL,
    temperature    DECIMAL(5,2), 
    humidity       DECIMAL(5,2),
    pressure       DECIMAL(7,2),
    light_intensity DECIMAL(10,2),
    air_quality    DECIMAL(7,2),
    FOREIGN KEY (station_id) REFERENCES stations(station_id)
);

CREATE TABLE user_friends (
    user_id      INT NOT NULL,
    friend_id    INT NOT NULL,
    status       BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY (user_id, friend_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (friend_id) REFERENCES users(user_id)
);
CREATE TABLE friend_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    requester_id INT NOT NULL,
    approver_id  INT NOT NULL
);

INSERT INTO users(username, full_name, email, is_admin) VALUES 
('jdoe', 'John Doe', 'jdoe@example.com', FALSE),
('asmith', 'Alice Smith', 'asmith@example.com', TRUE),
('bwayne', 'Bruce Wayne', 'bwayne@wayneenterprises.com', FALSE),
('ckent', 'Clark Kent', 'ckent@dailyplanet.com', FALSE),
('dprince', 'Diana Prince', 'dprince@themyscira.gov', TRUE);

INSERT INTO stations(serial_number, name, description, owner_id, created_by) VALUES 
('ST-001', 'Central Park Station', 'Main station located in Central Park', 1, 2),
('ST-002', 'Riverside Station', 'Station near Riverside Avenue', 3, 2),
('ST-003', 'Hilltop Station', 'Station positioned at the local hilltop viewpoint', 4, 5),
('ST-004', 'Airport Station', 'Station monitoring airport surroundings', 1, 2);

INSERT INTO measurements 
(station_id, timestamp, temperature, humidity, pressure, light_intensity, air_quality) VALUES
(1, '2025-01-10 12:00:00', 22, 55, 1012, 450, 35),
(1, '2025-01-10 12:05:00', 23, 57, 1011, 470, 40),
(2, '2025-01-10 12:10:00', 24, 54, 1013, 500, 32),
(2, '2025-01-10 12:40:00', 24, 52, 1012, 490, 31),
(3, '2025-01-10 12:45:00', 23, 55, 1011, 460, 33);

INSERT INTO collections (creator_id, name, description) VALUES 
(2, 'WeatherMetrics', 'A collection of weather-related environmental measurements'),
(2, 'AirQualityLogs', 'Contains air quality measurements from various stations'),
(1, 'LightStudies', 'Used for tracking light intensity over time'),
(3, 'HumidityTracking', 'Dataset focused on humidity trends'),
(5, 'FullEnvironmentSet', 'Complete set combining all environmental sensor metrics');

INSERT INTO user_friends (user_id, friend_id, status) VALUES
(1, 2, TRUE),
(1, 3, TRUE),
(2, 1, TRUE),
(2, 4, TRUE),
(3, 1, TRUE),
(4, 2, TRUE);