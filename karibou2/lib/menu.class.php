<?php
/**
 * Classe Menu
 *
 * @version 0.1
 * @copyright 2003 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2003 Pierre Laden <pladen@elv.enic.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package lib
 **/

class menu
{
	function genereHtml()
	{
//NEW MENU
	  $html = "
<!--
	 <div id=\"menu1\">
	  <h2>Animaux</h2>
		<ul>
  	  <li>
  	  	<a href=\"http://www.google.com/\" title=\"Sous-menu 1\">Google</a>
  	  </li>
  	  <li>
  	  	<a href=\"#\" title=\"Sous-menu 2\">Current Page</a>
  	  </li>
		</ul>
	 </div>
-->
	 ";

		return $html;
	}
}

?>
