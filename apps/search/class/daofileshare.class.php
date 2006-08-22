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
class DAOFileShare extends DAOElement
{
	protected $db;
	protected $userFactory;

	function __construct(PDO $db, UserFactory $userFactory)
	{
		
	}
	
	public function findFromKeyWords($keywords) {
    }
}

?>