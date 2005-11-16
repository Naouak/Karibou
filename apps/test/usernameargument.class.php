<?php



class UserNameArgument extends Argument
{
	function getUrlArgument()
	{
		return "~".$this->value;
	}
	
	function getVar($arg)
	{
		if( preg_match('#~([a-zA-Z]+)#', $arg, $match) )
		{
			return $match[1];
		}
		return false;
	}
}


?>