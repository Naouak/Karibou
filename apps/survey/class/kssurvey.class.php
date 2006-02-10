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

	function getAllQuestions()
	{
		return $this->questions;
	}

	function getQuestionById($questionid)
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
	function setQuestions ($questions)
	{
		$this->questions = $questions;
	}	
	function setAnswers ($answers)
	{
		$this->answers = $answers;
	}
	
	/**
	 * This method get the last answer of the user for the question defined by the questionid
	 */
	function getAnswer ($questionid)
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