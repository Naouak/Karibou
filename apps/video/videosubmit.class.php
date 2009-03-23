<?php
/**
 * @copyright 2009 Pierre Ducroquet
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 */

class VideoSubmit extends AppContentModel {

	public function formFields() {
		// Renvoyer un formulaire de config utilisable...
		$helpText = _("To add a video, just input the URL of the YouTube, Dailymotion, Vimeo or Koreus page that contained it.") . "<br />";
		$helpText = $helpText . _("Example for YouTube:") . " http://www.youtube.com/watch?v=rQgIUOwVZ1<br />";
		$helpText = $helpText . _("Example for Dailymotion:") . " http://www.dailymotion.com/swf/5Mpi5TJ9gfoeahP6G<br />";
		$helpText = $helpText . _("Example for Vimeo:") . " http://www.vimeo.com/1715202<br />";
		$helpText = $helpText . _("Example for Koreus:") . " http://www.koreus.com/video/numa.html<br />";
		$helpText = $helpText . _("Careful, you must input the whole address of the page containing the video, including the http:// text.");
		return array("help" => array("type" => "help", "title" => _("Explanation & Examples"), "text" => $helpText),
			"newvideo" => array("type" => "url", "required" => true, "label" => _("Please input the page's address")),
			"comment" => array("type" => "text", "required" => false, "label" => _("Add your comment here: ")));
	}

	public function submit($parameters) {
		$video = $parameters["newvideo"];
		$comment = $parameters["comment"];

		// Enregistrement URL : Youtube ou Dailymotion
		$site = "unknown";
		if (eregi("http://(.*)youtube.com/watch\?v=(.*)", $video, $out)) {
			$video = $out[2];
			$urlvid = "http://www.youtube.com/v/";
			$site = "youtube";
		} else if (eregi("http://(.*)vimeo.com/(.*)", $video, $out)) {
			$video = $out[2];
			$urlvid = "http://vimeo.com/moogaloop.swf?clip_id=";
			$site = "vimeo";
		} else if (eregi("http://(.*)koreus.com/video/(.*).html", $video, $out)) {
			$video = $out[2];
			$urlvid = "http://www.koreus.com/video/";
			$site = "koreus";
		} else if (eregi("http://(.*)dailymotion.com/video/(.*)", $video, $out)) {
			$video = $out[2];
			$urlvid = "http://www.dailymotion.com/swf/";
			$site = "dailymotion";
		}
		
		if ((strlen($video) > 3) && ($site != "unknown"))
		{
			// Requete d'insertion
			$sql = "INSERT INTO video (`datetime`, user_id, video, site, comment) VALUES (NOW(), :user, :vid, :url, :comment)";
			$stmt = $this->db->prepare($sql);
			$stmt->bindValue(":user", $this->currentUser->getID());
			$stmt->bindValue(":vid", $video);
			$stmt->bindValue(":url", $urlvid);
			$stmt->bindValue(":comment", $comment);
			$stmt->execute();
		}
	}
}

?>
