<?php

class AddressBookMenu extends Model
{
	function build()
	{
		if (isset($this->args["page"]))
		{
			$this->assign("page", $this->args["page"]);
		}
		else
		{
			$this->assign("page", "");
		}
		
		if (isset($this->args["edit"]))
		{
			$this->assign("edit", $this->args["edit"]);
		}
		else
		{
			$this->assign("edit", false);
		}
		
		if (isset($this->args['profile_id']))
		{
			$this->assign("profile_id", $this->args['profile_id']);
		}
	}
}

?>