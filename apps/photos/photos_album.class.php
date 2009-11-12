<?php 
/**
 * @copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.gnu.org/licenses/gpl.html.
 *
 */

/**
 * Classe photosAlbum
 * @package applications
 */

class photosAlbum extends Model {
	public function build() {
		$container = containerFactory::getInstance();
		$objalbum = $container->getPictureStorage($this->args["id"]);
		$pictures = $objalbum->getAllPictures();
		$array_pict = array();
		foreach($pictures as $picture) {
			$objpict = new pictures($picture["id"]);
//                    if ($objpict->can("read")){
			$array_pict[]["id"] = $objpict->getId();
			$array_pict[]["name"] = $objpict->getName();
			$array_pict[]["date"] = $objpict->getDate();
			$array_pict[]["path"] = $objpict->getFileName();
  //                }
		}
		$this->assign("pictures",$array_pict);
 		$this->assign("parentpath",$objalbum->getAllParent());
		$this->assign("id",$this->args["id"]);
		$this->assign("type","album");
 		$this->assign("tags",$objalbum->getAllTags());
		
    }
}
