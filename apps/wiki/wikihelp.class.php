<?php
class WikiHelp extends Model
{
    public function build(){
        $wiki = new wiki2xhtmlBasic();
        // initialisation variables
        $wiki->wiki2xhtml();
        // récupération de l'aide
        $help = $wiki->help();
        
        $this->assign('help', $help);
    }

}
?>
