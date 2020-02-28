<?php 
require_once 'db_classes.php';

class QuestionHandler{
	//e12 = 0; e13=1; e14=2	
	function GetNumberOfQuestions($ExamID){
		require 'db_connect.php';
		if($ExamID == 0)
			$sql = "SELECT * FROM e12";
		if($ExamID == 1)
			$sql = "SELECT * FROM e13";
		if($ExamID == 2)
			$sql = "SELECT * FROM e14";
	
		$result = $connect->query($sql);
		return $result->num_rows;
	}

	function GetQuestionItem($ExamID, $QuestionID){
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
				$AnswersList = array(new AnswerItem(0, $row['a']),new AnswerItem(1, $row['b']), new AnswerItem(2, $row['c']),new AnswerItem(3, $row['d']));
				return new QuestionItem($row['id'], $row['q'], $row['ans'],$row['i'], $AnswersList);
			}
		}
	}
}
?>