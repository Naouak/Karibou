<?php 

/**
 * @version $Id: minichatgrand.class.php,v 1.2 2004/12/03 00:52:56 jon Exp $
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class MCDefault extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$this->assign("config", $config);
		if( isset($this->args['maxlines']) && $this->args['maxlines'] != "" )
		{
			$max = $this->args['maxlines'] ;
		}
		else
		{
			$max = $config["max"]["small"] ;
		}

		if (isset($this->args['showbutton']) && $this->args['showbutton'] != "") {
			$this->assign("showbutton", $this->args['showbutton']);
		} else {
			$this->assign("showbutton", false);
		}
		if( isset($this->args['userichtext']) && $this->args['userichtext'] != "" )
		{
			$userichtext = $this->args['userichtext'];
		}
		else
		{
			$userichtext = $config["userichtext"]["small"];
		}
		if( isset($this->args['inversepostorder']) && $this->args['inversepostorder'] != "" )
		{
			$inversepostorder = $this->args['inversepostorder'];
		}
		else
		{
			$inversepostorder = $config["inversepostorder"]["small"];
		}
		
		if( isset($this->args['emoticon_theme']) && $this->args['emoticon_theme'] != "" )
		{
			$emoticon_theme = $this->args['emoticon_theme'];
			$emoticons = new Emoticons($this->userFactory);
			$emoticons->set_user_emoticon_theme($emoticon_theme);
		}
		else
		{
			$emoticon_theme = $config["emoticon_theme"]["small"];
		}

		if (isset($this->args["showscore"]) && $this->args["showscore"] != "") {
			$this->args["showscore"] = (bool)$this->args["showscore"];
		} else {
			$this->args["showscore"] = (bool) $config["showscore"]["small"];
		}

		$this->assign("emoticon_theme", $emoticon_theme);
		$this->assign("maxlines", $max);
		$this->assign("userichtext", $userichtext);
		$this->assign("inversepostorder", $inversepostorder);
		
		if(isset($this->args['pagenum']) && $this->args['pagenum'] != "")
			$page = $this->args['pagenum'];
		else
			$page = 1;
			
		$this->assign("pagenum", $page);
		$minichatMessageList = new MinichatMessageList($this->db, $this->userFactory, $userichtext == 1, $inversepostorder == 1, $this->args["showscore"]);
		$dateRange = $minichatMessageList->dateRange();
		$this->assign("minDate", $dateRange[0]);
		$this->assign("maxDate", $dateRange[1]);
		$page_count = ceil($minichatMessageList->count() / $max);
		
		if ($page_count > 1)
		{
			$pages = range(1, $page_count);
			$this->assign('pages', $pages);
			$this->assign('page', $page);
		}
		if ((isset($this->args["day"])) && ($this->args["day"] != "")) {
			$this->assign("post", $minichatMessageList->getMessagesFromDate($this->args["day"]));
		} else {
			if (isset($max) && isset($page))
				$this->assign("post", $minichatMessageList->getMessages($max, $page));
			else
				$this->assign("post", $minichatMessageList->getMessagesFromDate(time()));
		}

		$this->assign('permission', $this->permission);
		$this->assign('time', time());
		$this->assign('maxHeight', intval($max) * 20);
	}
}

?>
