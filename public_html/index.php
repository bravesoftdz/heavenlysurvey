<?php
/**
 * The goal of this page is to act as a survey with a question and 1..n answers.
 * If the user lands on this page without having voted, we will:
 * 	1) Set a cookie saying that the user has been on this page $_COOKIE['question'][q_id] = 'viewed-not-voted'
 *  2) After the user Submits his vote, using ajax we will call the vote script which will:
 *     a) Check the cookie to ensure it is 'viewed-not-voted'
 *     b) set the cookie to  $_COOKIE['question'][q_id] = <answer_id>
 *     c) Return the results of the poll in json {success: 1, already_voted: '', cookie_set: true, cookie_value: '12', 'question_text', 'stats': {q:32, answers: [{12: {votes: 152, percent: '14'}}, {13: {votes: 23, percent: 5}}, ... ], total_votes: 3356}
 *  3) Update the view to
 *     a) Display the voting bars
 *     b) Display the # of votes
 *     c) Display The percents
 * If the user lands on the page having already voted $_COOKIE['question'][q_id] != 'viewed-not-voted';
 *  1) Hide All the radio boxes,
 *  2) Hide the submit button,
 *  3) Ajax the page to grab the results.
 */
require_once __DIR__ . '/../lib/Earthling/Survey/Question.class.php';
$ERROR_MSG = false;
$question = new Earthling\Survey\Question();
if ($question->getId() == null) {
	$ERROR_MSG = 'There are no survey questions';
}
$answers = $question->fetchAnswers();

if (empty($_COOKIE['question_' . $question->getId()])) {
	setcookie(
		'question_' . $question->getId(),
		'viewed-not-voted',
		time() + (10 * 365 * 24 * 60 * 60)
	);
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Survey a user and keep track of their choice">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Survey</title>
        <link rel="stylesheet" href="css/styles.css">
        <link rel="author" href="humans.txt">
    </head>
    <body class="parent">
    	<div class="child">
	    	<h1>Earthling Survey</h1>
	    	<?php if ($ERROR_MSG): ?>
				<div class="error box">
				<?=$ERROR_MSG?>
				</div>
	        <?php else: ?>

	    	<section class="Survey">
			    <form class="frmSurvey">
				    <input type="hidden" name="question_id" value="<?=$question->getId()?>">
				    <h2 class="question"><?=$question->getText()?></h2>

					<div class="answers">
					<?php foreach ($answers as $answer): ?>
						<div class="answer">
						    <label><input type="radio" name="answer" value="<?=$answer['id']?>" /><?=htmlentities($answer['answer_text'])?></label>
						    <div class="result" id="answer_result_<?=$answer['id']?>"><span class="percent-graph"><span class="bar"></span></span><span class="stats">(<span class="percent"></span>, <span class="number"></span>)</span></div>
						</div>
					<?php endforeach;?>
					</div>
					<div class="totals">

					</div>
					<button type="submit" class="redButton" disabled="disabled">Vote</button>
			    </form>
		    </section>
			<?php endif;?>
		</div>
		<script src="https://code.jquery.com/jquery-1.12.3.min.js" integrity="sha256-aaODHAgvwQW1bFOGXMeX+pC4PZIPsvn2h1sArYOhgXQ=" crossorigin="anonymous"></script>
        <script src="js/main.js"></script>
    </body>
</html>
