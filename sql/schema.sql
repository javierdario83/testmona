CREATE DATABASE IF NOT EXISTS tarot_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tarot_app;

CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  display_name VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE packages (
  code VARCHAR(5) PRIMARY KEY,
  label VARCHAR(50) NOT NULL,
  questions_count INT NOT NULL,
  price DECIMAL(10,2) NOT NULL
);

CREATE TABLE cards (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  image_path VARCHAR(255) NULL
);

CREATE TABLE questions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  question_text VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE readings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  correlative VARCHAR(20) NOT NULL UNIQUE,
  client_name VARCHAR(120) NOT NULL,
  gender ENUM('M','F') NOT NULL,
  age INT NOT NULL,
  package_code VARCHAR(5) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  session_date DATE NOT NULL,
  reading_text TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_readings_package FOREIGN KEY (package_code) REFERENCES packages(code)
);

CREATE TABLE reading_cards (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reading_id INT NOT NULL,
  card_id INT NOT NULL,
  position INT NOT NULL,
  CONSTRAINT fk_rc_reading FOREIGN KEY (reading_id) REFERENCES readings(id) ON DELETE CASCADE,
  CONSTRAINT fk_rc_card FOREIGN KEY (card_id) REFERENCES cards(id)
);

CREATE TABLE reading_answers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reading_id INT NOT NULL,
  question_id INT NOT NULL,
  answer_text TEXT NOT NULL,
  audio_path VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_ra_reading FOREIGN KEY (reading_id) REFERENCES readings(id) ON DELETE CASCADE,
  CONSTRAINT fk_ra_question FOREIGN KEY (question_id) REFERENCES questions(id)
);

INSERT INTO packages (code,label,questions_count,price) VALUES
('P1','1 pregunta',1,10.00),
('P2','2 preguntas',2,18.00),
('P3','3 preguntas',3,25.00),
('P5','5 preguntas',5,38.00)
ON DUPLICATE KEY UPDATE label=VALUES(label), questions_count=VALUES(questions_count), price=VALUES(price);

-- Usuario inicial: heidy / brujosa123
INSERT INTO admins (username,password_hash,display_name)
VALUES ('heidy','$2y$10$NTjv7VxRk9qwvRjlI0Y0S.1lq84sQ5Q4udQxoGQ7m7XJgWzAKmJqC','Heidy')
ON DUPLICATE KEY UPDATE display_name=VALUES(display_name);

INSERT INTO cards (name,image_path) VALUES
('El Loco','https://upload.wikimedia.org/wikipedia/commons/9/90/RWS_Tarot_00_Fool.jpg'),
('La Sacerdotisa','https://upload.wikimedia.org/wikipedia/commons/8/88/RWS_Tarot_02_High_Priestess.jpg'),
('La Emperatriz','https://upload.wikimedia.org/wikipedia/commons/d/d2/RWS_Tarot_03_Empress.jpg'),
('El Emperador','https://upload.wikimedia.org/wikipedia/commons/c/c3/RWS_Tarot_04_Emperor.jpg'),
('El Hierofante','https://upload.wikimedia.org/wikipedia/commons/8/8d/RWS_Tarot_05_Hierophant.jpg')
ON DUPLICATE KEY UPDATE name=VALUES(name);
