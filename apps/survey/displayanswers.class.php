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
class KSDisplayAnswers extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$app->addView("menu", "header_menu", array("page"=>"displayanswers"));
		
		$mySF = new KSurveyFactory ($this->db, $this->userFactory);
		if ($this->permission > _SELF_WRITE_)
		{
			if (isset($this->args["surveyid"]) && $this->args["surveyid"] != "")
			{

				$mySurvey = $mySF->getSurveyById($this->args["surveyid"]);
				
				$mySF->setQuestionsToSurvey($mySurvey);
				$mySF->setAllAnswers($mySurvey); /* ! */
				$this->assign("answers", $mySurvey->getAllAnswers());

				if (isset($_SESSION["survey_answerssaved"]) && $_SESSION["survey_answerssaved"] === TRUE)
				{
					$this->assign("answerssaved", TRUE);
				}

				$this->assign("mySurvey", $mySurvey);
			}
		}
		
	}
}

?>
