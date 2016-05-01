<?php
	include "functions.php";
	
	LoadPage("Quiz Generator", "assignments");
	
?>
<script>
	var currentQuestion = 1;
	var questionPtr = 1;
	
	var answer = 
	{
		num: 1,
		title: "",
		numOfAns: 0,
		ansList: [],
		correct: 0
	
	};
	
	var list = new Array();
	
</script>
	<input type='hidden' id='quizData'>
	<div id="hiddenQuizData">
	
	</div>
<div class="col-lg-9">
	<table>
		<tr>
			<td style="padding-bottom: 10px;">Quiz Title: </td><td style="padding-bottom: 10px;padding-left: 15px;"><input id='quizTitle' type="text" class="form-control" style="width: 250px;"></td>
		</tr>
		<tr>
			<td style="padding-bottom: 10px;">Number of Questions: </td><td style="padding-bottom: 10px;padding-left: 15px;"><input id="quizNum" type="number" class="form-control" style="width: 250px;"></td>
		</tr>
		<tr>
			<td style="padding-bottom: 10px;">Points Per Question: </td><td style="padding-bottom: 10px;padding-left: 15px;"><input id="quizPts" type="number" class="form-control" style="width: 250px;"></td>
		</tr>
		<tr>
			<td style="padding-bottom: 10px;">Due Date: </td><td style="padding-bottom: 10px;padding-left: 15px;"><input id="quizDate" type="text" class="form-control" style="width: 250px;"></td>
		</tr>
		<tr>
			<td colspan="2"><button type="button" class="btn btn-success btn-block"  data-toggle="modal" data-target="#basicModal" onclick="StartGenerator();">Start Quiz Generator</button></td>
		</tr>
	</table>

</div>

<div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
				<table style='width:100%'><tr><td><h4 id="modalTitle" class="modal-title" id="myModalLabel"></h4></td><td><h4 id='modalQuestion' style="float: right;"></h4></td></tr></table>
            </div>
            <div class="modal-body">
				<table style="width: 100%;">
					<tr><td>Number of Answers: </td><td><input id="ansNum" type='number' class="form-control" onblur="GenerateOptions();"></td>
					<tr><td style="width: 150px;">Question Title: </td><td style="padding-bottom: 10px;"><input id="questionTitle" type="text" class="form-control" ></td><tr>
				</table>
				<div id="questions">
				
				
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" style="float: left;" onclick="PrevQuestion();">Previous</button><button class="btn btn-primary" style="float: right;" onclick="NextQuestion();">Next</button>
			</div>
        </div>

    </div>
</div>
<script>
	function StartGenerator()
	{
		document.getElementById('modalTitle').innerHTML = "Create Quiz - " + document.getElementById('quizTitle').value;
		document.getElementById('modalQuestion').innerHTML = "Q #" + currentQuestion;
		$('#basicModal').modal(options);

		
	}
	
	function GenerateOptions()
	{
		var optionSpace = document.getElementById("questions");
		var numOfQuestions = document.getElementById("ansNum").value;
		
		var ptr = questionPtr-1;
		
		optionSpace.innerHTML = "<table style='width:100%;'>";
		
		for( var i = 0; i < numOfQuestions; i++)
		{
			optionSpace.innerHTML += "<input type='radio' name='ans'><input id='Q" + questionPtr.toString() + "-" + i.toString() + "' class='form-control' style='padding-left: 20px;' type='text'><br/>";
		}
		
		optionSpace.innerHTML += "</table>";

	}
	
	function NextQuestion()
	{
		var optionSpace = document.getElementById("questions");
		var numOfQuestions = document.getElementById("ansNum").value;
		var tle = document.getElementById("questionTitle").value;
		
		var ptr = questionPtr-1;
		
		
		var data = [];

		for(var i = 0; i < numOfQuestions; i++)
		{
			data.push(document.getElementById("Q" + questionPtr + "-" + i).value);			
		}
		
		var a = {num: currentQuestion, title: tle, numOfAns: numOfQuestions, answers: data, correct: 0};
		
		
		if(questionPtr == currentQuestion)
		{
			tle = "";
			optionSpace.innerHTML = "";
			list.push(a);
			currentQuestion += 1;
			alert("HE IS HERE");
		}
		
		else
		{
			list[ptr].num = a.num;
			list[ptr].title = a.title;
			list[ptr].numOfAns = a.numOfAns;
			list[ptr].answers = data;
			list[ptr].correct = 0;
			
			// tle = list[ptr].title;
			
			optionSpace.innerHTML = "<table style='width:100%;'>";
		
			for(var i = 0; i < list[ptr].numOfAns; i++)
			{
				optionSpace.innerHTML += "<input type='radio' name='ans'><input id='Q" + list[ptr].num.toString() + "-" + i.toString() + "' class='form-control' style='padding-left: 20px;' type='text' value='" + list[ptr].answers[i] +"'><br/>";
			}

			optionSpace.innerHTML += "</table>";
		}
		
		numOfQuestions = 0;	
		
		questionPtr+=1;
		
		document.getElementById('modalQuestion').innerHTML = "Q #" + questionPtr.toString();
		
	}
	
	function PrevQuestion()
	{
		questionPtr -= 1;
		
		document.getElementById('modalQuestion').innerHTML = "Q #" + questionPtr;
		
		var ptr = questionPtr - 1;
		
		
		var optionSpace = document.getElementById("questions");
		var numOfQuestions = list[ptr].numOfAns;
		
		optionSpace.innerHTML = "<table style='width:100%;'>";
		
		for( var i = 0; i < numOfQuestions; i++)
		{
			optionSpace.innerHTML += "<input type='radio' name='ans'><input id='Q" + list[ptr].num.toString() + "-" + i.toString() + "' class='form-control' style='padding-left: 20px;' type='text' value='" + list[ptr].answers[i] +"'><br/>";
		}

		optionSpace.innerHTML += "</table>";
	
	}
</script>
<?php

	UnloadPage();
	
?>