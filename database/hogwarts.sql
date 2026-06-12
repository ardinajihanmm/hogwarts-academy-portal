-- HOGWARTS ACADEMY PORTAL 
-- Semua password demo: password

CREATE DATABASE IF NOT EXISTS hogwarts_academy
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hogwarts_academy;

-- ── USERS ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(100) NOT NULL,
  email      VARCHAR(150) NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  role       ENUM('student','admin') NOT NULL DEFAULT 'student',
  house      ENUM('Gryffindor','Slytherin','Ravenclaw','Hufflepuff') DEFAULT NULL,
  xp         INT UNSIGNED NOT NULL DEFAULT 0,
  level      ENUM('Beginner Wizard','Advanced Wizard','Expert Wizard') NOT NULL DEFAULT 'Beginner Wizard',
  photo_url  VARCHAR(255) DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── COURSES ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS courses (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  course_name VARCHAR(150) NOT NULL,
  professor   VARCHAR(150) NOT NULL,
  difficulty  ENUM('Beginner','Intermediate','Advanced') NOT NULL DEFAULT 'Beginner',
  xp_reward   INT UNSIGNED NOT NULL DEFAULT 200,
  description TEXT,
  topics      TEXT,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── SPELLS ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS spells (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  spell_name  VARCHAR(150) NOT NULL,
  type        ENUM('Charm','Curse','Hex','Jinx','Counter-Spell','Transfiguration') NOT NULL DEFAULT 'Charm',
  difficulty  ENUM('Beginner','Intermediate','Advanced') NOT NULL DEFAULT 'Beginner',
  xp_reward   INT UNSIGNED NOT NULL DEFAULT 50,
  description TEXT,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── PROGRESS ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS progress (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id    INT UNSIGNED NOT NULL,
  course_id  INT UNSIGNED DEFAULT NULL,
  spell_id   INT UNSIGNED DEFAULT NULL,
  xp_earned  INT UNSIGNED NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id)   REFERENCES users(id)   ON DELETE CASCADE,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
  FOREIGN KEY (spell_id)  REFERENCES spells(id)  ON DELETE CASCADE,
  UNIQUE KEY uq_user_course (user_id, course_id),
  UNIQUE KEY uq_user_spell  (user_id, spell_id)
) ENGINE=InnoDB;

-- ── DEMO USERS ───────────────────────────────────────────

-- DEMO USERS
-- Password semua akun: password

INSERT INTO users
(username, email, password, role, house, xp, level)
VALUES

(
'Dumbledore',
'[dumbledore@hogwarts.edu](mailto:dumbledore@hogwarts.edu)',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'admin',
NULL,
0,
'Beginner Wizard'
),

(
'Harry Potter',
'[harry@hogwarts.edu](mailto:harry@hogwarts.edu)',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'student',
'Gryffindor',
1250,
'Advanced Wizard'
),

(
'Hermione Granger',
'[hermione@hogwarts.edu](mailto:hermione@hogwarts.edu)',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'student',
'Gryffindor',
1820,
'Expert Wizard'
),

(
'Draco Malfoy',
'[draco@hogwarts.edu](mailto:draco@hogwarts.edu)',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'student',
'Slytherin',
950,
'Advanced Wizard'
),

(
'Luna Lovegood',
'[luna@hogwarts.edu](mailto:luna@hogwarts.edu)',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'student',
'Ravenclaw',
450,
'Beginner Wizard'
),

(
'Cedric Diggory',
'[cedric@hogwarts.edu](mailto:cedric@hogwarts.edu)',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'student',
'Hufflepuff',
620,
'Advanced Wizard'
),

(
'Neville Longbottom',
'[neville@hogwarts.edu](mailto:neville@hogwarts.edu)',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'student',
'Gryffindor',
200,
'Beginner Wizard'
),

(
'Cho Chang',
'[cho@hogwarts.edu](mailto:cho@hogwarts.edu)',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'student',
'Ravenclaw',
760,
'Advanced Wizard'
);


-- COURSES

INSERT INTO courses
(course_name, professor, difficulty, xp_reward, description, topics)
VALUES
('Potions', 'Professor Severus Snape', 'Intermediate', 200, 'Learn the art of brewing magical potions with enchanted ingredients.', 'Brewing Basics,Ingredient Properties,Potion Safety,Advanced Concoctions'),

('Defense Against The Dark Arts', 'Professor Remus Lupin', 'Intermediate', 200, 'Practice defensive magic against dark creatures and dangerous spells.', 'Shield Charms,Dark Creature Defense,Counter-Curses,Patronus Charm'),

('Charms', 'Professor Filius Flitwick', 'Beginner', 200, 'Master useful magical spells for everyday wizarding life.', 'Wand Technique,Basic Charms,Charm Combinations,Advanced Charmwork'),

('Herbology', 'Professor Pomona Sprout', 'Beginner', 200, 'Study magical plants and their uses in the wizarding world.', 'Magical Plants,Harvesting Techniques,Plant Potions,Dangerous Flora'),

('Astronomy', 'Professor Aurora Sinistra', 'Beginner', 200, 'Explore stars and celestial movements connected to ancient magic.', 'Star Mapping,Planetary Influence,Celestial Events,Astrological Magic'),

('History of Magic', 'Professor Cuthbert Binns', 'Beginner', 200, 'Discover important events and legends from wizarding history.', 'Founding of Hogwarts,Goblin Rebellions,Dark Lord Wars,Famous Wizards'),

('Transfiguration', 'Professor Minerva McGonagall', 'Advanced', 200, 'Master the art of changing the form and appearance of an object.', 'Object Transformation,Animal Transfiguration,Human Transfiguration,Untransfiguration'),

('Care of Magical Creatures', 'Professor Rubeus Hagrid', 'Intermediate', 200, 'Learn to handle magical creatures of the wizarding world safely.', 'Creature Identification,Safe Handling,Feeding and Care,Magical Properties');


-- SPELLS

INSERT INTO spells
(spell_name, type, difficulty, xp_reward, description)
VALUES
('Lumos', 'Charm', 'Beginner', 50, 'Creates a beam of light from the wand tip, illuminating dark places.'),
('Alohomora', 'Charm', 'Beginner', 50, 'Unlocks doors and windows that are not magically protected.'),
('Wingardium Leviosa', 'Charm', 'Beginner', 50, 'Levitates objects into the air with precision.'),
('Expelliarmus', 'Charm', 'Beginner', 50, 'Disarms the opponent, causing whatever they hold to fly away.'),
('Accio', 'Charm', 'Intermediate', 50, 'Summons an object to the caster from any distance.'),
('Protego', 'Charm', 'Intermediate', 50, 'Creates an invisible shield that deflects minor hexes and jinxes.'),
('Riddikulus', 'Charm', 'Intermediate', 50, 'Used against a Boggart, transforming it into something amusing.'),
('Expecto Patronum', 'Charm', 'Advanced', 50, 'Conjures a Patronus to ward off Dementors. Requires a happy memory.'),
('Avada Kedavra', 'Curse', 'Advanced', 50, 'One of the three Unforgivable Curses. Causes instant death.'),
('Crucio', 'Curse', 'Advanced', 50, 'Inflicts unbearable pain on the victim without physical harm.'),
('Imperio', 'Curse', 'Advanced', 50, 'Places the victim in a trance-like state under the caster''s will.'),
('Stupefy', 'Hex', 'Beginner', 50, 'Stuns the target, rendering them unconscious with a red flash.'),
('Incendio', 'Hex', 'Beginner', 50, 'Conjures fire from the wand tip for illumination or combat.'),
('Serpensortia', 'Transfiguration', 'Intermediate', 50, 'Conjures a serpent from the tip of the wand.'),
('Obliviate', 'Charm', 'Advanced', 50, 'Erases specific memories from a person''s mind.');


-- PROGRESS
-- Asumsi ID user:
-- 1 Dumbledore
-- 2 Harry Potter
-- 3 Hermione Granger
-- 4 Draco Malfoy
-- 5 Luna Lovegood
-- 6 Cedric Diggory
-- 7 Neville Longbottom
-- 8 Cho Chang

INSERT INTO progress
(user_id, course_id, spell_id, xp_earned, created_at)
VALUES
(2, 1, NULL, 200, '2025-01-13 10:00:00'),
(2, 2, NULL, 200, '2025-01-14 11:00:00'),
(2, 3, NULL, 200, '2025-01-15 12:00:00'),
(2, NULL, 1, 50, '2025-01-16 09:00:00'),
(2, NULL, 2, 50, '2025-01-17 10:00:00'),
(2, NULL, 3, 50, '2025-01-18 11:00:00'),
(2, NULL, 4, 50, '2025-01-19 12:00:00'),

(3, 1, NULL, 200, '2025-01-13 10:00:00'),
(3, NULL, 1, 50, '2025-01-16 09:00:00');

-- ── TRIGGER: auto update level ─────────────────────────────
DELIMITER $$

CREATE TRIGGER trg_level_update
BEFORE UPDATE ON users
FOR EACH ROW
BEGIN
  IF NEW.xp >= 1500 THEN
    SET NEW.level = 'Expert Wizard';
  ELSEIF NEW.xp >= 500 THEN
    SET NEW.level = 'Advanced Wizard';
  ELSE
    SET NEW.level = 'Beginner Wizard';
  END IF;
END$$

DELIMITER ;
