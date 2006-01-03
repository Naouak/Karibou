<?php

class NJMenu extends Model
{
	function build()
	{
		if( isset($this->args['page']) )
			$this->assign("page", $this->args['page']);

		if( isset($this->args['jobid']) )
			$this->assign("jobid", $this->args['jobid']);
	}
}

?>