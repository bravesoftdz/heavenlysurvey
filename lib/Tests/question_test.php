<?php

require_once __DIR__ . '/../Earthling/Survey/Question.class.php';

$qid = Earthling\Survey\Question::AdminAdd('What is the weather like today?');

if ($qid === false) {
	echo 'Question already added, aborting test';
	exit;
}

var_dump($qid);

$question = new Earthling\Survey\Question($qid);

$question->adminAddAnswer('Cold', 1);
$question->adminAddAnswer('Breezy', 2);
$question->adminAddAnswer('Hot', 3);

echo $question->getId() . ' ' . $question->getText();

var_dump($question->fetchAnswers());
