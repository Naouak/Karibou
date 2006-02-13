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
class KSSaveAnswers extends FormModel
{
	public function build()
	{
				$this->setRedirectArg('app', 'survey');
				
				if (isset($_POST["surveyid"]) && $_POST["surveyid"] != "")
				{
					$this->setRedirectArg('page', 'displayquestions');
					$this->setRedirectArg('surveyid', $_POST["surveyid"]);
					
					$mySF = new KSurveyFactory ($this->db, $this->userFactory);
					
					$mySurvey = $mySF->getSurveyById($_POST["surveyid"]);
					$mySF->setQuestionsToSurvey($mySurvey);
					//$mySF->setUserAnswersToQuestions($mySurvey);
					foreach($_POST as $key => $value)
					{
						if (preg_match("/ks_q([0-9]+)/", $key, $match))
						{
							$mySurvey->setAnswerById($match[1], $value);
						}
					}
					
					$mySF->saveAnswers($mySurvey);
					
					$_SESSION["survey_answerssaved"] = TRUE;
				}
	}
}

?>
