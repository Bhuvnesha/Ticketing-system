-- Users Table
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) UNIQUE,
  email VARCHAR(100) UNIQUE,
  password VARCHAR(255),
  role ENUM('admin', 'user') DEFAULT 'user'
);

-- Tickets Table
CREATE TABLE tickets (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(255),
  description TEXT,
  status ENUM('Open', 'In Progress', 'Closed') DEFAULT 'Open',
  priority ENUM('Low', 'Medium', 'High'),
  created_by INT,
  assigned_to INT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(id),
  FOREIGN KEY (assigned_to) REFERENCES users(id)
);

-- Comments Table
CREATE TABLE comments (
  id INT PRIMARY KEY AUTO_INCREMENT,
  ticket_id INT,
  user_id INT,
  comment TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (ticket_id) REFERENCES tickets(id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Forgot Password Feature
ALTER TABLE users 
ADD reset_token VARCHAR(255) DEFAULT NULL,
ADD reset_expires_at DATETIME DEFAULT NULL;