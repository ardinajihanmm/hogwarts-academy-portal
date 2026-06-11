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

INSERT INTO users (id, username, email, password, role, house, xp, level, created_at) VALUES

('Dumbledore',
'dumbledore@hogwarts.edu',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'admin',NULL,0,NOW()),

('Harry Potter',
'harry.potter@hogwarts.edu',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'student','Gryffindor',1250,NOW()),

('Hermione Granger',
'hermione.granger@hogwarts.edu',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'student','Gryffindor',1820,NOW()),

('Draco Malfoy',
'draco.malfoy@hogwarts.edu',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'student','Slytherin',950,NOW()),

('Luna Lovegood',
'luna.lovegood@hogwarts.edu',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'student','Ravenclaw',450,NOW()),

('Cedric Diggory',
'cedric.diggory@hogwarts.edu',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'student','Hufflepuff',620,NOW()),

('Neville Longbottom',
'neville.longbottom@hogwarts.edu',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'student','Gryffindor',200,NOW()),

('Cho Chang',
'cho.chang@hogwarts.edu',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
'student','Ravenclaw',760,NOW());

-- ── COURSES ────────────────────────────────────────────────
INSERT INTO courses (id, course_name, professor, difficulty, xp_reward, description, topics) VALUES
(1, 'Potions',                       'Professor Severus Snape',       'Intermediate', 200, 'Learn the art of brewing magical potions with enchanted ingredients.',        'Brewing Basics,Ingredient Properties,Potion Safety,Advanced Concoctions'),
(2, 'Defense Against The Dark Arts', 'Professor Remus Lupin',         'Intermediate', 200, 'Practice defensive magic against dark creatures and dangerous spells.',       'Shield Charms,Dark Creature Defense,Counter-Curses,Patronus Charm'),
(3, 'Charms',                        'Professor Filius Flitwick',     'Beginner',     200, 'Master useful magical spells for everyday wizarding life.',                   'Wand Technique,Basic Charms,Charm Combinations,Advanced Charmwork'),
(4, 'Herbology',                     'Professor Pomona Sprout',       'Beginner',     200, 'Study magical plants and their uses in the wizarding world.',                 'Magical Plants,Harvesting Techniques,Plant Potions,Dangerous Flora'),
(5, 'Astronomy',                     'Professor Aurora Sinistra',     'Beginner',     200, 'Explore stars and celestial movements connected to ancient magic.',           'Star Mapping,Planetary Influence,Celestial Events,Astrological Magic'),
(6, 'History of Magic',              'Professor Cuthbert Binns',      'Beginner',     200, 'Discover important events and legends from wizarding history.',              'Founding of Hogwarts,Goblin Rebellions,Dark Lord Wars,Famous Wizards'),
(7, 'Transfiguration',               'Professor Minerva McGonagall',  'Advanced',     200, 'Master the art of changing the form and appearance of an object.',           'Object Transformation,Animal Transfiguration,Human Transfiguration,Untransfiguration'),
(8, 'Care of Magical Creatures',     'Professor Rubeus Hagrid',       'Intermediate', 200, 'Learn to handle magical creatures of the wizarding world safely.',           'Creature Identification,Safe Handling,Feeding and Care,Magical Properties');

-- ── SPELLS ─────────────────────────────────────────────────
INSERT INTO spells (id, spell_name, type, difficulty, xp_reward, description) VALUES
(1,  'Lumos',              'Charm',          'Beginner',     50, 'Creates a beam of light from the wand tip, illuminating dark places.'),
(2,  'Alohomora',          'Charm',          'Beginner',     50, 'Unlocks doors and windows that are not magically protected.'),
(3,  'Wingardium Leviosa', 'Charm',          'Beginner',     50, 'Levitates objects into the air with precision.'),
(4,  'Expelliarmus',       'Charm',          'Beginner',     50, 'Disarms the opponent, causing whatever they hold to fly away.'),
(5,  'Accio',              'Charm',          'Intermediate', 50, 'Summons an object to the caster from any distance.'),
(6,  'Protego',            'Charm',          'Intermediate', 50, 'Creates an invisible shield that deflects minor hexes and jinxes.'),
(7,  'Riddikulus',         'Charm',          'Intermediate', 50, 'Used against a Boggart, transforming it into something amusing.'),
(8,  'Expecto Patronum',   'Charm',          'Advanced',     50, 'Conjures a Patronus to ward off Dementors. Requires a happy memory.'),
(9,  'Avada Kedavra',      'Curse',          'Advanced',     50, 'One of the three Unforgivable Curses. Causes instant death.'),
(10, 'Crucio',             'Curse',          'Advanced',     50, 'Inflicts unbearable pain on the victim without physical harm.'),
(11, 'Imperio',            'Curse',          'Advanced',     50, 'Places the victim in a trance-like state under the caster\'s will.'),
(12, 'Stupefy',            'Hex',            'Beginner',     50, 'Stuns the target, rendering them unconscious with a red flash.'),
(13, 'Incendio',           'Hex',            'Beginner',     50, 'Conjures fire from the wand tip for illumination or combat.'),
(14, 'Serpensortia',       'Transfiguration','Intermediate', 50, 'Conjures a serpent from the tip of the wand.'),
(15, 'Obliviate',          'Charm',          'Advanced',     50, 'Erases specific memories from a person\'s mind.');

-- ── PROGRESS (sample for Harry Potter) ─────────────────────
INSERT INTO progress (user_id, course_id, spell_id, xp_earned, created_at) VALUES
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
