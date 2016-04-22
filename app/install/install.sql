-- CREATE A NEW DATABASE `cesta` first

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS pass_link;
DROP TABLE IF EXISTS users;

-- ----------------------------------------------------------------------------> USERS
CREATE TABLE users (
  id INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  password VARCHAR(500) NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'admin',
  created_at TIMESTAMP NOT NULL,
  PRIMARY KEY (id)
) ENGINE='InnoDB' COLLATE 'utf8_general_ci';

-- ----------------------------------------------------------------------------> PASS LINK
CREATE TABLE pass_link (
  id int(11) NOT NULL AUTO_INCREMENT,
  hash varchar(500) NOT NULL,
  created_at timestamp NOT NULL,
  valid_until timestamp NULL,
  user_id int(11) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE='InnoDB' COLLATE 'utf8_general_ci';

-- TRIGGER FOR PASS LINK
DELIMITER $$
CREATE TRIGGER `pass_link_validity` BEFORE INSERT ON `pass_link`
 FOR EACH ROW BEGIN

  SET NEW.valid_until = CURRENT_TIMESTAMP + INTERVAL 14 DAY;

END
$$
DELIMITER ;

-- ----------------------------------------------------------------------------> INSERT A TEST USER
-- Don't forget to change the email to your email, otherwise
-- you won't be able to generate a new password for this user
INSERT INTO users(username) VALUES ('cieslar@webkreativ.cz');
