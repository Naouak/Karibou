<?php
/* Made in Rémy Sanchez <remy.sanchez@hyperthese.net> */ 
 
/**
 * Class klockConfig
 *
 * @package applications
 */

class klockConfig extends Model
{	
	public function build()
	{
		// Valid modes
		$modes = array("analog", "textual", "binary", "digital");
		
		// Getting current mode
		$current_mode = (in_array($this->args["mode"], $modes)) ? $this->args["mode"] : "binary";
		// Getting current imprecision
		$current_imprecision = ($this->args["imprecision"] > 5) ? $this->args["imprecision"] : 5;
		
		// Time to assign
		$this->assign("mode", $current_mode);
		$this->assign("imprecision", $current_imprecision);
	}
}
?>
