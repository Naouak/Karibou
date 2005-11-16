<?php

class Wiki {
    
    public $title = '';
    public $content = '';
    public $date_modif = '';
    public $auteur;
    public $id = '';
    
    public function __construct($title, $content, $date, $auteur) {
    
        $this->setTitle($title);
        $this->setContent($content);
        $this->setUser($auteur);
        $this->setDateModif($date);
    
    }
    
    public function setTitle($title){
        $this->title = $title;
    }
    
    public function getTitle() {
        return $this->title;
    }

    public function setContent($content) {
        $this->content = $content;
    }
    
    public function getContent() {
        return $this->content;
    }
    
    public function getContentXhtml(){
        $wiki = new wiki2xhtmlBasic();
        // initialisation variables
        $wiki->wiki2xhtml();
        $content = $wiki->transform($this->content);
        return $content;
    }
    
    public function setDateModif($date) {
        $this->date_modif = $date;
    }
    
    public function getDateModif() {
        return $this->date_modif;
    }
    
    public function setUser($user){
        $this->user = $user;
    }
    
    public function getUser() {
        return $this->user;
    }
    
    public function add($db){
        WikiFactory::init($db);
        WikiFactory::insert($this->getTitle(), $this->getContent(), $this->getDateModif(), $this->getUser()->getID());
    }
    
    public function history($db){
        WikiFactory::init($db);
        $result = WikiFactory::selectHistory($this->title);
        return $result;
    }
    
}

?>
