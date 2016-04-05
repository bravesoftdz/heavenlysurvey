<?php
require_once __DIR__ . '/../lib/Earthling/Survey/Question.class.php';
$ERROR_MSG = false;
$question = new Earthling\Survey\Question();
if ($question->getId() == null) {
	$ERROR_MSG = 'There are no survey questions';
}
$answers = $question->fetchAnswers();
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Survey a user and keep track of their choice">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Survey</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="author" href="humans.txt">
    </head>
    <body>
    	<?php if ($ERROR_MSG): ?>
			<div class="error box">
			<?=$ERROR_MSG?>
			</div>
        <?php else: ?>
    	<section class="Survey">
	    <form class="frmSurvey">
	    <input type="hidden" name="question_id" value="<?=$question->getId()?>">
	    <div class="question"><?=$question->getText()?></div>
	    <?php foreach ($answers as $answer): ?>

	    <label><input type="radio" name="answer" value="<?=$answer['id']?>"><?=htmlentities($answer['answer_text'])?></label><div class="result">

		<?php endforeach;?>

	    </form>
	    </section>
		<?php endif;?>

	    <script src="https://code.jquery.com/jquery-1.12.2.min.js" integrity="sha256-lZFHibXzMHo3GGeehn1hudTAP3Sc0uKXBXAzHX1sjtk=" crossorigin="anonymous"></script>
        <script src="js/main.js"></script>
    </body>
</html>
