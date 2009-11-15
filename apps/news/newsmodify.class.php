<?php

/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 * @copyright 2009 Pierre Ducroquet <pinaraf@pinaraf.info>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 * @package applications
 **/

class NewsModify extends Model
{
	protected $article;

	public function getPermission()
	{
		$this->article = new News($this->userFactory);
		$this->article->loadFromId($this->db, $this->args['id']);

		if ($this->appList->getApp($this->appname)->getPermission() == _ADMIN_)
			return _ADMIN_;
		if ($this->article->getAuthorId() == $this->currentUser->getId())
			return _SELF_WRITE_;
		if ($this->article->getGroup())
			if ($this->currentUser->isInGroup($this->db, $this->article->getGroup()))
				return _READ_WRITE_;
		return _NO_ACCESS_;
	}

	public function build()
	{
		$menuApp = $this->appList->getApp($this->appname);
		$menuApp->addView("menu", "header_menu", array("page" => "modify") );

		$admingroups = $this->currentUser->getAllAdminGroups($this->db);
		$array = array();
		$i=0;
		foreach ($admingroups as $key)
		{
			$array[$i]["name"] = $key->getName();
			$array[$i]["Id"] = $key->getId();
			$i++;
		}
		$this->assign('Admin',$array);
		
		//Affectation des variables a afficher
		$this->assign("theNewsToModify", $this->article);
	}
}

?>
