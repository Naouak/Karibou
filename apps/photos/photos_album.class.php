<?php 
/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 */

class photosAlbum extends Model {
    $objalbum = new album($this->args["id"]);
    $pictures = $objalbum->getAllPictures();
    $array_pict = array();
    $i = 0;
    foreach($pictures as $picture) {
        $objpict = new Photos($picture["id"]);
        if ($objpict->canRead($this->currentUser)){
            $array_pict[$i]["id"] = $objpict->getId();
            $array_pict[$i]["name"] = $objpict->getName();
            $array_pict[$i]["date"] = $objpict->getDate();
            $array_pict[$i]["path"] = $objpict->getFileName();
            $i++;
        }
    }
    $this->assign("pictures",$array_pict);
}
