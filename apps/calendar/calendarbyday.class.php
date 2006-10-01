 <?php 

/**
 * @copyright 2005 Jonathan Semczyk <http://jon.netcv.org>
 * @copyright 2005 Benoit Tirmarche <benoit.tirmarche@telecomlille.net>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/


class CalendarByDay extends CalendarModel
{
	public function build()
	{
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "home") );

		/**
		 * Initialisation de KDate en une fois pour éviter les problèmes du 31/0X lorsque X+1 n'a que 30 jours...
		 */
		$currentDate = new KDate();
		if(isset($this->args['day']) && $this->args['day'] != '')
		{
			$cDay = $this->args['day'];
		}
		else
		{
			$cDay = $currentDate->getDay();
		}
		if(isset($this->args['month']) && $this->args['month'] != '')
		{
			$cMonth = $this->args['month'];
		}
		else
		{
			$cMonth = $currentDate->getMonth();
		}
		if(isset($this->args['year']) && $this->args['year'] != '')
		{
			$cYear = $this->args['year'];
		}
		else
		{
			$cYear = $currentDate->getYear();
		}
		$reqDate = mktime($currentDate->getHour(), $currentDate->getMinute(), $currentDate->getSecond(), $cMonth, $cDay, $cYear);
		$currentDate->timecopy($reqDate);

		$this->buildCalendar($currentDate);

		$this->assign('today', $currentDate);

		$myCalendars = new CalendarListDB($this->db, $this->currentUser);
		$cals = $myCalendars->getSubscribedCalendars();
		
		$adminCalendars = $myCalendars->getAdminCalendars();
		
		$start = new Date($currentDate);
		$start->setHour(0);
		$start->setMinute(0);
		$start->setSecond(0);
		$stop = new Date($currentDate);
		$stop->setHour(23);
		$stop->setMinute(59);
		$stop->setSecond(59);
		
		$nextDay = $currentDate->getNextDay();
		$nextDay_start = new Date($nextDay);
		$nextDay_start->setHour(0);
		$nextDay_start->setMinute(0);
		$nextDay_start->setSecond(0);
		$nextDay_stop = new Date($nextDay);
		$nextDay_stop->setHour(23);
		$nextDay_stop->setMinute(59);
		$nextDay_stop->setSecond(59);
		
		$this->columns = array();
		$this->columns[0] = new CalendarEventList();
		$nextDay_evts = array();
		if (count($cals) > 0)
		{
			foreach($cals as $cal)
			{
				$evts_ol = $cal->getReader()->getEventsByDay( $currentDate );
				$evts = $evts_ol->getAllEvents( $start, $stop );
				foreach($evts as &$e)
				{
					foreach($adminCalendars as $adm)
					{
						if( ($e->calendarid == $adm->getId()) )
						{
							$e->admin = true;
							Debug::display("TRUE");
							break;
						}
					}
				}
				$this->insertIntoColumns($evts);
	
				$nextDay_evts_ol = $cal->getReader()->getEventsByDay( $nextDay );
				$nextDay_evts = array_merge($nextDay_evts, $nextDay_evts_ol->getAllEvents($nextDay_start, $nextDay_stop) );
			}
		}
		//Debug::display($this->columns);
		$this->assign("cals", $cals);
		$this->assign("nextday_events", $nextDay_evts);
		$this->assign("columns", $this->columns);
		$this->assign("colnum", count($this->columns));
		//Verification de la presence d'erreur et affection du message d'erreur a afficher
		$this->assign("calendarMessages", $this->formMessage->getSession());
		$this->formMessage->flush();
		
		$this->assign("currentUser", $this->currentUser);
	}

	function insertIntoColumns(&$events)
	{
		foreach($events as $evt)
		{
			$test_evt = $evt;
			
			$start = $this->getRoundedStart($evt->o_start);
			$test_evt->o_start->setHour($start['hour']);
			$test_evt->o_start->setMinute($start['minute']);
			
			$evt->start_class = $start['hour']."_".$start['minute'];
			
			$span = new Date_Span();
			$span->setFromDateDiff($evt->o_start, $evt->o_stop);
			
			$size = $this->getRoundedSize($span);
			
			$test_span = clone $span;
			$test_span->hour = $size['hour'];
			$test_span->minute = $size['minute'];
			
			$test_evt->o_stop = new Date($test_evt->o_start);
			$test_evt->o_stop->addSpan($test_span);
			unset($test_span);
			$i = 0;
			$c_evts = $this->columns[$i]->testEvents($test_evt->o_start, $test_evt->o_stop);
			while( count($c_evts) > 0 )
			{
				$i++;
				if( ! isset($this->columns[$i]) )
				{
					$this->columns[$i] = new CalendarEventList();
				}
				$c_evts = $this->columns[$i]->testEvents($test_evt->o_start, $test_evt->o_stop);
			}
			$evt->size_class = $size['hour']."_".$size['minute'];
			$this->columns[$i][] = $evt;
			unset($test_evt);
		}
	}
	
	function getRoundedStart(Date $date)
	{
		$hour = $date->getHour();
		$min = floor($date->getMinute() / 15) * 15;
		if( $min == 0 ) $min = "00";
		return array( 'hour' => $hour, 'minute' => $min );
	}

	function getRoundedSize(Date_Span $span)
	{
		$hour = $span->hour;
		$min = ceil($span->minute / 15) * 15;
		if( $min == 0 ) $min = "00";
		if( $min == 60 )
		{
			$hour ++;
			$min = "00";
		}
		return array( 'hour' => $hour, 'minute' => $min );
	}
}

?>
