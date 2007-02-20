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

class Geoloc extends Model
{
	public function build()
	{
	
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
	
		if ($this->currentUser->isLogged())
		{
			$appsdb = $GLOBALS['config']['bdd']['frameworkdb'];
			$gkey = $GLOBALS['config']['geoloc']['gkey'];

			$addr_table = Array();
			$html_table = Array();

			$i = 0;
			$query='SELECT street,extaddress,city,postcode,country,profile_id from '.$appsdb.'.profile_address';
			$data = "users = new Array();\n";
			foreach($this->db->query($query) as $row){
				$addr_table[] = $row['street']." ".$row['extaddress']." ".$row['city']." ".$row['postcode']." ".$row['country'];

				$login_query = 'select login from '.$appsdb.'.users where profile_id='.$row['profile_id'];
				foreach($this->db->query($login_query) as $row1){
					$login=$row1['login'];
				}

				$userobj = $this->userFactory->prepareUserFromLogin($login);
				$this->userFactory->setUserList();
				
				$data .= "users[$i] = new Array();\n";
				$data .= "users[$i]['login']=\"".$login."\";\n";
				$data .= "users[$i]['firstname']=\"".$userobj->getFirstname()."\";\n";
				$data .= "users[$i]['lastname']=\"".$userobj->getLastname()."\";\n";
				$data .= "users[$i]['picture']=\"".$userobj->getPicturePath()."\";\n";
				foreach($row as $k => $v){
					if(is_string($k))
						$data .= "users[$i]['$k']=\"$v\";\n";
				}
				$data .= "\n";

				$i++;
			}

			if(array_key_exists('login', $this->args)){
				$this->assign('search', "var user = \"".$this->args['login']."\";");
			}
			$this->assign('data', $data);
			$this->assign('gkey', $gkey);

		}
	
	}
}

?>
