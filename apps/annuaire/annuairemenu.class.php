<?php

class AnnuaireMenu extends Model
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
		
		if ($this->permission >= _SELF_WRITE_)
		{
			$this->assign("username", $this->currentUser->getLogin());
		}
	}
}

?>