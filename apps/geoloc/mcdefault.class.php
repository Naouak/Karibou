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
		
		if ($this->currentUser->isLogged())
		{
			$appsdb = $GLOBALS['config']['bdd']['frameworkdb'];
			$gkey = $GLOBALS['config']['geoloc']['gkey'];
			$i=0;
			$addr_table = Array();
			$html_table = Array();
			$string ="";
			$query='SELECT street,extaddress,city,postcode,country,profile_id from '.$appsdb.'.profile_address';
			foreach($this->db->query($query) as $row){
				$addr_table[] = $row['street']." ".$row['extaddress']." ".$row['city']." ".$row['postcode']." ".$row['country'];
				$login_query = 'select login from '.$appsdb.'.users where profile_id='.$row['profile_id'];
				foreach($this->db->query($login_query) as $row1){
					$login=$row1['login'];
				}
				$userobj = $this->userFactory->prepareUserFromLogin($login);
				$this->userFactory->setUserList();
				$html = "<img src=\\\"".$userobj->getPicturePath()."\\\"/>";
				$html .= "<div><h1>".$userobj->getFirstname()." ".$userobj->getLastname()."</h1>";
				$html .= "<div>".$row['street']."<br/>".$row['city']."<br/>".$row['postcode']."<br/>".$row['country']."</div>";
				$html_table[] = $html;
			}
			$jscript_addrs = "var adresses = new Array(\"";
			$jscript_addrs .= implode("\",\"",$addr_table);
			$jscript_addrs .= "\");";
			$jscript_html = "var html = new Array(\"";
			$jscript_html .= implode("\",\"",$html_table);
			$jscript_html .= "\");";
			$this->assign('jscript_addrs', $jscript_addrs);
			$this->assign('jscript_html', $jscript_html);
			$this->assign('gkey', $gkey);

		}
	
	}
}

?>
