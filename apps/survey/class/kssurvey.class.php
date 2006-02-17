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
	
	//$this->answers[$userid][$versionid][$questionid]
	//3 level array containing the users answers versioned to the questions
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
		if (!isset($this->answers))
		{
			$this->answers = array();
		}
		if (!isset($this->answers[$this->userFactory->getCurrentUser()->getId()]))
		{
			$this->answers[$this->userFactory->getCurrentUser()->getId()] = array();
		}
		$this->answers[$this->userFactory->getCurrentUser()->getId()][0][$questionid] = new KSAnswer(array("questionid" => $questionid, "value" => $answer),$this->userFactory);
	}
	
	/**
	 * This method get a answer for the question defined by the questionid
	 * It works decently only in the cas where there is one version of answers of one user
	 */
	public function getAnswerById ($questionid)
	{
		if (count($this->answers)>0)
		{
			reset($this->answers);
			$answers = current(current($this->answers));
			if (isset($answers[$questionid]))
			{
				return $answers[$questionid]->getInfo("value");
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	/**
	 * This method defines if the user has already answered the survey
	 */
	public function userAnswered()
	{
		if ($this->getInfo("answeruserid") > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

?>