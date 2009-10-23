<?php
/*
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 *
 * @package framework
 */

/*
 * objet commentsource
 * @package framework
 */

class CommentSource
{
    protected $name;
    protected $title;
    protected $content;
    protected $db;
    protected $id;

    function __construct(){
        $nb = func_num_args();
        if ($nb == 4){
            $args = func_get_args();
            $this->name=$args[1];
            $this->title=$args[2];
            $this->content=$args[3];
            $this->db=$args[0];
            $stmt = $this->db->prepare("INSERT INTO combox ( `name`,`title`,`content` ) VALUES (:name,:title,:content) ON DUPLICATE KEY UPDATE `title`=:title,`content`=:content ");
            $stmt->bindValue(":name",$this->name);
            $stmt->bindValue(":title",$this->title);
            $stmt->bindValue(":content",$this->content);
            $stmt->execute();
            $stmt2 = $this->db->prepare("SELECT id FROM combox where `name`=:name;");
            $stmt2->bindValue(":name",$this->name);
            $stmt2->execute();
            $tabid = $stmt2->fetch();
            $this->id=$tabid["id"];
        }
        elseif ($nb == 2){
            $this->id=func_get_arg(1);
            $this->db=func_get_arg(0);
            $stmt = $this->db->prepare("SELECT * FROM combox WHERE `id`=:id;");
            $stmt->bindValue(":id",$this->id);
            $stmt->execute();
            $combox = $stmt->fetch();
            $this->name = $combox["name"];
            $this->content=$combox["content"];
            $this->title=$combox["title"];
        }
        else{
            //what the fuck is this number of arguments
        }
    }

    public function getName(){
        return $this->name;
    }

    public function getTitle(){
        return $this->title;
    }

    public function getContent(){
        return $this->content;
    }

    public function getId(){
        return $this->id;
    }
}
