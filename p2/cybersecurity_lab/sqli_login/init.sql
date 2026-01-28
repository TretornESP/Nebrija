CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL
);

INSERT IGNORE INTO users (username, password) VALUES ('admin', 'supersecretpassword123');
INSERT IGNORE INTO users (username, password) VALUES ('bob', 'pizza');
