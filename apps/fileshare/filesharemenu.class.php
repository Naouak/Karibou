<?php

class FileShareMenu extends Model
{
	function build()
	{
		if( isset($this->args['page']) )
			$this->assign("page", $this->args['page']);
		if( isset($this->args['myDir']) )
			$this->assign("myDir", $this->args['myDir']);
		if( isset($this->args['permission']) && ($this->args['permission'] > _READ_ONLY_) )
			$this->assign("uploadallowed", TRUE);
	}
}

?>