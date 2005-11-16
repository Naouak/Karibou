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

			$login = $miniapps->getNewApp('login');
			$minichat = $miniapps->getNewApp('minichat', array('maxlines'=>5));
			$containers->setApps( $c1, array($login, $minichat) ) ;
            
            $c2_apps = array();
            if( $this->currentUser->isLogged() )
            {
			    	$c2_apps[] = $miniapps->getNewApp('mail');
                $c2_apps[] = $miniapps->getNewApp('news');
            }
		    $containers->setApps( $c2, $c2_apps ) ;

			$c3_apps = array();
			$c3_apps[] = $miniapps->getNewApp('onlineusers');
			$c3_apps[] = $miniapps->getNewApp('prefs');
			$containers->setApps( $c3, $c3_apps) ;

			$currentUser->setPref('miniapps', $miniapps );
			$currentUser->setPref('containers', $containers );
		}
		
		if( isset($this->args['act']) && $this->args['act'] == 'edit' )
		{
			$miniapp_view = 'miniappedit';
			$this->assign("miniapps", $miniapps->getConfig() );
			$default = $containers->getDefaultApps();
			
			foreach($default as $app)
			{
				if( isset($miniapps[$app]) )
				{
					$a = $this->appList->getApp($this->appname);
					$args = $miniapps[$app];
					$args['id'] = $app;
					$a->addView(
						$miniapp_view ,
						"default_container",
						$args
					);
				}
			}
		}
		else
		{
			$miniapp_view = 'miniappview';
		}
		
		$cont = array();
		foreach( $containers as $key => $c )
		{
			$cont[$key] = $c['size'];
			foreach($c['apps'] as $app)
			{
				if( isset($miniapps[$app]) )
				{
					$a = $this->appList->getApp($this->appname);
					$args = $miniapps[$app];
					$args['id'] = $app;
					$a->addView(
						$miniapp_view ,
						$key,
						$args
					);
				}
			}
		}

		$this->assign("messages", $this->messageManager->getMessages("default"));
		if (isset($this->currentUser))
		{
			$this->assign("username", $this->currentUser->getLogin());
		}

		$this->assign("containers", $cont);
	}
}

?>
