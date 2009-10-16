<?php

/**
 * @copyright 2009 Gilles DEHAUDT
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *-
 * @package applications
 **/

class actionmodify extends FormModel {
    public function build() {
        $id = filter_input(INPUT_POST,"id",FILTER_SANITIZE_NUMBER_INT);
        $event = filter_input(INPUT_POST,"event",FILTER_SANITIZE_SPECIAL_CHARS);
        $visible = filter_input(INPUT_POST,"visible", FILTER_SANITIZE_SPECIAL_CHARS);
        $date = filter_input(INPUT_POST,"date");
        $datearray = explode('-',$date);
   
        $desc = filter_input(INPUT_POST,"desc",FILTER_SANITIZE_SPECIAL_CHARS);
        $url = filter_input(INPUT_POST,"URL",FILTER_SANITIZE_URL);
        if (checkdate($datearray["1"],$datearray["2"],$datearray["0"]) and $visible == "" ){
            $stmt = $this->db->prepare("UPDATE `dday` SET `event`=:event, `date`=:date, `desc`=:desc, `link`=:url WHERE `id`=:id;");
            $stmt->bindValue(":event",$event);
            $stmt->bindValue(":date",$date);
            $stmt->bindValue(":desc",$desc);
            $stmt->bindValue(":url",$url);
        }
        elseif($visible == "supprimer"){
            $stmt = $this->db->prepare("UPDATE `dday` SET `visible`=0 WHERE `id`=:id;");
        }

        $stmt->bindValue(":id",$id);
        try{
                $stmt->execute();
        }

        catch(PDOException $e){
        Debug::kill($e->getMessage());
        }


    }
}
