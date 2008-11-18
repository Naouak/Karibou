<?php 
/**
 * @copyright 2008 Gilles Dehaudt
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

class AnnonceConfig extends Model
{
    public function build()
    {
	$app = $this->appList->getApp($this->appname);
	$config = $app->getConfig();
	$this->assign("config", $config);
	if ( isset($this->args['maxannonce']) && $this->args['maxannonce'] !="")
	{
		$this->assign("maxannounce",$this->args['maxannonce']);
	}
	else
	{
		$this->assign("maxannounce",$config['maxannonce']['default']);
	}
    }
}

?>