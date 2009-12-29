<?php

/**
 * @copyright 2009 Grégoire Leroy <lupuscramus@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/
class BugsMod extends Model
{
	public function build()
	{
		$sql = $this->db->prepare("SELECT * from bugs_module");
		try {
			$sql->execute();
			$modules = $sql->fetchAll();

			$this->assign("modules",$modules);
			
		} catch (PDOException $e) {
			$e->getMessage();
		}
	}
}
