-- database: community_tool_library
CREATE DATABASE IF NOT EXISTS community_tool_library CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE community_tool_library;

-- tools table
CREATE TABLE IF NOT EXISTS tools (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  `condition` ENUM('New','Good','Fair','Needs Repair') DEFAULT 'Good',
  quantity INT DEFAULT 1,
  image VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- members table
CREATE TABLE IF NOT EXISTS members (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fullname VARCHAR(150) NOT NULL,
  email VARCHAR(150) UNIQUE,
  phone VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- loans table
CREATE TABLE IF NOT EXISTS loans (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tool_id INT NOT NULL,
  member_id INT NOT NULL,
  loaned_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  due_date DATE,
  returned_at DATETIME DEFAULT NULL,
  notes TEXT,
  FOREIGN KEY (tool_id) REFERENCES tools(id) ON DELETE CASCADE,
  FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

-- sample data
INSERT INTO tools (name, description, `condition`, quantity) VALUES
('Cordless Drill', '18V cordless drill with two batteries', 'Good', 2),
('Lawn Mower', 'Electric push lawn mower', 'Fair', 1),
('Hammer', 'Claw hammer 16oz', 'Good', 5);

INSERT INTO members (fullname, email, phone) VALUES
('Alice Umutoni', 'alice@example.com', '+250788000111'),
('Ben Turatsinze', 'ben@example.com', '+250788000222');

INSERT INTO loans (tool_id, member_id, due_date) VALUES
(1, 1, DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY));
