<?php
/**
 *   After the user Submits his vote, using ajax we will call this script which will:
 *     a) Check the cookie to ensure it is 'viewed-not-voted'
 *     b) set the cookie to  $_COOKIE['question'][q_id] = <answer_id>
 *     c) Return the results of the poll in json {success: 1, already_voted: '', cookie_set: true, cookie_value: '12', 'question_text', {q:32, answers: [{12: {votes: 152, percent: '14'}}, {13: {votes: 23, percent: 5}}, ... ], total_votes: 3356}
 */

//Turn this on to disable checking for cookie
$DISABLE_COOKIE_CHECK = false;

require_once(__DIR__ . '/../lib/autoloader.inc.php');

if (empty($_REQUEST['question'])){
	die(json_encode(['error'=>'No question specified']));
}

$question = new Earthling\Survey\Question($_REQUEST['question']);


if (!$question->isValid()) {
	die(json_encode(['error' => 'Invalid question']));
}

$already_voted = false;
$cookie_value = false;

if (isset($_COOKIE['question_' . $question->getId()])) {
	if (is_numeric($_COOKIE['question_' . $question->getId()])) {
		//already voted just return values
		$already_voted = true;
		$cookie_value = $_COOKIE['question_' . $question->getId()];
	}
} else {
	$cookie_value = false;
}

$cookie_set = false;

if ($DISABLE_COOKIE_CHECK || !$already_voted) {

	$assert_question_row = Earthling\Survey\QuestionsUtil::FetchQuestionFromAnswerId($_REQUEST['answer']);

	if ($question->getId() != $assert_question_row['id']) {
		die(json_encode(['error' => 'Invalid question. Does not match answer']));
	}

	$answer_added = $question->addUserAnswer($_REQUEST['answer']);

	if ($answer_added) {
		setcookie(
			'question_' . $question->getId(),
			$_REQUEST['answer'],
			time() + (10 * 365 * 24 * 60 * 60)
		);
		$cookie_set = true;
	} else {
		die(json_encode(['error' => 'Server error. Answer could not be added']));
	}
}

die(json_encode(['success' => 1, 'already_voted' => $already_voted, 'cookie_set' => $cookie_set, 'cookie_value' => $cookie_value, 'question_text' => $question->getText(), 'stats' => $question->userAnswerStatistics()]));
