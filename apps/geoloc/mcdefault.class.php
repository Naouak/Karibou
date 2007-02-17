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
			$string ="";
			$query='SELECT street,extaddress,city,postcode,country from '.$appsdb.'.profile_address';
			foreach($this->db->query($query) as $row){
				$addr_table[] = $row['street']." ".$row['extaddress']." ".$row['city']." ".$row['postcode']." ".$row['country'];
			}
			$jscript_table = "var adresses = new Array(\"";
			$jscript_table .= implode("\",\"",$addr_table);
			$jscript_table .= "\");";
			$this->assign('jscript_table', $jscript_table);
			$this->assign('gkey', $gkey);

		}
	
	}
}

?>
