<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 **/

/**
 *
 * @package applications
 **/
class KSSurvey extends KSElement
{
	protected $questions;
	protected $answers;

	/**
	 * Get all methods
	 */ 
	public function getAllQuestions()
	{
		return $this->questions;
	}
	public function getAllAnswers()
	{
		return $this->answers;
	}


	public function getQuestionById($questionid)
	{
		foreach ($this->questions as $id => $question)
		{
			if ($question->getInfo("id") == $questionid)
			{
				return $question;
			}
		}
		
		return FALSE;
	}
	
	/**
	 * Set methods
	 */
	public function setQuestions ($questions)
	{
		$this->questions = $questions;
	}	
	public function setAnswers ($answers)
	{
		$this->answers = $answers;
	}
	public function setAnswerById($questionid, $answer)
	{
		$this->answers[$questionid] = new KSAnswer(array("questionid" => $questionid, "value" => $answer),$this->userFactory);;
	}
	
	/**
	 * This method get the last answer of the user for the question defined by the questionid
	 */
	public function getAnswerById ($questionid)
	{
		if (isset($this->answers[$questionid]))
		{
			return $this->answers[$questionid]->getInfo("value");
		}
		else
		{
			return FALSE;
		}
	}
}

?>