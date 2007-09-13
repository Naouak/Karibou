<?php
/**
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
class SearchDefault extends Model
{
	public function build()
	{
		if (isset($_POST['keywords']))
		{
			if (isset($_POST['app']) && $_POST['app'] !== '')
			{
				$app = $_POST['app'];
				$this->assign('app', $_POST['app']);
			}
			else
			{
				$app = 'everywhere';
			}
			
			$keywords = trim($_POST['keywords']);
			$this->assign('keywords', $keywords);
			
			$errors = array();
			if (strlen($keywords) <= 3)
			{
				$errors['SEARCH_NOTENOUGHCHARS'] = _('SEARCH_NOTENOUGHCHARS');
			}
			
			if (count($errors)>0)
			{
				$this->assign('errors', $errors);
			}
			else
			{
				DAOFactory::init($this->db, $this->userFactory);
				
				if ( (isset($app) && $app == 'news') || $app == 'everywhere' )
				{
					$daoNews = DAOFactory::create('News');
					$articles = $daoNews->find($keywords);
					$this->assign("articles", $articles);
				}

				if ( (isset($app) && $app == 'fileshare') || $app == 'everywhere' )
				{
					$daoFiles = DAOFactory::create('Files');
					$files = $daoFiles->find($keywords);
					$this->assign("files", $files);
				}
				
				if ( (isset($app) && $app == 'calendar') || $app == 'everywhere' )
				{
					$daoEvents = DAOFactory::create('Events');
					$events = $daoEvents->find($keywords);
					$this->assign("events", $events);
				}

				if ( (isset($app) && $app == 'annuaire') || $app == 'everywhere' )
				{
					$daoUsers = DAOFactory::create('Users');
					$usersfound = $daoUsers->find($keywords);
					$this->assign("usersfound", $usersfound);
				}
			}
		}
	}
}

?>