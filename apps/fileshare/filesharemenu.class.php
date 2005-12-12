<?php

class FileShareMenu extends Model
{
	function build()
	{
		if( isset($this->args['page']) )
			$this->assign("page", $this->args['page']);
		
		if( isset($this->args['myDirPathBase64']) )
			$this->assign("myDirPathBase64", $this->args['myDirPathBase64']);

		if( isset($this->args['permission']) && ($this->args['permission'] > _READ_ONLY_) )
			$this->assign("uploadallowed", TRUE);
	}
}

?>