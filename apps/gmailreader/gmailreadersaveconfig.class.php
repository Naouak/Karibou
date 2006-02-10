<?php 
/**
 * @version $Id:  preferenceslarge.class.php,v 0.1 2005/06/26 10:52:56 dat Exp $
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/** 
 * 
 * @package applications
 **/
class gmailReaderSaveConfig  extends FormModel
{
	public function build()
	{
	/*
		if( isset($this->args['gmaillogin']) )
		{
			$this->assign('gmaillogin', $this->args['gmaillogin']);
		}
		if( isset($this->args['gmailpass']) )
		{
			$this->assign('gmailpass', $this->args['gmailpass']);
		}
		if( isset($this->args['gmailmax']) )
		{
			$this->assign('gmailmax', $this->args['gmailmax']);
		}
		$this->assign('feed', "https://$this->args['feed']);
		*/
//setPref
		$currentUser = $this->userFactory->getCurrentUser();
//		echo 'gmaillogin : '.$_POST['gmaillogin'];
		$prefName = 'gmaillogin';
		$currentUser->setPref($prefName, $this->args['gmaillogin']);
		$prefName = 'gmailpass';
		$currentUser->setPref($prefName, $this->args['gmailpass']);
		$prefName = 'gmailmax';
		$currentUser->setPref($prefName, $this->args['gmailmax']);
		$this->setRedirectArg('page', '');
	}
}

?>