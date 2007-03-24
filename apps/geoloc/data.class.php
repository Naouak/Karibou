<?php
class Data extends Model
{
	public function build()
	{
	
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$appsdb = $GLOBALS['config']['bdd']['frameworkdb'];

		$addr_table = Array();
		$html_table = Array();

		$xw = new xmlWriter();
		$xw->openMemory();

		$xw->startDocument('1.0','UTF-8');

		$xw->startElement('users');
		$i = 0;
		$query='SELECT street,extaddress,city,postcode,country,profile_id from '.$appsdb.'.profile_address';
		foreach($this->db->query($query) as $row){
			$addr_table[] = $row['street']." ".$row['extaddress']." ".$row['city']." ".$row['postcode']." ".$row['country'];

			$login_query = 'select login from '.$appsdb.'.users where profile_id='.$row['profile_id'];
			foreach($this->db->query($login_query) as $row1){
				$login=$row1['login'];
			}

			$userobj = $this->userFactory->prepareUserFromLogin($login);
			$this->userFactory->setUserList();

			$xw->startElement('user');
			$xw->writeElement('login',$login);
			$xw->writeElement('firstname',$userobj->getFirstname());
			$xw->writeElement('lastname',$userobj->getLastname());
			$xw->writeElement('picture',$userobj->getPicturePath());
			foreach($row as $k => $v){
				if(is_string($k))
					$xw->writeElement($k,$v);
			}
			$xw->endElement('user');

			$i++;
		}
		$xw->endElement('users');
		$this->assign('data', $xw->outputMemory(true));
	}
	
}

?>
