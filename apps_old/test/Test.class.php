<?php

class Test extends Model
{
	public function build()
	{
		//Debug::$display = false;
		$time_array = array();
		$duration_array = array();
		$multiplier = 50;
		
		$start_hour = 8;
		$end_hour = 20;
		
		for( $i = $start_hour ; $i <= $end_hour ; $i++ )
		{
			$time_array[$i."_00"] = ($i-$start_hour)*$multiplier;
			$time_array[$i."_15"] = ($i-$start_hour)*$multiplier + round($multiplier/4);
			$time_array[$i."_30"] = ($i-$start_hour)*$multiplier + round($multiplier/2);
			$time_array[$i."_45"] = ($i-$start_hour)*$multiplier + round($multiplier*3/4);
		}
		for( $i = 0; $i <= 12; $i++ )
		{
			$duration_array[$i."_00"] = ($i)*$multiplier;
			$duration_array[$i."_15"] = ($i)*$multiplier + round($multiplier/4);
			$duration_array[$i."_30"] = ($i)*$multiplier + round($multiplier/2);
			$duration_array[$i."_45"] = ($i)*$multiplier + round($multiplier*3/4);
		}
		$this->assign("time", $time_array);
		$this->assign("duration", $duration_array);
	}
}

?>