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
		$keywords = $_POST['keywords'];
	
		DAOFactory::init($this->db, $this->userFactory);
		
		$daoNews = DAOFactory::create('News');
		$articles = $daoNews->find($keywords);
		
		$this->assign("articles", $articles);
	}
}

?>