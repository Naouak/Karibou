<?php

class CVId extends Argument
{
	//Permet d'ecrire l'argument dans l'URL
	function getUrlArgument()
	{
		return "cv=".$this->value;
	}
	
	//A l'URL en argument, retourne la valeur si elle la trouve sinon retourne FALSE
	function getVar($arg)
	{
		if( preg_match('#cv=([0-9]+)#', $arg, $match) )
		{
			return $match[1];
		}
		return false;
	}
}

?>