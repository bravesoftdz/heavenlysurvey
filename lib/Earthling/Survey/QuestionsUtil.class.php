<?php

namespace Earthling\Survey;

require_once __DIR__ . '/../../db.class.php';

class QuestionsUtil {

	//Static Utility Funcitons

	public static function AdminDeleteAll() {
		$prep = \db::pdo()->prepare('DELETE FROM questions ');
		$res1 = $prep->execute(array());

		$prep = \db::pdo()->prepare('DELETE FROM answers');
		$res2 = $prep->execute(array());
		return $res1 && $res2;
	}

	/**
	 * Add a question to the database
	 */
	public static function AdminAdd($question_text) {
		if (self::FetchByText($question_text)) {
			return false; //already added to database
		}
		$prep = \db::pdo()->prepare('INSERT INTO questions (question_text) VALUES (:question_text) ');
		$prep->execute(array(':question_text' => $question_text));
		return \db::pdo()->lastInsertId();
	}

	/**
	 * Fetch a given question by the question text
	 */
	public static function FetchByText($question_text) {
		try {
			$prep = \db::pdo()->prepare('SELECT id, question_text FROM questions WHERE question_text = :question_text');
			$prep->execute(array(':question_text' => $question_text));
			return $prep->fetch(\PDO::FETCH_ASSOC);

		} catch (PDOException $e) {
			//last_error = $e->getMessage();
			return false;
		}
	}

	/**
	 * Find the question from the answer id, good to verify that the answer is actually a part of the question
	 */
	public static function FetchQuestionFromAnswerId($answer_id) {
		try {
			$prep = \db::pdo()->prepare('SELECT questions.id, question_text, question_id FROM answers
					JOIN questions ON answers.question_id = questions.id
					WHERE answers.id = :answer_id');
			$prep->execute(array(':answer_id' => $answer_id));
			return $prep->fetch(\PDO::FETCH_ASSOC);

		} catch (PDOException $e) {
			//last_error = $e->getMessage();
			return false;
		}
	}
}
