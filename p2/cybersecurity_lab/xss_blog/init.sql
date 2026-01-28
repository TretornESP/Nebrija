CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content TEXT NOT NULL
);

INSERT IGNORE INTO users (username, password) VALUES ('admin', '$2y$10$8.XhX/./././././././././././././././././././././.'); -- Password hash for 'admin' (dummy)
