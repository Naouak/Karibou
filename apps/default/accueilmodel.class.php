<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2008 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/


/**
 * fetch infos for homepage
 * 
 * @package applications
 */

class AccueilModel extends Model
{
	function build()
	{
		$keychainError = false;
		$currentUser = $this->userFactory->getCurrentUser();
		if ($currentUser->isLogged())
		{
			$keychain = KeyChainFactory::getKeyChain($this->currentUser);
			if (!$keychain->unLock())
			{
				if (isset($_SESSION["temp_session_password"]) && (strlen($_SESSION["temp_session_password"]) > 0))
				{
					// Ok, warn the user that his keychain is dead right now, we needs his new password...
					// It means we won't show him the applications and so on, we will only display input field or something like that
					// So that he can put in his/her old password
					$this->assign("keychainError", true);
					$keychainError = true;
					if ($_SESSION["bad_keychain_attempt"])
						$this->assign("secondAttempt", true);
				}
			}
		}
		if (isset($this->currentUser))
		{
			$this->assign("username", $this->currentUser->getLogin());
		}
		if (!$keychainError) {
			$thisapp = $this->appList->getApp($this->appname);
			
			if( ($containers = $currentUser->getPref('containers'))
				&& ($miniapps = $currentUser->getPref('miniapps')) )
			{
				// the configuration of the applications (including config view etc) are stored in the user profile : the configuration has to be reloaded into the user's profile
				$app_config = $miniapps->updateConfig();
				$currentUser->setPref('miniapps', $miniapps );
				$currentUser->savePrefs($this->db);
			}
			else
			{
				$containers = new HomeContainers();
				$c1 = $containers->add('s');
				$c2 = $containers->add('m');
				$c3 = $containers->add('s');

				$miniapps = new HomeMiniApps();
				
				$c1_apps = array();
				$c1_apps[] = $miniapps->getNewApp('login');
				$c1_apps[] = $miniapps->getNewApp('daytof');
				if( $this->currentUser->isLogged() )
				{
					$c1_apps[] = $miniapps->getNewApp('ilsontdit');
					$c1_apps[] = $miniapps->getNewApp('myschoolnotes');
				}
				$containers->setApps( $c1, $c1_apps) ;

				$c2_apps = array();
				if( $this->currentUser->isLogged() )
				{
					$c2_apps[] = $miniapps->getNewApp('mail');
					$c2_apps[] = $miniapps->getNewApp('pool');
				}
				else $c2_apps[] = $miniapps->getNewApp('video');
			

				$c2_apps[] = $miniapps->getNewApp('news');
		
				
				$containers->setApps( $c2, $c2_apps ) ;

				$c3_apps = array();
				if( $this->currentUser->isLogged() )
				{
					$c3_apps[] = $miniapps->getNewApp('minichat', array('maxlines'=>5));
				}
				$c3_apps[] = $miniapps->getNewApp('onlineusers');
				$c3_apps[] = $miniapps->getNewApp('bday');
				$c3_apps[] = $miniapps->getNewApp('dday');
				$c3_apps[] = $miniapps->getNewApp('citation');
				$containers->setApps( $c3, $c3_apps) ;


				$currentUser->setPref('miniapps', $miniapps );
				$currentUser->setPref('containers', $containers );
				$currentUser->savePrefs($this->db);

				$app_config = $miniapps->getConfig();
			}

			usort($app_config, array($this, "compareApp"));
			
			$this->assign("miniapps", $app_config );
			$default = $containers->getDefaultApps();
			foreach($default as $app)
			{
				if( isset($miniapps[$app]) )
				{
					//$a = $this->appList->getApp($this->appname); //thisapp
					$args = $miniapps[$app];
					$args['id'] = $app;
					$thisapp->addView(
						'miniappedit',
						"default_container",
						$args
					);
				}
			}

			$cont = array();
			foreach( $containers as $key => $c )
			{
	// echo "Loop on $key => $c\n";
				$cont[$key] = $c['size'];
				foreach($c['apps'] as $app)
				{
	// echo "Loop on $app\n";
					if( isset($miniapps[$app]) )
					{
						//$a = $this->appList->getApp($this->appname);
						$args = $miniapps[$app];
						$args['id'] = $app;
						/*print("miniappedit\t$key\t");
						print_r($args);
						print("\n");*/
						$thisapp->addView(
							'miniappedit',
							$key,
							$args
						);
					}
				}
			}
			$this->assign("containers", $cont);
	//print_r($containers);
			$this->assign("messages", $this->messageManager->getMessages("default"));
		}
	}

	static function compareApp($a1, $a2) {
		return strcasecmp(_($a1["id"]), _($a2["id"]));
	}
}

?>
