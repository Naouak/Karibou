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
class KSDisplayUserAnswers extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$app->addView("menu", "header_menu", array("page"=>"displayanswers"));
		$mySF = new KSurveyFactory ($this->db, $this->userFactory);
		if ($this->permission >= _ADMIN_)
		{
			if (isset($this->args["surveyid"],$this->args["userid"]) && $this->args["surveyid"] != "" && $this->args["userid"] != "")
			{

				$mySurvey = $mySF->getSurveyById($this->args["surveyid"]);
				
				$mySF->setQuestionsToSurvey($mySurvey);
				$mySF->setUserAnswersToQuestions($mySurvey, $this->args["userid"]);
				$answers = $mySurvey->getAllAnswers();
				
				$this->assign("answers", $answers);

				$this->assign("currentUser", $this->userFactory->getCurrentUser());

				$this->assign("mySurvey", $mySurvey);
			}
		}
		
	}
}

?>
