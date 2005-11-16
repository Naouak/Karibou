<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/
 

 
$GLOBALS['myTranslation'] = new NetCVTempTranslation();

class NetCVSkinDisplay extends Model
{
	public function build()
	{
	
		if (isset($this->args["filename"]) && is_file(dirname(__FILE__)."/skins/".$this->args["filename"])) {

			$this->assign("skinName",$this->args["filename"]);
		} else {
			$this->assign("skinName","pro1.css");
		}
	}
}
?>
