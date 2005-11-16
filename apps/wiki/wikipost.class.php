<?php

class WikiPost extends FormModel
{
	public function build() {
        if ( isset($_POST) && isset($_POST['submit']) && $_POST['submit'] != '' ) {
            $date = date('Y-m-d H:i:s');
            $o_wiki = new Wiki($_POST['page_wiki'], $_POST['contenu_wiki'], $date, $this->currentUser->getID());
            $o_wiki->add($this->db);
        }
	}
}

?>
