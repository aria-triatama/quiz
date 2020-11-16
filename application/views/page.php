<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Quiz</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="js/jquery-3.5.1.min.js"></script>
	<script src="js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container-fluid bg-dark min-vh-100">
	<div class="row vh-100">
		<div id="q-card" class="card mx-auto my-auto w-50">
			<div class="card-header">
				<input type="hidden" id="qid"/>
				<div id="question"></div>
			</div>
			<div class="card-body" id="choices">
				<div class="row">
					<div class="col"><input type="radio" name="choice" id="choice1"/><span class="ml-1" id="desc1"></span></div>
					<div class="col"><input type="radio" name="choice" id="choice2"/><span class="ml-1" id="desc2"></span></div>
				</div>
				<div class="row">
					<div class="col"><input type="radio" name="choice" id="choice3"/><span class="ml-1" id="desc3"></span></div>
					<div class="col"><input type="radio" name="choice" id="choice4"/><span class="ml-1" id="desc4"></span></div>
				</div>
				<a href="javascript:void(0)" class="btn btn-primary mt-4" id="submit">Next</a>
			</div>
		</div>
		<div id="res-card" class="card mx-auto my-auto w-50" style="display:none">
			<div class="card-body">
				<div class="row">
					<div class="col-3 font-weight-bold">Result</div>
					<div class="col-9 font-weight-bold" id="result"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
$(function() {
	Load();
});

$("#submit").click(function() {
	var number = $("#qid").val();
	var answer = $("input[name='choice']:checked").val();
	if (answer != undefined) {
		Load(number,answer);
	} else {
		alert("Pilih jawaban.");
	}
});

function Load(number=null, answer=null) {
	$.ajax({
		method: "POST",
		url: "<?php echo site_url("sesi/process"); ?>",
		data: { number: number, answer: answer }
	})
		.done(function(data) {
			if (data.success == 1) {
				if (data.result != null) {
					$("#result").html(data.result);
					$("#q-card").hide();
					$("#res-card").show();
				} else {
					$("#qid").val(data.question.id);
					$("#question").html("<span class='font-weight-bold mr-1'>"+data.question.id+".</span>"+data.question.question_desc);
					var num = 1;
					data.choices.forEach(function(choice) {
						$("#choice"+num).val(choice.choices_id);
						$("#desc"+num).html(choice.choice_head+"&nbsp;"+choice.choice_desc);
						num++;
					});
					$("input[name='choice']:checked").prop("checked", false);
				}
			} else {
				alert(data.message);
			}
	});
}
</script>

</body>
</html>