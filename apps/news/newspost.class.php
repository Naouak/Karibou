<?php 

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package applications
 **/

class NewsPost extends FormModel
{
	protected $article;

	public function build()
	{
		if ($this->permission > _READ_ONLY_)
		{
		
			//Definition des droits de l'utilisateur
			//ToDo: prendre en compte groupe du From
			if (isset($_POST['id']) && ($this->permission < _FULL_WRITE_) )
			{
				$this->article = new News ($this->userFactory);
				$this->article->loadFromId($this->db, $_POST['id']);
				if($this->currentUser->getId() == $this->article->getAuthorId())
				{
					$this->permission = _SELF_WRITE_;
				}
			}
		
		
			if (isset($_POST['postType']))
			{
				if ($_POST['postType'] == "newNews")
				{
					if (!isset($_POST['id']))
					{
						//Insertion de la nouvelle news
						$maxNewId = $this->getMaxNewsID()+1;
						$req_sql = "INSERT INTO news
										(`id`,`id_author`,`id_groups`,`title`,`content`, `last`,`time`)
										VALUES ('" . $maxNewId . "', " . $this->currentUser->getID() . ", '".$_POST["group"]."', '" . $_POST['title'] . "', '" . $_POST['content'] . "', 1, NOW())";
						
                        $insertRes = $this->db->prepare($req_sql);
						if ($insertRes->execute()) {
							$this->formMessage->add (FormMessage::SUCCESS, $this->languageManager->getTranslation("ADDED_ARTICLE"));
							$this->setRedirectArg('page', '');
						} else {
							$this->formMessage->add (FormMessage::FATAL_ERROR, $this->languageManager->getTranslation("ADD_ERROR"));
							$this->setRedirectArg('page', 'modify');
						}
					}
					else
					{
						if ($this->permission >= _SELF_WRITE_) 
						{
							//Post d'une modification de news
							//Desactivation de l'ancienne news
							$req_sql_update = "UPDATE `news`
											SET `last` = 0
											WHERE `id` = '".$_POST["id"]."'";
                            $updateReq = $this->db->prepare($req_sql_update);
							$updateReq->execute();
							//Insertion de la nouvelle news
							$req_sql_insert = "INSERT INTO news
											(`id`,`id_author`, `id_groups`,`title`,`content`,`last`,`time`)
											VALUES (" . $_POST['id'] . "," . $this->currentUser->getID() . ", '".$_POST["group"]."','" . $_POST['title'] . "', '" . $_POST['content'] . "', '1', NOW())";

                            $insertReq = $this->db->prepare($req_sql_insert);
							if ($insertReq->execute()) {
								$this->formMessage->add (FormMessage::SUCCESS, $this->languageManager->getTranslation("MODIFIED_ARTICLE"));
								$this->setRedirectArg('page', '');
							} else {
								$this->formMessage->add (FormMessage::FATAL_ERROR, $this->languageManager->getTranslation("MODIFY_ERROR"));
								$this->setRedirectArg('page', 'modify');
								$this->setRedirectArg('id', $_POST['id']);
							}

						} else {
							$this->formMessage->add (FormMessage::WARNING, $this->languageManager->getTranslation("NOT_AUTHORIZED_TO_MODIFY"));
						}
					}
				}
				elseif ($_POST['postType'] == "newNewsComment")
				{
					//Ajout d'un commentaire de news
					if ( !isset ($_POST['id_news']) ) {
						$this->formMessage->add (FormMessage::FATAL_ERROR, $this->languageManager->getTranslation("NO_ID"));
						$this->setRedirectArg('page', 'addcomment');
					} elseif ( !isset ($_POST['title']) || ($_POST['title'] == "") ) {
						$this->formMessage->add (FormMessage::NOTICE, $this->languageManager->getTranslation("NO_TITLE_FOR_COMMENT"));
						$this->setCurrentComment($_POST['id_news'],"",$_POST['content']);
						$this->setRedirectArg('page', 'addcomment');
						$this->setRedirectArg('id', $_POST['id_news']);
					} elseif ( !isset ($_POST['content']) || ($_POST['content'] == "") ) {
						$this->formMessage->add (FormMessage::NOTICE, $this->languageManager->getTranslation("NO_DESCRIPTION_FOR_COMMENT"));
						$this->setCurrentComment($_POST['id_news'], $_POST['title'],"");
						$this->setRedirectArg('page', 'addcomment');
						$this->setRedirectArg('id', $_POST['id_news']);
					} else {
						//Post d'un commentaire
						$newNewsCommentReq = "INSERT INTO news_comments
										(`id_news`,`id_parent`,`id_author`,`title`,`content`,`time`)
										VALUES (".$_POST['id_news'].", '" . $_POST['id_parent'] . "'," . $this->currentUser->getID() . ", '" . $_POST['title'] . "', '" . $_POST['content'] . "', NOW())";
						$newNewsCommentSQLResponse = $this->db->prepare($newNewsCommentReq);
						
						//Gestion des erreurs / succes
						if ($newNewsCommentSQLResponse->execute()) {
							$this->formMessage->add (FormMessage::SUCCESS, $this->languageManager->getTranslation("COMMENT_POSTED"));
							$this->setRedirectArg('page', 'view');
							$this->setRedirectArg('id', $_POST['id_news']);
							$this->setRedirectArg('displayComments', 1);
						} else {
							$this->formMessage->add (FormMessage::FATAL_ERROR, $this->languageManager->getTranslation("COMMENT_POST_ERROR"));
							$this->setRedirectArg('page', 'addcomment');
							$this->setRedirectArg('id', $_POST['id_news']);
						}
					}
				}
				elseif ($_POST['postType'] == "delNews")
				{
					if ($this->permission >= _SELF_WRITE_)
					{
						//Post d'une modification de news
						//Desactivation de l'ancienne news
						$req_sql_update = "UPDATE `news`
										SET `last` = 0
										WHERE `id` = '".$_POST["id"]."'";
						$updateReq = $this->db->prepare($req_sql_update);
                        $updateReq->execute();
						//Insertion de la nouvelle news
						$req_sql_insert = "INSERT INTO news
										(`id`,`id_author`,`last`,`deleted`,`time`)
										VALUES (" . $_POST['id'] . "," . $this->currentUser->getID() . ", '1', '1', NOW())";
										
						//Gestion des erreurs / succes
                        $insertReq = $this->db->prepare($req_sql_insert);
						if ($insertReq->execute()) {
							$this->formMessage->add (FormMessage::SUCCESS, $this->languageManager->getTranslation("DELETED_ARTICLE"));
						} else {
							$this->formMessage->add (FormMessage::FATAL_ERROR, $this->languageManager->getTranslation("MODIFY_ERROR"));
						}
						$this->setRedirectArg('page', '');
					} else {
						$this->formMessage->add (FormMessage::WARNING, $this->languageManager->getTranslation("NOT_AUTHORIZED_TO_DELETE"));
						$this->setRedirectArg('page', '');
					}
				} else {
						$this->formMessage->add (FormMessage::FATAL_ERROR, $this->languageManager->getTranslation("ACTION_ERROR"));
						$this->setRedirectArg('page', '');
				}
			}
		} else {
			//Accès en lecture seule !
			//Interdiction de supprimer
			$this->formMessage->add (FormMessage::WARNING, $this->languageManager->getTranslation("NOT_AUTHORIZED_TO_POST_COMMENTS"));
			$this->setRedirectArg('page', '');
		}

		$this->formMessage->setSession();
	}
	
	function getMaxNewsID () {
		//Post d'une nouvelle news
		//Selection du max des ids de news
		$req_sql_add = "SELECT max(id) AS maxID
						FROM news";
		$res_sql_add = $this->db->prepare($req_sql_add);
		$res_sql_add->execute();
		$row = $res_sql_add->fetch(PDO::FETCH_ASSOC);
		return $row['maxID'];
	}





	//Affecte la variable de session contenant le titre et la description du commentaire en cours
	function setCurrentComment ($newsId, $title, $content) {
		$_SESSION["currentComment"]["newsId"] = $newsId;
		$_SESSION["currentComment"]["title"] = $title;
		$_SESSION["currentComment"]["content"] = $content;
	}
}
?>
