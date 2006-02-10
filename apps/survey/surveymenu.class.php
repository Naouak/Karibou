<?php

class KSMenu extends Model
{
	function build()
	{
		if( isset($this->args['page']) )
			$this->assign("page", $this->args['page']);

		if( isset($this->args['surveyid']) )
			$this->assign("surveyid", $this->args['surveyid']);
	}
}

?>