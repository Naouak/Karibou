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

		if( isset($this->args["folderExistsInDB"]) && ($this->args["folderExistsInDB"] == TRUE) )
			$this->assign("folderExistsInDB", TRUE);			
		
		if( isset($this->args["canUpdate"]) && ($this->args["canUpdate"] == TRUE) )
			$this->assign("canUpdate", TRUE);

		if( isset($this->args["canWrite"]) && ($this->args["canWrite"] == TRUE) )
			$this->assign("canWrite", TRUE);

		if( isset($this->args["elementid"]) )
			$this->assign("elementid", $this->args["elementid"]);
	}
}

?>