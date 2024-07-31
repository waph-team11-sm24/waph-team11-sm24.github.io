-- Drop tables if they exist
use waph_team;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS superusers;
DROP TABLE IF EXISTS users;

-- Create tables
CREATE TABLE users (
  username VARCHAR(50) PRIMARY KEY,
  password VARCHAR(255) NOT NULL,
  name VARCHAR(100),
  email VARCHAR(100),
  phone VARCHAR(20)
);

CREATE TABLE posts (
  postID INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  content TEXT NOT NULL,
  date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  username VARCHAR(50) NOT NULL,
  FOREIGN KEY (username) REFERENCES users(username)
);

CREATE TABLE comments (
  commentID INT AUTO_INCREMENT PRIMARY KEY,
  content TEXT NOT NULL,
  date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  postID INT NOT NULL,
  username VARCHAR(50) NOT NULL,
  FOREIGN KEY (postID) REFERENCES posts(postID),
  FOREIGN KEY (username) REFERENCES users(username)
);

CREATE TABLE superusers (
  username VARCHAR(50) PRIMARY KEY,
  password VARCHAR(255) NOT NULL
);
