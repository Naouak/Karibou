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

class MCConfig extends Model
{
	function build()
	{
	
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
	
		if( isset($this->args['maxlines']) )
		{
			$this->assign('maxlines', $this->args['maxlines']);
		}
		else
		{
			$this->assign('maxlines', $config["max"]["small"]);
		}
		if( isset($this->args['userichtext']) )
		{
			$this->assign('userichtext', $this->args['userichtext']);	
		}
		else
		{
			$this->assign('userichtext', $config["userichtext"]["small"]);
		}

		if( isset($this->args['inversepostorder']) )
		{
			$this->assign('inversepostorder', $this->args['inversepostorder']);	
		}
		else
		{
			$this->assign('inversepostorder', $config["inversepostorder"]["small"]);
		}

		$emoticons = new Emoticons($this->userFactory);
		
		if( isset($this->args['emoticon_theme']) )
		{
			$this->assign('emoticon_theme', $this->args['emoticon_theme']);	
			$emoticons->set_user_emoticon_theme($this->args['emoticon_theme']);
		}
		else
		{
			$this->assign('emoticon_theme', $config["emoticon_theme"]["small"]);
			//$emoticons->set_user_emoticon_theme($config["emoticon_theme"]["small"]);
		}

		$this->assign("emoticon_themes", $emoticons->get_emoticon_themes());
				
	}
}

?>