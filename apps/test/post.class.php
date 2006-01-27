<?php


class TestPost extends FormModel
{
	public function build()
	{
   
		if(!empty($_POST)) {
		   // validate after a POST
		   SmartyValidate::connect($this->smarty);
		   if(SmartyValidate::is_valid($_POST)) {
			   // no errors, done with SmartyValidate
			   SmartyValidate::disconnect();
			   
			   //Actions !
			   $this->setRedirectArg('page', '');
		   } else {
			   // error, redraw the form
			   $this->setRedirectArg('page', '');
			   $_SESSION["FormVariables"] = $_POST;
			   //$this->assign($_POST);
			   //$smarty->display('form.tpl');
		   }
		}

	}
}

?>