<?php

class TestView extends Model
{
	public function build()
	{
		$grps = $this->currentUser->getGroups($this->db);
		$grps_admin = $this->currentUser->getAllAdminGroups($this->db);
		Debug::display($grps);
		Debug::kill($grps_admin);
	}
}

?>