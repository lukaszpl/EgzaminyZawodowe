<?php 
function GetRandomQuestions($ExamID, $userCode, $NumberOfQuestions){
	require 'db_methods.php';
	$toReturn = "<div class=\"top\"><h1>Pozostało: <output id=\"remainingMinutes\">60</output> minut i <output id=\"remainingSeconds\">0</output> sekund.</h1> <form method=\"post\" action=\"check_answers.php\">"."<input type=\"hidden\" name=\"examtype\" value=\"".$ExamID."\">"."<input type=\"hidden\" name=\"userCode\" value=\"".$userCode."\">";
	$Questions_Id = randomNumbers(1,QuestionHandler::GetNumberOfQuestions($ExamID),$NumberOfQuestions);		
	for($i = 0; $i < $NumberOfQuestions; $i++){
		$Question = QuestionHandler::GetQuestionItem($ExamID, $Questions_Id[$i]);
		$toReturn .= "<div class=\"q\">
						<h2>".($i+1).". ".htmlentities($Question->Question)."</h2>";
		if($Question->ImageName != "undefined"){
			$toReturn .= "<figure><img src=\"".$Question->ImageName."\" alt=\"".htmlentities($Question->Question)."\"/></figure>";
		}	
		$toReturn .= "<ul>";	
		for($a = 0; $a < 4; $a++){
			$toReturn .="<li>
							<input type=\"radio\" id=".$Question->Id."-".$Question->ListOfAnswers[$a]->Id." name=".$Question->Id." value =\"".$Question->ListOfAnswers[$a]->Id."\">
							<label for=".$Question->Id."-".$Question->ListOfAnswers[$a]->Id.">".htmlentities($Question->ListOfAnswers[$a]->Answer)."</label>
						</li>";
		}
		$toReturn .= "<li><input type=\"radio\" name=\"".$Question->Id."\" value=\"-1\" style=\"display:none\" checked=\"checked\"/></li>";
		$toReturn .= "</ul>
				</div>";
	}
	$toReturn .= "<button type=\"submit\">Sprawdź!</button></form></div>";
	return $toReturn;
}

function randomNumbers($from,$to,$num){
	$array=array();
	for($i=$from;$i<$to;$i++){
		$array[$i]=$i;
	}
	shuffle($array);
	return array_slice($array,0,$num);

}
