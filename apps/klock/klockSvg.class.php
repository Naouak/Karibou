<?php
/* Made in Rémy Sanchez <remy.sanchez@hyperthese.net> */ 
 
/**
 * Class klockSvg
 *
 * @package applications
 */

class klockSvg extends Model
{	
	public function build()
	{
		// This is svg !
		header("Content-type: image/svg+xml");
		
		// Let's get the imprecision from config
		$imprecision = ($this->args["imprecision"] > 300) ? (int) $this->args["imprecision"] : 300;
		
		// Values are assigned
		// Server (incorrect) time
		$this->assign("time", time() + rand(0, $imprecision * 2) - $imprecision);
		// Server's time zone offset (in seconds)
		$this->assign("tz", date("Z"));
	}
}
?>
