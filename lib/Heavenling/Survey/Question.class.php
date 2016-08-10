<?php
namespace Heavenling\Survey;

require_once(__DIR__ . '/QuestionsUtil.class.php');

/**
 * This class is the main model for handling questions and answers to the specific
 * quesiton.
 */
class Question {

	/**
	 * Fetch a given question row
	 * @param int|null question id to fetch or null if fetching first one
	 * @return array|boolean associative array of the question found or false if pdo sql error
	 */
	public static function Fetch($question_id = null) {
		try {
			$sql = 'SELECT id, question_text FROM questions';
			$ary = array();

			if (!is_null($question_id)) {
				$sql .= ' WHERE id = :id ';
				$ary = array(':id' => $question_id);
			} else {
				$sql .= ' LIMIT 1';
			}

			$prep = \db::pdo()->prepare($sql);
			$prep->execute($ary);
			return $prep->fetch(\PDO::FETCH_ASSOC);

		} catch (PDOException $e) {
			//last_error = $e->getMessage();
			return false;
		}
	}

	private $id = null;
	private $last_error = null;
	private $row = null;

	/**
	 * Get the id of the current question
	 * @return int|null the id of the question or null if doesn't exist.
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Determine if this question is valid
	 * @return boolean true if a valid question and id exists, false otherwise
	 */
	public function isValid() {
		return !is_null($this->id);
	}

	/**
	 * Get question text
	 * @return string|boolean question text if it exists, otherwise if invalid, return false
	 */
	public function getText() {
		if (isset($this->row['question_text'])) {
			return $this->row['question_text'];
		} else {
			return false;
		}
	}

	/**
	 * Retreive the last error if there is one
	 * @return string|null last error or null if no last error
	 */
	public function getLastError() {
		return $this->last_error;
	}

	/**
	 * Construct the question with given id,
	 * if it is null, just construct with first question
	 * in the database.
	 * @param int|null id of the question or null if we want to get first question
	 */
	public function __construct($id = null) {
		$this->row = self::Fetch($id);
		if (!empty($this->row)) {
			$this->id = $this->row['id'];
		}
	}

	/**
	 * Grab all the answers from this specific question
	 * @return array|boolean an array of answer associative arrays or false if pdo sql failure
	 */
	public function fetchAnswers() {
		try {
			$prep = \db::pdo()->prepare('SELECT id, sort_order, answer_text FROM answers WHERE question_id = :qid ORDER BY sort_order ASC');
			$prep->execute(array(':qid' => $this->id));
			return $prep->fetchAll(\PDO::FETCH_ASSOC);

		} catch (PDOException $e) {
			$this->last_error = $e->getMessage();
			return false;
		}
	}

	/**
	 * Get user answer statistics
	 * @return array all the statistics of each question the number of votes and the percent of each answer
	 */
	public function userAnswerStatistics() {
		$answers = $this->fetchAnswers();
		$stats = array('q' => $this->getId(), 'answers' => [], );
		$total = 0;
		foreach ($answers as $a) {
			$prep = \db::pdo()->prepare('SELECT COUNT(*) FROM user_answers WHERE answer_id = :answer_id');
			$params = [':answer_id' => $a['id']];
			$prep->execute($params);
			$stats['answers'][$a['id']] = ['votes' => $prep->fetchColumn(), 'percent' => 0];

			$total += $stats['answers'][$a['id']]['votes'];
		}
		$stats['total_votes'] = $total;

		$total_percent = 0;

		foreach ($stats['answers'] as $a_id => &$stat_answer) {
			$stat_answer['percent'] = round(($stat_answer['votes'] / $total) * 100);
			$total_percent += $stat_answer['percent'];
		}

		$stats['total_percent'] = $total_percent;

		return $stats;
	}

	/**
	 * Add an answer to this question
	 * @param string $answer_text the text of the answer to add to the question
	 * @param int $sort_order the sort order of where the answer should appear
	 */
	public function adminAddAnswer($answer_text, $sort_order = 1) {
		$prep = \db::pdo()->prepare('INSERT INTO answers (sort_order, answer_text, question_id) VALUES(:sort_order, :answer_text, :question_id) ');
		$res = $prep->execute(array(':answer_text' => $answer_text, ':sort_order' => $sort_order, ':question_id' => $this->id));
		return $res;
	}

	/**
	 * Delete all answers from this particular question
	 * @return boolean result of delete of all answers
	 */
	public function adminDeleteAllAnswers() {
		$prep = \db::pdo()->prepare('DELETE FROM answers WHERE question_id = :qid');
		$res = $prep->execute(array(':qid' => $this->id));
		return $res;
	}

	/**
	 * Append a user answer to the survey
	 * @param int $answer_id the answer id of the specific question
	 * @param string $ip remote ip address of user being added
	 * @param string $user_agent the user agent/browser of the user
	 * @return boolean true if executed properly, false on failure or no question found
	 */
	public function addUserAnswer($answer_id, $ip = null, $user_agent = null) {
		try {
			$question = QuestionsUtil::FetchQuestionFromAnswerId($answer_id);
			if (empty($question)) {
				//no question found
				return false;
			}

			$prep = \db::pdo()->prepare("INSERT INTO user_answers (answer_id, date_answered, ip, user_agent)
                              VALUES (:answer_id, :date_answered, :ip, :user_agent)");

			if (is_null($ip)) {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			if (is_null($user_agent)) {
				$user_agent = $_SERVER['HTTP_USER_AGENT'];
			}

			$res = $prep->execute(array('answer_id' => $answer_id, 'date_answered' => strtotime('now'), 'user_agent' => $user_agent,
				'ip' => $ip));
			return $res;
		} catch (PDOException $e) {
			$this->last_error = $e->getMessage();
			return false;
		}
	}
}
