<?php
require_once __DIR__ . '/../Heavenling/Survey/Question.class.php';

$question = new Heavenling\Survey\Question();

echo $question->getId() . ' ' . $question->getText() . "\n";

$answers = $question->fetchAnswers();

$submitted = false;
$random = rand(0, count($answers) - 1);

foreach ($answers as $i => $answer) {

	echo $answer['id'] . ' ' . $answer['sort_order'] . ' ' . $answer['answer_text'] . "\n";
	if ($submitted == false && $i === $random) {
		$question->addUserAnswer($answer['id'], '127.0.0.1', 'Command Line');
		$submitted = true;
		echo ".\n";
	}
}
