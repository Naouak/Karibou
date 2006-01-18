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
class KFile extends KDataViewDB
{
	protected $db;
	protected $userFactory;

	function __construct(PDO $db, UserFactory $userFactory)
	{
		$this->db = $db;
		$this->userFactory = $userFactory;
	}
}

?>