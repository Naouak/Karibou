<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/
 
 
class NetCVSingleCVDelete extends Model
{
	public function build()
	{
		if (isset($this->args["cvid"],$this->args["gid"]) && ($this->args["cvid"] != "") && ($this->args["gid"] != "") ) {
			$myNetCVUser = new NetCVUser($this->db, $this->currentUser,TRUE);
			//Mise a jour d'une version de CV existante
			$myNetCVUser->deleteSingleCV($this->args["gid"],$this->args["cvid"]);

			$this->formMessage->add (FormMessage::SUCCESS, gettext("DELETED_LANG_CV"));
			$this->formMessage->setSession();
		}
	}
}
?>