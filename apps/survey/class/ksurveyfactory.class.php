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
class KSurveyFactory
{
	protected $db;
	protected $userFactory;

	public $surveys;

	function __construct(PDO $db, UserFactory $userFactory)
	{
		$this->db = $db;
		$this->userFactory = $userFactory;
	}

	/**
	 * Method that returns the survey list
	 */
	function getSurveyList ()
	{
		$surveys = array();
	
		$sql = "
				SELECT	*
				FROM survey_surveys
				ORDER BY
					datetime
					DESC
			";			
			
		try
		{
			$stmt = $this->db->query($sql);
			$surveysinfos = $stmt->fetchAll(PDO::FETCH_ASSOC);
			unset($stmt);
			
			if (count($surveysinfos)>0)
			{
				foreach ($surveysinfos as $surveyinfos)
				{
				
					$surveys[] = new KSSurvey($surveyinfos,$this->userFactory);
				}
			}
			else
			{
			}
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		return $surveys;
	}
	
	/**
	 * Method that returns the survey list
	 */
	function getSurveyById ($surveyid)
	{
		$surveys = array();
	
		$sql = "
				SELECT	*
				FROM survey_surveys
				WHERE id = '$surveyid'
				ORDER BY
					datetime
					DESC
			";			
			
		try
		{
			$stmt = $this->db->query($sql);
			$surveysinfos = $stmt->fetchAll(PDO::FETCH_ASSOC);
			unset($stmt);
			
			if (count($surveysinfos)>0)
			{
				foreach ($surveysinfos as $surveyinfos)
				{
					$survey = new KSSurvey($surveyinfos,$this->userFactory);
				}
			}
			else
			{
			}
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		return $survey;
	}



	/**
	 * Connector that set the questions array of the KSSurvey object
	 */
	function setQuestionsToSurvey ($survey)
	{
		$questions = array();
	
		$sql = "
				SELECT *
				FROM survey_questions
				WHERE surveyid = '".$survey->getInfo("id")."'
				ORDER BY
					id
					ASC
			";			
			
		try
		{
			$stmt = $this->db->query($sql);
			$questionsinfos = $stmt->fetchAll(PDO::FETCH_ASSOC);
			unset($stmt);
			
			if (count($questionsinfos)>0)
			{
				foreach ($questionsinfos as $questioninfos)
				{
				
					$questions[] = new KSQuestion($questioninfos,$this->userFactory);
				}
			}
			else
			{
			}
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		$survey->setQuestions($questions);
	}
	
	/**
	 * Connector that links the user answers to the KSSurvey questions
	 */
	function setUserAnswersToQuestions ($survey)
	{
		$answers = array();
	
		$sql = "
				SELECT
					*
				FROM
					survey_answers
				WHERE
					surveyid = '".$survey->getInfo("id")."'
				AND
					userid = '".$this->userFactory->getCurrentUser()->getId()."'
			";			
			
		try
		{
			$stmt = $this->db->query($sql);
			$answersinfos = $stmt->fetchAll(PDO::FETCH_ASSOC);
			unset($stmt);
			
			if (count($answersinfos)>0)
			{
				foreach ($answersinfos as $answerinfos)
				{
				
					$answers[$answerinfos["questionid"]] = new KSAnswer($answerinfos,$this->userFactory);
				}
			}
			else
			{
			}
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		$survey->setAnswers($answers);
	}

}

?>