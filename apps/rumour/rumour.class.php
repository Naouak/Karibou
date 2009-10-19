<?php
/**
 * @copyright Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/
 
 
/**
 * Classe AppliVide
 *
 * @package applications
 */

// name of the class and extends permit to have an heritage of an class 
class rumour extends Model
{
// you would have the function build() in all apps you create
	public function build(){
        $stmt = $this->db->prepare("SELECT * FROM rumours order by `id` desc LIMIT :limit ;");
        $stmt->bindParam(":limit",intval($this->args["number"]),PDO::PARAM_INT);
        try{
            $stmt->execute();
        }
        catch (PDOException $e){
            Debug::kill($e->getMessage());
        }
        $rumours = $stmt->fetchAll();
        $this->assign("rumours",$rumours);
	}
}
?>
