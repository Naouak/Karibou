<?php
/**
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

/**
 * Debug Class, currently output to screen.
 *
 * @todo can write to files, use hookmanager...
 * @package framework
 **/
class Debug
{
	public static $display = KARIBOU_DEBUG;
	private static $output = KARIBOU_DEBUG_TYPE;
	private static $method = KARIBOU_DEBUG_METHOD; 

	private static $logfile = KARIBOU_DEBUG_FILENAME ;

	private static $debugMessages = array();

	
	function __construct()
	{
		if( isset($GLOBALS['config']['debug_display']) )
			self::$display = $GLOBALS['config']['debug_display'];
	}
	
	function kill($txt)
	{
		if( self::$display )
		{
			self::display($txt);
			self::flushMessages(true);
			die();
		}
	}
	
	function display($txt)
	{
		if( self::$display )
		{
			if(is_array($txt) or is_object($txt))
			{
				switch(self::$output)
				{
					case 'text':
						$txt = print_r($txt, TRUE)."\n";
						break;
					case 'html':
						$txt = '<pre>' . print_r($txt, TRUE) . '</pre><br />';
						break;
				}
			}
			self::$debugMessages[] = $txt."\n";
		}
		
	}
	
	function flushMessages($backtrace = false)
	{
		if( self::$display )
		{
			switch(self::$method)
			{
				case 'stdout':
					foreach(self::$debugMessages as $message)
					{
						echo $message;
					}
					if( $backtrace ) echo self::backtrace();
					self::$debugMessages = array();
					break;
				case 'file':
					$fp = fopen(self::$logfile, "a");
					fputs($fp, "#####################################################\n");
					fputs($fp, date("Y-m-d H:i:s -- ").$_SERVER["REQUEST_URI"]."\n");
					fputs($fp, "#####################################################\n");
					foreach(self::$debugMessages as $message)
					{
						fputs($fp, $message);
					}
					if( $backtrace ) fputs($fp, self::backtrace());
					self::$debugMessages = array();
					fclose( $fp );
					break;
			}
		}
	}

	function backtrace()
	{
		switch(self::$output)
		{
			case 'text':
				return self::backtraceText();
				break;
			case 'html':
				return self::backtraceHTML();
				break;
		}		
	}
	function backtraceText()
	{
		$output = " -== Backtrace ==-\n";
		$backtrace = debug_backtrace();
		$backtrace = array_reverse($backtrace);
		array_pop($backtrace);
		$backtrace = array_reverse($backtrace);
		foreach ($backtrace as $bt)
		{
			$args = '';
			foreach ($bt['args'] as $a)
			{
				if (!empty($args))
				{
					$args .= ', ';
				}
				switch (gettype($a))
				{
					case 'integer':
					case 'double':
						$args .= $a;
						break;
					case 'string':
						$a = substr($a, 0, 64).((strlen($a) > 64) ? '...' : '');
						$args .= "\"$a\"";
						break;
					case 'array':
						$args .= 'Array('.count($a).')';
						break;
					case 'object':
						$args .= 'Object('.get_class($a).')';
						break;
					case 'resource':
						$args .= 'Resource('.strstr($a, '#').')';
						break;
					case 'boolean':
						$args .= $a ? 'True' : 'False';
						break;
					case 'NULL':
						$args .= 'Null';
						break;
					default:
						$args .= 'Unknown';
				}
			}
			$output .= "\n";
			$output .= " * call: {$bt['class']}{$bt['type']}{$bt['function']}($args)\n";
			$output .= " * file: {$bt['line']} - {$bt['file']}\n";
		}
		$output .= "\n";
		return $output;
	}
		
	function backtraceHTML()
	{
		$output = "<div style='text-align: left; font-family: monospace;'>\n";
		$output .= "<b>Backtrace:</b><br />\n";
		$backtrace = debug_backtrace();
		$backtrace = array_reverse($backtrace);
		array_pop($backtrace);
		$backtrace = array_reverse($backtrace);
		foreach ($backtrace as $bt)
		{
			$args = '';
			foreach ($bt['args'] as $a)
			{
				if (!empty($args))
				{
					$args .= ', ';
				}
				switch (gettype($a))
				{
					case 'integer':
					case 'double':
						$args .= $a;
						break;
					case 'string':
						$a = htmlspecialchars(substr($a, 0, 64)).((strlen($a) > 64) ? '...' : '');
						$args .= "\"$a\"";
						break;
					case 'array':
						$args .= 'Array('.count($a).')';
						break;
					case 'object':
						$args .= 'Object('.get_class($a).')';
						break;
					case 'resource':
						$args .= 'Resource('.strstr($a, '#').')';
						break;
					case 'boolean':
						$args .= $a ? 'True' : 'False';
						break;
					case 'NULL':
						$args .= 'Null';
						break;
					default:
						$args .= 'Unknown';
				}
			}
			$output .= "<br />\n";
			$output .= "<b>call:</b> {$bt['class']}{$bt['type']}{$bt['function']}($args)<br />\n";
			$output .= "<b>file:</b> {$bt['line']} - {$bt['file']}<br />\n";
		}
		$output .= "</div>\n";
		return $output;
	}

}

?>
