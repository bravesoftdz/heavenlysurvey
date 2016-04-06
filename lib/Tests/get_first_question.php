<?php
/**
 * This test will grab the first question in the database and show the answers
 */

require_once __DIR__ . '/../Earthling/Survey/Question.class.php';

$question = new Earthling\Survey\Question();

echo $question->getId() . ' ' . $question->getText() . "\n";

$answers = $question->fetchAnswers();

foreach ($answers as $answer) {
	echo $answer['id'] . ' ' . $answer['sort_order'] . ' ' . $answer['answer_text'] . "\n";
}
