<?php 

/**
 * @copyright 2009 Gilles Dehaudt <tonton1728@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class AnnonceUpdate extends FormModel
{
	public function build ()
		{
			$app = $this->appList->getApp($this->appname);
			$config = $app->getConfig();
			$this->assign("config", $config);
			$id = filter_input(INPUT_POST,"update",FILTER_VALIDATE_INT);
			$request = "UPDATE annonce SET visible= '0' WHERE annonce.Id = :id LIMIT 1";
			try
			{
				$stmt = $this->db->prepare($request);
				$stmt->bindValue(":id",$id);
				$stmt->execute();
			}
			catch(PDOException $e)
			{
				Debug::kill($e->getMessage());
			}
		}
}