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
	/*
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
				AND
					last = 1
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
	*/
	/**
	 * Connector that links the user answers to the KSSurvey questions
	 */
	function setAllAnswers ($survey)
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
					last = 1
				ORDER BY
					userid ASC, questionid ASC
			";			
			
		try
		{
			$stmt = $this->db->query($sql);
			$allanswersinfos = $stmt->fetchAll(PDO::FETCH_ASSOC);
			unset($stmt);
			
			if (count($allanswersinfos)>0)
			{
				$userid = '';
				foreach ($allanswersinfos as $answerinfos)
				{
					if (!isset($answers[$answerinfos["userid"]]))
					{
						$answers[$answerinfos["userid"]] = array();
					}
					$answers[$answerinfos["userid"]][$answerinfos["questionid"]] = new KSAnswer($answerinfos,$this->userFactory);
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
	
	/* Save user answers */
	function saveAnswers ($survey)
	{
		//Taking into account only questions to avoid forged answers id
		$questions = $survey->getAllQuestions();
		$userid = $this->userFactory->getCurrentUser()->getId();
		$surveyid = $survey->getInfo("id");

		//Set to 0 the `last` column
		$sql = "
				UPDATE
					survey_answers
				SET
					`last` = 0
				WHERE
					surveyid = '$surveyid'
				AND
					userid = '$userid'
			";
		try
		{
			$stmt = $this->db->exec($sql);
			unset($stmt);
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		

		//Find max version id + 1
		$versionid = 1;
		$sql = "
				SELECT
					MAX(`versionid`) AS maxversionid
				FROM
					survey_answers
				WHERE
					surveyid = '$surveyid'
				AND
					userid = '$userid'
			";			
			
		try
		{
			$stmt = $this->db->query($sql);
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$versionid = $result[0]["maxversionid"]+1;
			unset($stmt);
			
			//Building the new insert request
			$insertsql = "";
			foreach ($questions as $question)
			{
				if ($insertsql != "")
				{
					$insertsql .= ", ";
				}
	
				$questionid = $question->getInfo("id");
				$insertsql .= "
					(	$surveyid,
						$questionid,
						$versionid,
						1,
					 '".$survey->getAnswerById($questionid)."',
					 	$userid,
					 	NOW())";
			}
			$sql = "
					INSERT INTO
						survey_answers
					(`surveyid`,`questionid`,`versionid`,`last`,`value`,`userid`, `datetime`)
					VALUES
						$insertsql
				";
				
			try
			{
				$stmt = $this->db->exec($sql);
				unset($stmt);
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
			
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}	
	}

}

?>