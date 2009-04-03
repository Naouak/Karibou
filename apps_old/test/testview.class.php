<?php


class TestView extends Model
{
	public function build()
	{
 
		if(!isset($_SESSION["FormVariables"]) || empty($_SESSION["FormVariables"])) {
			// new form, we (re)set the session data
			SmartyValidate::connect($this->smarty, true);
			// register our validators
			SmartyValidate::register_validator('fullname', 'FullName', 'notEmpty',
				false, false, 'trim');
			SmartyValidate::register_validator('phone', 'Phone', 'isNumber', true,
				false, 'trim');
			SmartyValidate::register_validator('expdate', 'CCExpDate', 'notEmpty',
				false, false, 'trim');
			SmartyValidate::register_validator('email', 'Email', 'isEmail', false,
				false, 'trim');
			SmartyValidate::register_validator('date', 'Date', 'isDate', true,
				false, 'trim');
			SmartyValidate::register_validator('password', 'password:password2', 'isEqual');
			// display form
			//$smarty->display('form.tpl');
		} else {
		   // validate after a POST
		   SmartyValidate::connect($this->smarty);
		   if(SmartyValidate::is_valid($_SESSION["FormVariables"])) {
			   // no errors, done with SmartyValidate
			   SmartyValidate::disconnect();
			   //$smarty->display('success.tpl');
		   } else {
			   // error, redraw the form
			   $this->assign($_SESSION["FormVariables"]);
			   //$smarty->display('form.tpl');
		   }
		}

	}
}

?>