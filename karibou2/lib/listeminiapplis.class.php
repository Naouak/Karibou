<?php

/**
 * ListeMiniApplis
 *
 * @version 0.1
 * @copyright 2003 Jonathan Semczyk <jonathan.semczyk@free.fr>
 * @copyright 2003 Pierre Laden <pladen@elv.enic.fr>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 * 
 * @package lib
 **/

class ListeMiniApplis
{
    protected $listeApplis;
    protected $tabAppliOrd;

    function __construct($listeApplis)
    {
        $this->listeApplis = $listeApplis;
        $this->tabAppliOrd = array();
    }

    function __destruct()
    {
    }

    function add($appli)
    {
        // insertion de l'id dans la liste
        $this->tabAppliOrd[] = $appli;

        $this->listeApplis->add($appli, _MINI_);
    }

    function genereHTML()
    {
        $html = "";
        foreach($this->tabAppliOrd as $appli)
        {
            $html .= $this->listeApplis->getAppli($appli)->getAppli(_MINI_);
        }
        return $html;
    }
}

?>
