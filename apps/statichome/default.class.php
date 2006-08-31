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
		/* * * * * * * * * * * */
		/* Sélection des News  */
		/* * * * * * * * * * * */
		//Recherche de toutes les derniers articles non supprimés et de leurs originaux
		$reqSqlAllArticles = "
			SELECT news.id, news.id_author, news.id_groups, news.title, news.content, UNIX_TIMESTAMP(news.time) as timestamp, count(news_comments.id) as nb_comments
			FROM news LEFT OUTER JOIN news_comments ON news.id = news_comments.id_news
			WHERE (news.last = 1 AND news.deleted = 0) ".
			"GROUP BY news.id
			ORDER BY timestamp
			DESC
			LIMIT 0,8;
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
		
		/* * * * * * * * * * * * * */
		/* Sélection des Fichiers  */
		/* * * * * * * * * * * * * */
		$myKDBFSElementFactory = new KDBFSElementFactory($this->db, $this->userFactory, $this->permission);
		$this->assign("lastAddedFiles", $myKDBFSElementFactory->getLastAddedFiles());
		$this->assign("mostDownloadedFiles", $myKDBFSElementFactory->getMostDownloadedFiles());
		
		
		/* * * * * * * * * * * * * */
		/* Sélection des évènements du jour et du lendemain */
		/* * * * * * * * * * * * * */
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
	}
}

?>
