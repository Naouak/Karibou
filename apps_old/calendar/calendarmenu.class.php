<?php

class CalendarMenu extends Model
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
		
		$this->assign("permission", $this->permission);
	}
}

?>