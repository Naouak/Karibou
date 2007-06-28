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
				SELECT	survey_surveys.*, survey_answers.userid as answeruserid
				FROM survey_surveys
				LEFT JOIN survey_answers ON survey_surveys.id = survey_answers.surveyid
					AND survey_answers.userid = ".$this->userFactory->getCurrentUser()->getId()."
				GROUP BY survey_surveys.id
				ORDER BY
					survey_surveys.datetime
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
	 * Récupère un sondage par rapport à son identifiant
	 */
	function getSurveyById ($surveyid)
	{
		$surveys = array();
	
		$sql = "
				SELECT	survey_surveys.*, survey_answers.userid as answeruserid
				FROM survey_surveys
				LEFT JOIN survey_answers ON survey_surveys.id = survey_answers.surveyid 
					AND survey_answers.userid = ".$this->userFactory->getCurrentUser()->getId()."
				WHERE
					id = '$surveyid'
				GROUP BY survey_surveys.id
				ORDER BY
					survey_surveys.datetime
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
	 * Créé le sondage à partir des variables présentes dans un tableau
	 */
	function createSurvey ($vars)
	{

		if (isset($vars['name'], $vars['description']))
		{
			$sql = "
					INSERT INTO survey_surveys
							(name,
							description,
							userid,
							datetime)
					VALUES	('".$vars['name']."',
							 '".$vars['description']."',
							'".$this->userFactory->getCurrentUser()->getId()."',
							NOW())
				";			
			
			try
			{
				$stmt = $this->db->exec($sql);
				$insertid = $this->db->lastInsertId();
				unset($stmt);
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
		
		if (isset($insertid))
		{
			$infos = array_merge($vars, array('id' => $insertid));
			$survey = new KSSurvey($infos, $this->userFactory);
			return $survey;
		}
		else
		{
			return false;
		}
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
	function setUserAnswersToQuestions ($survey, $userid = FALSE)
	{
		$answers = array();
	
		if ($userid == FALSE)
		{
			//If userid not specified, this means we need the current user "last" answers
			$uid = $this->userFactory->getCurrentUser()->getId();
		}
		else
		{
			//If userid is specified, this means we need all answers from the specified user
			$uid = $userid;
		}
	
		$sql = "
				SELECT
					*
				FROM
					survey_answers
				WHERE
					surveyid = '".$survey->getInfo("id")."'
				AND
					userid = '".$uid."'
				";

		if ($userid === FALSE)
		{
			$sql .= "
					AND
						last = 1
					";
		}
		
		$sql .= "
					ORDER BY datetime DESC
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
					if (!isset($answers[$answerinfos["userid"]][$answerinfos["versionid"]]))
					{
						$answers[$answerinfos["userid"]][$answerinfos["versionid"]] = array();
					}
					$answers[$answerinfos["userid"]][$answerinfos["versionid"]][$answerinfos["questionid"]] = new KSAnswer($answerinfos,$this->userFactory);
				}
			}
			else
			{
			}
		/*
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
		*/
		}
		catch(PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
		
		$survey->setAnswers($answers);
	}
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
					if (!isset($answers[$answerinfos["userid"]][$answerinfos["versionid"]]))
					{
						$answers[$answerinfos["userid"]][$answerinfos["versionid"]] = array();
					}
					$answers[$answerinfos["userid"]][$answerinfos["versionid"]][$answerinfos["questionid"]] = new KSAnswer($answerinfos,$this->userFactory);
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
	
	/**
	 * Save user answers
	 */
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

	/**
	 * Save survey details & questions
	 */
	public function saveSurveyDetails($survey)
	{
		if ($survey->getInfo("id") !== FALSE)
		{
			//Update if exists
			$sql = "
					UPDATE
						survey_surveys
					SET
						name = '".$survey->getInfo("name")."',
						description = '".$survey->getInfo("description")."'
					WHERE
						id = '".$survey->getInfo("id")."'
				";			
				
			try
			{
				$stmt = $this->db->exec($sql);
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
		else
		{
			//Create if it doesn't
			$sql = "
					INSERT INTO
						survey_surveys
					(name, description, userid, datetime)
					VALUES
						('".$survey->getInfo("name")."',
						'".$survey->getInfo("description")."',
						'".$this->userFactory->getCurrentUser()->getId()."',
						NOW())
				";			
				
			try
			{
				$stmt = $this->db->exec($sql);
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
		
				
	}
	
	public function saveSurveyQuestions($survey)
	{
		//Create insert or update request for each question
		
		//For each question
		foreach ($survey->getAllQuestions() as $questionid => $question)
		{
			$this->saveSurveyQuestion($question);
		}
	}
	
	public function saveSurveyQuestion($question)
	{
		if ($question->getInfo("delete") == "delete")
		{
			//Delete question
			$sql = "
					DELETE FROM
						survey_questions
					WHERE
						id = '".$question->getInfo("id")."'
				";			
				
			try
			{
				$stmt = $this->db->exec($sql);
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
		elseif ($question->getInfo("id") !== FALSE)
		{
			//Update if exists
			$sql = "
					UPDATE
						survey_questions
					SET
						`type` = '".$question->getInfo("type")."',
						name = '".$question->getInfo("name")."',
						description = '".$question->getInfo("description")."',
						datetime = NOW()
					WHERE
						id = '".$question->getInfo("id")."'
				";			
				
			try
			{
				$stmt = $this->db->exec($sql);
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
		elseif ($question->getInfo("name") != "")
		{
			//Create if it doesn't
			$sql = "
					INSERT INTO
						survey_questions
					(surveyid, `type`, name, description, datetime)
					VALUES
						('".$question->getInfo("surveyid")."',
						'".$question->getInfo("type")."',
						'".$question->getInfo("name")."',
						'".$question->getInfo("description")."',
						NOW())
				";			
				
			try
			{
				$stmt = $this->db->exec($sql);
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
	}

}

?>
