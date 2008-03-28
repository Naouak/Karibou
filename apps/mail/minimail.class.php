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
        if (isset($_SESSION['user_mail_login'])) {
            $username = $_SESSION['user_mail_login'];
        } else {
		    $username = $user->getLogin();
        }
		$server = $GLOBALS['config']['mail']['server'];

		$mailbox = new Mailbox($server, $username, $pass );
        if (!$mailbox->connected()) {
            $username .= $GLOBALS['config']['mail']['post_username'];
            $mailbox = new Mailbox($server, $username, $pass );
        }
		$mailbox->setPerPage(10);
		if( $mailbox->connected() )
		{
            $_SESSION['user_mail_login'] = $username;
			$this->assign('quota', $mailbox->getQuota() );
			$this->assign('messagecount', $mailbox->getMessageCount());
			$h = $mailbox->getHeaders(1);
			$h = array_reverse($h);
			$this->assign('messageheaders', $h);
		}
	}
}

?>
