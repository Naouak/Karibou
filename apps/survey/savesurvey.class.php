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
class KSSaveSurvey extends FormModel
{
	public function build()
	{
		$this->setRedirectArg('app', 'survey');
	
		$mySF = new KSurveyFactory ($this->db, $this->userFactory);
		
		if (isset($_POST["surveyid"]) && $_POST["surveyid"] != "")
		{
			//Modify survey

			//Load survey
			$mySurvey = $mySF->getSurveyById($_POST["surveyid"]);
			
			//Check permissions
			if ($mySurvey->canUpdate())
			{
				//Load existing questions
				$mySF->setQuestionsToSurvey($mySurvey);
				
				//Update survey details
				$mySurvey->setInfo("name", $_POST["ks_surveyname"]);
				$mySurvey->setInfo("description", $_POST["ks_surveydescription"]);
				
				//Update survey questions
				//Browse post vars to find questions
				$qinfos = array();
				foreach($_POST as $key => $value)
				{
					if (preg_match("/ks_q([0-9]+)_([a-z]+)$/", $key, $match))
					{
						$questionid = $match[1];
						$label = $match[2];
						if (!isset($qinfos[$questionid]))
						{
							$qinfos[$questionid] = array();
						}
						
						//Group questions infos by id
						$qinfos[$questionid] = array_merge($qinfos[$questionid], array($label => $value));
					}
				}
				
				//Browse post vars to find new questions
				$nqinfos = array();
				foreach($_POST as $key => $value)
				{
					if (preg_match("/ks_qn([0-9]+)_([a-z]+)$/", $key, $match))
					{
						$tmpquestionid = $match[1];
						$label = $match[2];
						if (!isset($nqinfos[$tmpquestionid]))
						{
							$nqinfos[$tmpquestionid] = array();
						}
						//Group questions infos by id
						$nqinfos[$tmpquestionid] = array_merge($nqinfos[$tmpquestionid], array($label => $value));
					}
				}

				//Browse posted questions and create questions objects
				$questions = array();
				foreach($qinfos as $qid => $infos)
				{
					$infos["id"] = $qid;
					$questions[$qid] = new KSQuestion($infos, $this->userFactory);
				}
				
				//Browse new questions and create questions objects
				$newquestions = array();
				foreach($nqinfos as $infos)
				{
					$infos["surveyid"] = $_POST["surveyid"];
					$newquestions[] = new KSQuestion($infos, $this->userFactory);
				}

				//Set questions in survey
				$mySF->saveSurveyDetails($mySurvey);
				
				//Save existing questions update
				$mySurvey->setQuestions($questions);
				$mySF->saveSurveyQuestions($mySurvey);

				//Save new questions
				$mySurvey->setQuestions($newquestions);
				$mySF->saveSurveyQuestions($mySurvey);

				//Save survey
				
				$_SESSION["survey_surveysaved"] = TRUE;
			}
		}
		else
		{
			//Add survey
		}
		
		
		$this->setRedirectArg('page', 'displayquestions');
		$this->setRedirectArg('surveyid', $_POST["surveyid"]);
	}
}

?>