<?php 
/**
 * @copyright 2008 SwaEn http://lodewijk.boutu.netcv.fr
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

class weather extends Model
{
	public  function f2c($t) {
		 return round(($t-32)*5/9); 
	}

	public function build()
	{
		$picture_dir = KARIBOU_THEMES_URL . '/weather';
		if (isset($this->args["city"]) && (strlen($this->args["city"]) > 0)){
			$city_code = $this->args["city"];
		} else {
			$app = $this->appList->getApp($this->appname);
			$config = $app->getConfig();
			$city_code = $config["city"]["normal"];
		}

		if (isset($this->args["number_day"]) && (strlen($this->args["number_day"]) > 0)){
			$number_day = $this->args["number_day"];
		} else {
			$app = $this->appList->getApp($this->appname);
			$config = $app->getConfig();
			$city_code = $config["number_day"]["normal"];
		}

		$url = "http://xoap.weather.com/weather/local/".$city_code."?cc=*&unit=s&dayf=".$number_day;
		$xml_file=simplexml_load_file($url);

		$city_name= $xml_file->loc->dnam;
		$this->assign("city",$city_name);

		$days = $xml_file->dayf->day;
		
		$i = 0;

		foreach($xml_file->dayf->day as $days){
			
			$day_name[$i] = $days['t'];
			if($days->hi != "N/A")
				$highest_temp[$i]= $this->f2c(intval($days->hi));
			else
				$highest_temp[$i]= "N/A";
			
			if($days->low != "N/A")
				$lowest_temp[$i] = $this->f2c($days->low);
			else
				$lowest_temp[$i] = "N/A";
			
			if ($days->part['p'] == "d") {
				if (strlen($days->part->icon) == 1)
					$image[$i] = $picture_dir . "/0" . $days->part->icon . ".png";
				else
					$image[$i] = $picture_dir . "/" . $days->part->icon . ".png";
				$alt[$i] = $days->part->t;
			}

			$i++;
		}

		$this->assign("day_name",$day_name);
		$this->assign("high_temp",$highest_temp);
		$this->assign("low_temp",$lowest_temp);
		$this->assign("image",$image);
		$this->assign("alternatif",$alt);

	}
}
?>
