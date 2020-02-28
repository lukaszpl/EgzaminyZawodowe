<?php 
$ARR=count($_POST)?$_POST:$_GET;
require_once 'db_classes.php';
require_once 'db_methods.php';
if($ARR['userCode']){
	require 'php_db/db_connect.php';
			$code = $ARR['userCode'];
			$type = $ARR['examtype'];
			$sql = "SELECT * FROM users WHERE userCode = '{$code}'";
			$result = $connect->query($sql);
			if($result->num_rows <= 0){
				//alert nie ma takiego ucznia
				echo "<script>alert(\"Wybrany kod ucznia nie istnieje!\"); window.location.href = \"index.php\";</script>";
				exit(0);
			}else{
				$row=$result->fetch_assoc();
				$canSolveBin=str_pad(decbin($row['canSolve']), 100, "0", STR_PAD_LEFT);
				if($canSolveBin[99-$type]==0){
					//alert zabroniono
					echo "<script>alert(\"Administrator zablokował Ci możliwość rozwiązywania tego testu!\"); window.location.href = \"index.php\";</script>";
					exit(0);
				}
			}	
}
$correctans = 0;
$incorrectans = 0;
$a = 0;
$toReturn = "";
$toSave = null;
foreach($ARR as $item => $value)
{
	if(($item != "examtype")&&($item != "previous")&&($item != "userCode")){
		//
		$toSave .= $item."=".$value."&";
		//
		$Question = QuestionHandler::GetQuestionItem($ARR['examtype'], $item);
		$toReturn .= "<div class=\"q\">
		<h2>".++$a.". ".$Question->Question."</h2>";
		if($Question->ImageName != "undefined"){
			$toReturn .= "<figure><img src=\"".$Question->ImageName."\"/></figure>";
		}	
		$toReturn .= "<ul>";
		for($i = 0; $i<4; $i++){
			$toReturn .= "<li>
							<label for=".$Question->Id."-".$Question->ListOfAnswers[$i]->Id."";
			if(CheckAnswer($ARR['examtype'], $item, $value)){							
				if($value == $i){
					$toReturn .= " class=\"correct chosen\"";
					$correctans++;
				}
			}else{
				if($i == $value)
					$toReturn .= " class=\"chosen\"";
					if(CheckAnswer($ARR['examtype'], $item, $i)){
						$toReturn .= " class=\"correct\"";
						$incorrectans++;
				}				
				
			}
		    $toReturn .= ">".htmlentities($Question->ListOfAnswers[$i]->Answer)."</label></li>";
		}
		$toReturn .= "</ul></div>";
	}
}
$shortcut=$ARR["previous"]?$ARR["previous"]:("index.php".($incorrectans==0?"?you=spin&me=round":""));
$percent = $correctans/($incorrectans+$correctans)*100;
echo "<div class=\"top\"><h1><a href=\"".$shortcut."\"><i class=\"fas fa-arrow-left\"></i> Liczba poprawnych odpowiedzi: ".$correctans."/".($incorrectans+$correctans)."</a><br>";
echo "Twój wynik to: ".$percent."%"."</h1>";
echo $toReturn;
echo "</div>";

//save in database
if($ARR['userCode'] != null){
	SaveResult($ARR['userCode'], $toSave, $ARR['examtype'],$correctans);
}

//////////////////////////////////////////////////////////////////////////////
function CheckAnswer($ExamID, $QuestionID, $UserAnswerID){

	require 'db_connect.php';	
	if($ExamID == 0)
		$sql = "SELECT * FROM e12 WHERE id=".$QuestionID;
	if($ExamID == 1)
		$sql = "SELECT * FROM e13 WHERE id=".$QuestionID;
	if($ExamID == 2)
		$sql = "SELECT * FROM e14 WHERE id=".$QuestionID;
	
	$result = $connect->query($sql);
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) 
		{
			if($row['ans'] == $UserAnswerID)
				return true;
			return false;
		}
	}
}

////////////////////////////////////////////////////////////////////////////
function SaveResult($userCode, $input, $ExamID, $score){
	require 'db_connect.php';	
	$date = date("Y-m-d H:i:s");
	$sql = "INSERT INTO history (Id, Data, userCode, Date, ExamID, Score, Total) VALUES ('', '{$input}', '{$userCode}', '{$date}', '{$ExamID}', '{$score}', 40)";

	if ($connect->query($sql) === TRUE) {
		echo "<br><center>Twój wynik został pomyślnie zapisany!</center>";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	//usun uprawienia
	$sql = "UPDATE `users` SET `canSolve` = '0' WHERE `users`.`userCode` = '{$userCode}'";
	$result = $connect->query($sql);
}
?>
