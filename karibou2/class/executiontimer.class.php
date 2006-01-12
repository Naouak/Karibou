<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

/**
 * Count execution time
 *
 * @package framework
 */ 
class ExecutionTimer
{
	private static $ref;

	public $timeArray;
	protected $currentTimeArray;
	
	public $display = true;
	
	private function __construct()
	{
		$this->timeArray = array();
		$this->startTimeArray = array();
	}

	public static function getRef()
	{
		if(self::$ref == null)
		{
			self::$ref = new ExecutionTimer();
		}
		return self::$ref;
	}
	
	/**
	 * retourne le temps en microsecondes
	 */
	function getmicrotime()
	{
		list($usec, $sec) = explode(" ",microtime()); 
		return ((float)$usec + (float)$sec); 
	}
	
	function start($mark)
	{
		if( $this->display == false ) return ;
		if(!isset($this->startTimeArray[$mark]))
			$this->startTimeArray[$mark] = $this->getmicrotime();
	}

	function stop($mark)
	{
		if( $this->display == false ) return ;
		if(isset($this->startTimeArray[$mark]))
		{
			if(isset($this->timeArray[$mark]))
			{
				$this->timeArray[$mark] += 
					$this->getmicrotime() - $this->startTimeArray[$mark];
				unset($this->startTimeArray[$mark]);
			}
			else
			{
				$this->timeArray[$mark] = 
					$this->getmicrotime() - $this->startTimeArray[$mark];
				unset($this->startTimeArray[$mark]);
			}
		}
	}
	
	function getHTML()
	{
		$html = "";
		if( $this->display == false ) return $html;
		reset($this->timeArray);
		while(list($key, $value) = each($this->timeArray))
		{
			$html .= $key." : ".round($value, 3)." sec<br />\n";
		}
		return $html;
	}
	
	function getTableHTML()
	{
		$html = "";
		if( $this->display == false ) return $html;
		
		$html .= "<div style=\"text-align: left; border: 2px solid #000000; position: absolute; top:0px; right:0px; background: #ffffff; height: 400px; overflow: scroll; padding-right: 20px;\"><pre>";

		reset($this->timeArray);
		arsort($this->timeArray);

		$totaltime = $this->timeArray["Full Load"];
		$toptimers = array();
		
		foreach ($this->timeArray as $action => $time)
		{
			$toptimers[$action] = array();
			$toptimers[$action]["percent"] = number_format($time/$totaltime * 100,2);
			$toptimers[$action]["time"] = number_format($time*1000,2);
		}
		
		$html .= "<table border=1><tr><td style=\"font-weight: bold;\">Action</td><td style=\"font-weight: bold;\">Percent</td><td style=\"font-weight: bold;\">Time (msec)</td></tr>";
		foreach($toptimers as $action => $info)
		{
			$html .= "<tr>";
			$html .= "<td>".$action."</td>";
			$html .= "<td>".$info["percent"]."%</td>";
			$html .= "<td>".$info["time"]."</td>";
			$html .= "</tr>";
		}
		$html .= "</table></div>";
		
		return $html;
	}
}

?>
