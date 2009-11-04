<?php 

class AddContainer extends Model {
    public function build() {
        $this->assign("parent",$this->args["parent"]);
    }
}
