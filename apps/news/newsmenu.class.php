<?php

class NewsMenu extends Model
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
		
		if (isset($this->args["viewcomments"]))
		{
			$this->assign("article_id", $this->args["article_id"]);
			if (isset($this->args["article_id"]) && $this->args["viewcomments"])
			{
				$this->assign("viewcomments", $this->args["viewcomments"]);
			}
			else
			{
			}
		}
	}
}

?>