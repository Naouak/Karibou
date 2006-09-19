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
class DAOFiles extends DAOElement
{
	protected $db;
	protected $userFactory;
	
	public function findFromKeyWords($keywords) {
		$myKDBFSElementFactory = new KDBFSElementFactory($this->db, $this->userFactory, _READ_ONLY_);
		
		return $myKDBFSElementFactory->getFilesFromSearch($keywords);
    }
}

?>