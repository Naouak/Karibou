 <?php 

/**
 * @version $Id: calendrier.class.php,v 1.2 2004/12/03 00:52:56 jon Exp $
 * @copyright 2005 Benoit Tirmarche <benoit.tirmarche@telecomlille.net>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/


class CalendrierByDay extends Model
{
	public function build()
	{
		$currentDate = new KDate();
		if(isset($this->args['year']) && $this->args['year'] != '')
		{
			$currentDate->setYear($this->args['year']);
		}
		if(isset($this->args['month']) && $this->args['month'] != '')
		{
			$currentDate->setMonth($this->args['month']);
		}
		if(isset($this->args['day']) && $this->args['day'] != '')
		{
			$currentDate->setDay($this->args['day']);
		}
		$this->assign('today', $currentDate);
		
		$ical = new ICal(dirname(__FILE__) . '/calendars/karibou.ics');
	}

}

?>