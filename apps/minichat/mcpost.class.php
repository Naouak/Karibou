<?php
/**
 * @version $Id: minichatgrand.class.php,v 1.2 2004/12/03 00:52:56 jon Exp $
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
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
    if (isset($_POST['post']))
    {
    $message = $_POST['post'];
    }
    elseif (isset($_GET['post']))
    {
    $message = $_GET['post'];
    }
        $req_sql = "INSERT INTO minichat (time, id_auteur, post) VALUES (NOW(), " .
             $this->currentUser->getID() . ", '" .
             $message . "')";

	try
	{
		$this->db->exec($req_sql);
	}
	catch(PDOException $e)
	{
		Debug::kill($e->getMessage());
	}
    }
}
?>
