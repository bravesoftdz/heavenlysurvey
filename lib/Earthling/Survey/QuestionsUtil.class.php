<?php
namespace Earthling\Survey;

/**
 * This class contains utility functions for all questions.
 *
 * Methods in here are better here than in the Questions class.
 * Encapsulate all these static methods to deal with as a utility class
 * for survey questions.
 */
class QuestionsUtil {

	/**
	 * Delete all questions and answers
	 * @return bool the result of the 2 sql pdo delete statements
	 */
	public static function AdminDeleteAll() {
		$prep = \db::pdo()->prepare('DELETE FROM questions ');
		$res1 = $prep->execute(array());

		$prep = \db::pdo()->prepare('DELETE FROM answers');
		$res2 = $prep->execute(array());
		return $res1 && $res2;
	}

	/**
	 * Add a question to the database
	 * @param string $question_text the question text which to add
	 * @return boolean|int false if question already exists, otherwise last question id inserted
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
	 * @param string $question_text the question text which to find a question by
	 * @return array|boolean associative array of the question or false on failure
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
	 * @param integer $answer_id a particular answer id (each one is unique)
	 * @return array|boolean associative array of the question or false if an error with the pdo or sql statements
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
