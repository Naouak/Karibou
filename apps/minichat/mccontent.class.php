<?php 

/**
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2009 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class MCContent extends Model
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
			
		$minichatMessageList = new MinichatMessageList($this->db, $this->userFactory, $userichtext == 1, $inversepostorder == 1, $this->args["showscore"]);
		$this->assign("post", $minichatMessageList->getMessages($max, 1));

	}
}

?>
