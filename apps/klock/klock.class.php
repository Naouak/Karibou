<?php
/* Made in Rémy Sanchez <remy.sanchez@hyperthese.net> */ 
 
/**
 * Class klock
 *
 * @package applications
 */

class klock extends Model
{	
	public function build()
	{
		// Valid modes
		$modes = array("analog", "textual", "binary", "digital");
		
		// Getting current mode
		$current_mode = (in_array($this->args["mode"], $modes)) ? $this->args["mode"] : "analog";
		// Getting current imprecision
		$current_imprecision = ($this->args["imprecision"] > 5) ? $this->args["imprecision"] : 5;

		// Values are assigned
		// Server (incorrect) time
		$this->assign("time", time());
		// Server's time zone offset (in seconds)
		$this->assign("tz", date("Z"));
		// Mode
		$this->assign("mode", $current_mode);
		// Imprecsion (in seconds)
		$this->assign("imprecision", $current_imprecision * 60);
		// Self id
		$this->assign("id", $this->args["miniappid"]);
	}
}
?>
