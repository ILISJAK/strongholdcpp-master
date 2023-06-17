DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS saved_games;
DROP TABLE IF EXISTS highscores;
DROP TABLE IF EXISTS debug_logs;

CREATE TABLE users (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(30) NOT NULL,
  `password` VARCHAR(70) NOT NULL,
  `email` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE saved_games (
  id INT AUTO_INCREMENT PRIMARY KEY,
  turnNumber INT,
  currentPlayerIndex INT,
  players INT,
  towns TEXT,
  user_id INT,
  save_date DATETIME
);

CREATE TABLE highscores (
  highscore_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  save_date DATETIME,
  score DECIMAL(10,2),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE debug_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    log_message TEXT,
    log_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX user_id_idx ON highscores(user_id);

DROP PROCEDURE IF EXISTS CalculateHighScores;

CREATE OR REPLACE PROCEDURE CalculateHighScores(new_game_id INT, new_user_id INT)
BEGIN
    DECLARE townsData TEXT;
    DECLARE save_time DATETIME;
    DECLARE totalScore DECIMAL(10, 2) DEFAULT 0;
    DECLARE userExists INT DEFAULT 0;

    -- Fetch data into the variables
    SELECT towns, save_date INTO townsData, save_time
    FROM saved_games WHERE id = new_game_id;

    -- Check if user_id exists in users table
    SELECT COUNT(*) INTO userExists FROM users WHERE id = new_user_id;

    -- If townsData is not NULL and userExists is 1 (true), then proceed
    IF townsData IS NOT NULL AND userExists = 1 THEN
        SET @townData = JSON_EXTRACT(townsData, '$');
        SET @i = 0;
        SET @numTowns = JSON_LENGTH(@townData);

        WHILE @i < @numTowns DO
            SET @townKeys = JSON_KEYS(@townData);
            SET @townKey = JSON_UNQUOTE(JSON_EXTRACT(@townKeys, CONCAT('$[', @i, ']')));
            SET @town = JSON_EXTRACT(@townData, CONCAT('$.', @townKey));

            SET @population = CAST(JSON_UNQUOTE(JSON_EXTRACT(@town, '$.population.population')) AS DECIMAL(10, 2));
            SET @gold = CAST(JSON_UNQUOTE(JSON_EXTRACT(@town, '$.gold.gold')) AS DECIMAL(10, 2));
            SET @score = @population + @gold;

            SET totalScore = totalScore + @score;
            SET @i = @i + 1;
        END WHILE;

        INSERT INTO highscores (user_id, save_date, score)
        VALUES (new_user_id, save_time, totalScore);
    END IF;
END;

DROP TRIGGER IF EXISTS AfterGameSave;

CREATE TRIGGER AfterGameSave
AFTER INSERT ON saved_games
FOR EACH ROW
BEGIN
    CALL CalculateHighScores(NEW.id, NEW.user_id);
END;


INSERT INTO users (username, password, email)
VALUES ('JohnDoe', 'password123', 'jdoe@gmail.com'),
       ('JaneSmith', 'securepass456', 'jsmith@gmail.com'),
       ('RobertJohnson', 'secretword789', 'rjohnson@gmail.com');
      
INSERT INTO saved_games (turnNumber, currentPlayerIndex, players, towns, user_id, save_date)
VALUES (10, 2, 3, '{"town1": {"population": {"population": 100}, "gold": {"gold": 200}}}', 1, NOW());


SELECT * FROM highscores;
SELECT * FROM users u ;
SELECT * FROM saved_games sg ;

SELECT users.username, highscores.save_date, highscores.score 
FROM highscores
INNER JOIN users ON highscores.user_id = users.id
ORDER BY highscores.score DESC;

SHOW TRIGGERS;
SHOW VARIABLES LIKE 'log_bin';
SELECT * FROM debug_logs;
