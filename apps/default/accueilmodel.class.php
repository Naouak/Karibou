<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
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
		$thisapp = $this->appList->getApp($this->appname);
		$thisapp->addView("menu", "header_menu");

		$currentUser = $this->userFactory->getCurrentUser();

		if( ($containers = $currentUser->getPref('containers'))
			&& ($miniapps = $currentUser->getPref('miniapps')) )
		{
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
		}
		
		$miniapp_view = 'miniappedit';
		$this->assign("miniapps", $miniapps->getConfig() );
		$default = $containers->getDefaultApps();
		
		foreach($default as $app)
		{
			if( isset($miniapps[$app]) )
			{
				//$a = $this->appList->getApp($this->appname); //thisapp
				$args = $miniapps[$app];
				$args['id'] = $app;
				$thisapp->addView(
					$miniapp_view ,
					"default_container",
					$args
				);
			}
		}

		$cont = array();
		foreach( $containers as $key => $c )
		{
			$cont[$key] = $c['size'];
			foreach($c['apps'] as $app)
			{
				if( isset($miniapps[$app]) )
				{
					//$a = $this->appList->getApp($this->appname);
					$args = $miniapps[$app];
					$args['id'] = $app;
					$thisapp->addView(
						$miniapp_view ,
						$key,
						$args
					);
				}
			}
		}
		$this->assign("containers", $cont);

		$this->assign("messages", $this->messageManager->getMessages("default"));
		if (isset($this->currentUser))
		{
			$this->assign("username", $this->currentUser->getLogin());
		}

	}
}

?>
