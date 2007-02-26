<?php 

/**
 * @version $Id $
 * @copyright 2007 Antoine Reversat
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class Squelette extends Model
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();

		/*
		Traitement et export des variables vers le template
		*/
	
}

?>
