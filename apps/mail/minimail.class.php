<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class MiniMail extends Model
{
	function build()
	{
		$user = $this->userFactory->getCurrentUser();
		$config = $this->getConfig();

		$keychain = KeyChainFactory::getKeyChain($this->currentUser);
		if( !$keychain->unlock() )
		{
			$this->assign("message", 'cannot unlock the KeyChain');
			return;
		}
		$pass = $keychain->get('session_password');
		$username = $user->getLogin().$GLOBALS['config']['login']['post_username'];
		$server = $GLOBALS['config']['login']['server'];

		$mailbox = new Mailbox($server, $username, $pass );
		$mailbox->setPerPage(10);
		if( $mailbox->connected() )
		{
			$this->assign('quota', $mailbox->getQuota() );
			$this->assign('messagecount', $mailbox->getMessageCount());
			$h = $mailbox->getHeaders(1);
			$h = array_reverse($h);
			$this->assign('messageheaders', $h);
		}
	}
}

?>
