/** 
 * Using poll results, populate percentages and statistics,
 * and hide all the form stuff like the submit button and radio 
 * buttons
 */
function show_poll_results(stats){

	for (var answer_id in stats['answers']){
		var stat = stats['answers'][answer_id];

		var $ans_res = $('#answer_result_'+answer_id);
		$ans_res.find('.percent-graph .bar').css('width', stat['percent']+'px');
		$ans_res.find('.stats .percent').html(stat['percent']+'%');
		$ans_res.find('.stats .number').html(stat['votes']+' votes');
	}

	$('.frmSurvey .totals').html(stats.total_votes + ' total votes');

	$('.answer .result').show();
	$('input').hide();
	$('button[type=submit]').hide();
}


function ajax_vote_or_refresh(buildStr){
	 $.ajax({
      type: "GET",
      url: "ajax_vote.php",
      dataType: "json",
      data: buildStr
    }).done(function( msg ) {
         if (msg.error){
         	alert(msg.error);
         }else{
         	show_poll_results(msg.stats);
         }  
    });  
}

$('form.frmSurvey').submit(function(event){
	var values = $(this).serializeArray();
	var hasAnswer = false;
	var buildStr = '';

	$.each(values, function(index, obj){
		if (buildStr != ''){
			buildStr += '&';
		}
		if (obj.name == 'answer'){
			hasAnswer = true;
			buildStr += 'answer='+encodeURIComponent(obj.value);
			$('#answer_result_'+obj.value).addClass('voted');
		}else if (obj.name == 'question_id'){
			buildStr += 'question='+encodeURIComponent(obj.value);
		}
	});

    if (!hasAnswer){
    	alert('Please select an answer');
    	return false;
    }

    ajax_vote_or_refresh(buildStr);

	event.preventDefault();
});

$('input[type=radio]').click(function(){
	$('button[type=submit]').attr('disabled', false);
})