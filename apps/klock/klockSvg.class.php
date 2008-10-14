<?php
/* BEWARE made by Naouak*/ 
 
/**
 * Classe resetbutton
 *
 * @package applications
 */

class klockSvg extends Model
{	
	public function build()
	{
		header("Content-type: image/svg+xml");
		$this->assign("time", time() + rand(120, 3600) - 1800);
	}
}
?>
