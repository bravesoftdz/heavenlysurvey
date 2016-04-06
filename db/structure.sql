-- Run these create statements inside of Sqlite --

-- ------------------------------------------------------------------------
-- This table is meant for all the questions in the database
-- ------------------------------------------------------------------------
CREATE TABLE `questions` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`question_text`	TEXT NOT NULL
);

-- ------------------------------------------------------------------------
-- This table is meant for all the answer texts to each question
-- in the database
-- ------------------------------------------------------------------------
CREATE TABLE `answers` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`sort_order`	INTEGER NOT NULL DEFAULT 1,
	`answer_text`	TEXT NOT NULL,
	`question_id`	INTEGER NOT NULL,
	FOREIGN KEY(`question_id`) REFERENCES `questions`(`id`)
);

-- ------------------------------------------------------------------------
-- This table is meant for all the users that will answer the questions
-- ------------------------------------------------------------------------
CREATE TABLE `user_answers` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`date_answered`	INTEGER NOT NULL,
	`ip`	TEXT NOT NULL,
	`user_agent`	TEXT NOT NULL,
	`answer_id`	INTEGER,
	FOREIGN KEY(`answer_id`) REFERENCES `answers`(`id`)
);
