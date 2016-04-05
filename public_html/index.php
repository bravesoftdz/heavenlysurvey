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
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="css/fontface.css">
        <link rel="author" href="humans.txt">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.14.8/react.js"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.14.8/react-dom.js"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.23/browser.min.js"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/0.3.5/marked.min.js"></script>
    </head>
    <body>
    	<div id="content" class="parent">

    	</div>
		<script type="text/babel">
		var FrmQuestion = React.createClass({
			render: function(){
				return (
				    <div className="question"><?=$question->getText()?></div>
				);
			}
		});

		var FrmAnswers = React.createClass({
			render: function(){
				return (
					<div className="answers">
					<?php foreach ($answers as $answer): ?>
					    <label><input type="radio" name="answer" value="<?=$answer['id']?>" /><?=htmlentities($answer['answer_text'])?></label>
					    <div className="result"><span className="percent"></span><span className="stats">(<span className="percent"></span>, <span className="number"></span>)</span></div>
					<?php endforeach;?>
					</div>
				);
			}
		});

		var FrmSurvey = React.createClass({
		  render: function() {
		    return (
		      <form className="frmSurvey child">
		      	<input type="hidden" name="question_id" value="<?=$question->getId()?>" />
		      	<FrmQuestion />
		      	<FrmAnswers />
				<button type="submit">Submit</button>
		      </form>
		    );
		  }
		});

		ReactDOM.render(
		  <FrmSurvey />,
		  document.getElementById('content')
		);
		</script>

    	<?php if ($ERROR_MSG): ?>
			<div class="error box">
			<?=$ERROR_MSG?>
			</div>
        <?php else: ?>
        	<!--
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
		-->
        <script src="js/main.js"></script>
    </body>
</html>
