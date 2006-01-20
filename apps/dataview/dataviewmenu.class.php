<?php

class DVMenu extends Model
{
	function build()
	{
		if (isset($this->args['page']) )
			$this->assign("page", $this->args['page']);
			
		if (isset($this->args['source']) && $this->args['source'] != "")
			$this->assign("source", $this->args['source']);
	}
}

?>