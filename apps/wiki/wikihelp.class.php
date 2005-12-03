<?php
class WikiHelp extends Model
{
    public function build(){
    	$menuApp = $this->appList->getApp($this->appname);
	$menuApp->addView("menu", "header_menu", array("page" => "help", "docu" => "", "mode" => ""));
        $wiki = new wiki2xhtmlBasic();
        // initialisation variables
        $wiki->wiki2xhtml();
        // récupération de l'aide
        $help = $wiki->help();
        
        $this->assign('help', $help);
    }

}
?>
