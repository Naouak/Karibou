<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 **/

/**
 *
 * @package applications
 **/
class StaticHomeDefault extends Model
{
	public function build()
	{
		/**
		 * Assignation des variables diverses
		 */
		$this->assign('permission', $this->permission);
		
		/**
		 * Sélection des News
		*/
		//Recherche de toutes les derniers articles non supprimés et de leurs originaux
		$reqSqlAllArticles = "
			SELECT news.id, news.id_author, news.id_groups, news.title, news.content, UNIX_TIMESTAMP(news.time) as timestamp, count(news_comments.id) as nb_comments
			FROM news LEFT OUTER JOIN news_comments ON news.id = news_comments.id_news
			WHERE (news.last = 1 AND news.deleted = 0) ".
			"GROUP BY news.id
			ORDER BY timestamp
			DESC
			LIMIT 0,20;
			";

		$articles = array ();
		$resSqlAllArticles = $this->db->prepare($reqSqlAllArticles);
		$resSqlAllArticles->execute();
		
		//Pour eviter les problemes de "Err 2014 : Command out of sync. You can't run this query now..."
		//il faut faire un fetchall, afin de vider la pile des enregistrements pour les traiter par la suite
		while ( $row = $resSqlAllArticles->fetch(PDO::FETCH_ASSOC) )
		{
			//Creation de l'objet d'une news
			$aNews = new News($this->userFactory);
			$aNews->load(
				$row['id'],
				$row['timestamp'], 
				$this->userFactory->prepareUserFromId($row['id_author']), 
				$row['id_groups'],
				$row['title'],
				$row['content'],
				$row['nb_comments']
				);

			$articles[] = $aNews;
		}
		
		$this->assign('articles', $articles);
		
		/**
		 * Sélection des Fichiers
		 */
		$myKDBFSElementFactory = new KDBFSElementFactory($this->db, $this->userFactory, $this->permission);
		$this->assign("lastAddedFiles", $myKDBFSElementFactory->getLastAddedFiles(20));
		$this->assign("mostDownloadedFiles", $myKDBFSElementFactory->getMostDownloadedFiles(5));
		
		
		/**
		 * Sélection des évènements du jour et du lendemain
		 */

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
		
		/* Aujourd'hui */
		$today = $currentDate;
		$today_start = new Date($today);
		$today_start->setHour(0);
		$today_start->setMinute(0);
		$today_start->setSecond(0);
		$today_stop = new Date($today);
		$today_stop->setHour(23);
		$today_stop->setMinute(59);
		$today_stop->setSecond(59);
		
		$this->columns = array();
		$this->columns[0] = new CalendarEventList();
		$today_evts = array();
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
				//$this->insertIntoColumns($evts);
	
				$today_evts_ol = $cal->getReader()->getEventsByDay( $today );
				$today_evts = array_merge($today_evts, $today_evts_ol->getAllEvents($today_start, $today_stop) );
			}
		}
		
		/* Demain */
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
				//$this->insertIntoColumns($evts);
	
				$nextDay_evts_ol = $cal->getReader()->getEventsByDay( $nextDay );
				$nextDay_evts = array_merge($nextDay_evts, $nextDay_evts_ol->getAllEvents($nextDay_start, $nextDay_stop) );
			}
		}
		//Debug::display($this->columns);
		$this->assign("cals", $cals);
		$this->assign("today_events", $today_evts);
		$this->assign("nextday_events", $nextDay_evts);
		
		
		/**
		 * Récupération des dernières offres d'emploi et de stage
		 */
		$netJobs = new NetJobs ($this->db, $this->userFactory);
		$myJobs = $netJobs->getJobList(20);
		$this->assign("jobs", $myJobs);
		
		/**
		 * Derniers évènements sur l'intranet
		 */
		foreach($this->vars['articles'] as $article)
		{
			$iEvents[] = array('secondsago' => $article->getSecondsSinceLastUpdate(), 'type' => 'article', 'object' => $article);
		}
		foreach($this->vars['lastAddedFiles'] as $file)
		{
			$iEvents[] = array('secondsago' => $file->getSecondsSinceLastUpdate(), 'type' => 'file', 'object' => $file);
		}
		foreach($this->vars['jobs'] as $job)
		{
			$iEvents[] = array('secondsago' => $job->getSecondsSinceLastUpdate(), 'type' => 'job', 'object' => $job);
		}
		sort($iEvents);
		$this->assign('iEvents', $iEvents);
		
		
	}
	
	protected function buildCalendar(KDate $currentDate)
	{
		$firstDayOfTheMonth = new Date($currentDate);
		$firstDayOfTheMonth->setDay(1);
		
		$myCalendars = new CalendarListDB($this->db, $this->currentUser);
		$cals = $myCalendars->getSubscribedCalendars();
		$month_evts_ol = array();
		if (count($cals) > 0)
		{
			foreach($cals as $cal)
			{
				$month_evts_ol[] = $cal->getReader()->getEventsByMonth( $currentDate );
			}
		}
		$days = array();
		$i=0;
		
		$previousDay = $firstDayOfTheMonth;
		while($previousDay->getDayOfWeek() != 1)
		{
			$previousDay = $previousDay->getPrevDay();
			$days[$i] = array('date' => $previousDay);
			$i++;
		}
		$days = array_reverse($days);
		$nextDay = $firstDayOfTheMonth;
		while( ($currentDate->getMonth() == $nextDay->getMonth()) || 
			((sizeof($days) % 7 ) != 0) )
		{
			$nextDay_start = new Date($nextDay);
			$nextDay_start->setHour(0);
			$nextDay_start->setMinute(0);
			$nextDay_start->setSecond(0);
			$nextDay_stop = new Date($nextDay->getNextDay());
			$nextDay_stop->setHour(0);
			$nextDay_stop->setMinute(0);
			$nextDay_stop->setSecond(0);			
			
			$nextDay_evts = array();
			foreach($month_evts_ol as $month_evts)
			{
				$nextDay_evts = array_merge($nextDay_evts, $month_evts->getAllEvents($nextDay_start, $nextDay_stop) );
			}
			//Debug::display($nextDay_evts);
			$days[$i] = array('date' => $nextDay, 'events' => $nextDay_evts);
			$nextDay = $nextDay->getNextDay();
			$i++;
		}
		
		$title = $currentDate->getMonthName() . ' ' . $currentDate->getYear();
		$this->assign('days', $days);
		$this->assign('weekDayName', KDate::getWeekNameArray());
		$this->assign('currentDate', $currentDate);
		$this->assign('previousMonth', $currentDate->getPrevMonth());
		$this->assign('nextMonth', $currentDate->getNextMonth());
	}
}

?>
