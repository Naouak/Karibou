<?php
/**
 * @copyright 2008 Antoine ROUSSEL <antoine.roussel@gmail.com>
 * TELECOM Lille1 - FI08
 * Master Global e-Business 2008
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 **/

class iCalExport extends Model {

    public function build() {	
	
		$id = $_GET["id"];
		//$id='19';
		
		$sql = 'SELECT `name`,`type` FROM `calendar` WHERE `id` ='.$id.''; 
		try{
			$sqlResult = $this->db->query($sql);
		}
		catch(PDOException $e){
            Debug::kill($e->getMessage());
        }
		
		if($result = $sqlResult->fetch(PDO::FETCH_ASSOC)){
			
			if($result['type'] == "public"){
				try{
					$events = $this->db->query('
						SELECT * FROM `calendar_event` WHERE `calendar_id` = '.$id.'
						AND `startdate` > ADDDATE(CURRENT_DATE,-60)
						AND `startdate` < ADDDATE(CURRENT_DATE,60);'
					);
				}
				catch(PDOException $e){
					Debug::kill($e->getMessage());
				}
			
				$v = new vcalendar(); // create a new calendar instance
					if(!empty($id)) $v->setConfig( 'unique_id', $id ); // set Your unique id
					if(!empty($result['name'])) $v->setProperty( 'X-WR-CALNAME',  $result['name'] ); 
					//$v->setProperty( 'X-WR-CALDESC', 'Description of the calendar' );
					$v->setProperty( 'method', 'PUBLISH' ); // required of some calendar software

				while($event = $events->fetch(PDO::FETCH_NUM)){
					
					$start = date_parse($event[8]);
					$end = date_parse($event[9]);
				
					$vevent = new vevent(); // create an event calendar component
					
						$vevent->setProperty( 'dtstart', array( 'year'=>$start['year'], 'month'=>$start['month'], 'day'=>$start['day'], 'hour'=>$start['hour'], 'min'=>$start['minute'], 'sec'=>$start['second'] ));
						$vevent->setProperty( 'dtend',  array( 'year'=>$end['year'], 'month'=>$end['month'], 'day'=>$end['day'], 'hour'=>$end['hour'], 'min'=>$end['minute'],  'sec'=>$end['second'] ));
						
						if(!empty($event[4])) $vevent->setProperty( 'summary', $event[4] );
						if(!empty($event[3])) $vevent->setProperty( 'description', $event[3] );
						if(!empty($event[5])) $vevent->setProperty( 'location', $event[5] ); // property name - case independent
						if(!empty($event[6])) $vevent->setProperty( 'categories', $event[6] );
						
					$v->setComponent ( $vevent ); // add event to calendar
				}

				$v->returnCalendar(); // redirect calendar file to browser
			}
		}
		else $this->assign("error","Le calendrier est privé et ne peut être exporté.");
	}
}
?>