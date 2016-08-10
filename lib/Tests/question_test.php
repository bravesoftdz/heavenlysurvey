<?php

require_once __DIR__ . '/../autoloader.inc.php';

$qid = Heavenling\Survey\QuestionsUtil::AdminAdd('Rate your front-end experience:');

if ($qid === false) {
	echo 'Question already added, aborting test';
	exit;
}

var_dump($qid);

$question = new Heavenling\Survey\Question($qid);

$question->adminAddAnswer('0/5', 1);
$question->adminAddAnswer('1/5', 2);
$question->adminAddAnswer('2/5', 3);
$question->adminAddAnswer('3/5', 4);
$question->adminAddAnswer('4/5', 5);
$question->adminAddAnswer('5/5', 6);

echo $question->getId() . ' ' . $question->getText();

var_dump($question->fetchAnswers());
