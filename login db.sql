-- Create database (optional)
CREATE DATABASE IF NOT EXISTS proformance;
USE proformance;

-- Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------
-- Password hash used below is for: Pass123!
-- ---------------------------------------

-- Insert Customer Accounts
INSERT INTO users (name, email, password, role) VALUES
('Adam Khan', 'adam.khan@gmail.com', '$2y$10$2hlMcX8yKyYEGhGHI1Fe1O/umIe1nE5teLoI0lp4rWuH7tV/6zHvy', 'customer'),
('Sarah Malik', 'sarah.malik@outlook.com', '$2y$10$2hlMcX8yKyYEGhGHI1Fe1O/umIe1nE5teLoI0lp4rWuH7tV/6zHvy', 'customer');

-- Insert Admin Accounts
INSERT INTO users (name, email, password, role) VALUES
('Admin One', 'admin1@proformance.com', '$2y$10$2hlMcX8yKyYEGhGHI1Fe1O/umIe1nE5teLoI0lp4rWuH7tV/6zHvy', 'admin'),
('Admin Two', 'admin2@proformance.com', '$2y$10$2hlMcX8yKyYEGhGHI1Fe1O/umIe1nE5teLoI0lp4rWuH7tV/6zHvy', 'admin');
