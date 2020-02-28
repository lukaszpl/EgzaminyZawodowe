<?php 
class AnswerItem {
	public $Id;
    public $Answer;
	function __construct($Id, $Answer) {
       $this->Id=$Id;
	   $this->Answer=$Answer;	   
   }
}
class QuestionItem
{
    public $Id;
    public $IdCorrectAnswer;
    public $Question;
    public $ImageName;
    public $ListOfAnswers;
    function __construct($Id, $Question, $IdCorrectAnswer, $ImageName, $ListOfAnswers)
    {
		$this->Id = $Id;
		$this->IdCorrectAnswer = $IdCorrectAnswer;
		$this->Question = $Question;
		$this->ImageName = $ImageName;
		$this->ListOfAnswers = $ListOfAnswers;
    }
}
?>