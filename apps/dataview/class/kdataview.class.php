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
class KDataView
{
	protected $db;
	protected $userFactory;
	public $sources;

	function __construct(PDO $db, UserFactory $userFactory)
	{
		$this->db = $db;
		$this->userFactory = $userFactory;
	}
	
	function addSource ($tablename, Array $data)
	{
		$this->sources[$tablename] = new KDataViewSource($this->db, $this->userFactory, $tablename, $data);
	}
	
	function getSource ($tablename)
	{
		if (isset($this->sources[$tablename]))
		{
			return $this->sources[$tablename];
		}
		else
		{
			return FALSE;
		}
	}
}

?>