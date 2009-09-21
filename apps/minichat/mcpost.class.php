<?php 

/**
 * @copyright 2008 Pierre Ducroquet <pinaraf@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class MCPost extends FormModel
{
	public function build()
	{
		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();
		$this->assign("config", $config);
		if ($app->getPermission() > _READ_ONLY_)
//		if ($this->currentUser->isLogged())
		{
			/* POST */
			if (isset($_POST['post']))
			{
				$message = $_POST['post'];
				if (get_magic_quotes_gpc()) {
					$message = stripslashes($message);
				}
			}
			$flooding = false;
			$flood_sql = "SELECT COUNT(*) FROM minichat WHERE id_auteur=:userId AND TIME_TO_SEC(TIMEDIFF(NOW(), `time`)) < 60";
			try {
				$stmt = $this->db->prepare($flood_sql);
				$stmt->bindValue(":userId", $this->currentUser->getID());
				$stmt->execute();
				$row = $stmt->fetch();
				if ($row[0] >= 20)
					$flooding = true;
			} catch (PDOException $e) {
				Debug::kill($e->getMessage());
			}
			if ((isset($message)) && (strlen(trim($message)) > 0) && !$flooding)
			{
				$req_sql = "INSERT INTO minichat 
					(time, id_auteur, post) VALUES
					(NOW(), :userId, :message)";
				try
				{
					$stmt = $this->db->prepare($req_sql);
					$stmt->bindValue(":userId", $this->currentUser->getID());
					$stmt->bindValue(":message", htmlspecialchars($message));
					$stmt->execute();
				}
				catch(PDOException $e)
				{
					Debug::kill($e->getMessage());
				}
			}
		}
	}
}

?>
