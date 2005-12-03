<?php

class WikiMenu extends Model
{
	function build()
	{
		if (isset($this->args["page"]))
		{
			$this->assign("page", $this->args["page"]);
			$this->assign("docu", $this->args["docu"]);
			$this->assign("mode", $this->args["mode"]);
		}
		else
		{
			$this->assign("page", "");
		}
		$this->assign('permission', $this->permission);
	}
}

?>