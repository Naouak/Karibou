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
		$mySF = new KSurveyFactory ($this->db, $this->userFactory);
		if ($this->permission >= _ADMIN_)
		{
			if (isset($this->args["surveyid"]) && $this->args["surveyid"] != "")
			{
				$this->assign("admin", true);
				
				//Menu
				$app = $this->appList->getApp($this->appname);
				$config = $app->getConfig();
				$app->addView("menu", "header_menu", array("page"=>"displayanswers", "surveyid"=>$this->args["surveyid"]));


				$mySurvey = $mySF->getSurveyById($this->args["surveyid"]);
				
				$mySF->setQuestionsToSurvey($mySurvey);
				$mySF->setAllAnswers($mySurvey);
				$this->assign("verionsanswers", $mySurvey->getAllAnswers());

//var_dump($mySurvey->getAllAnswers());

				$this->assign("mySurvey", $mySurvey);
			}
		}
		
	}
}

?>
