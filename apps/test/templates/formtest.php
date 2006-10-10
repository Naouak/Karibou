<pre>
<?php
	//Vérification de l'existence d'un formulaire (premier appel)
		//S'il existe : vérification de sa validation précédente
		//S'il n'existe pas : ... rien

	//Définition du formulaire myform

	$myform = KFormFactory::open('myform');

	$formoptions = array(
		'method'	=> 'post',
		'action'	=> kurl(array('app'=>'test', 'page'=>'formtestpost')));
	$myform->setOptions($formoptions);
	
	//Text
	$fieldarray = array(
		'name' 			=> 'mytext',
		'defaultvalue'	=> 'cool',
		'type'			=> 'text',
		'label'			=> 'MyText');
	$myform->addFieldFromArray($fieldarray);
	$myform->addRuleToField('mytext', 'NotEmpty', _('FORM_ERROR_NotEmpty'));
	$myform->addRuleToField('mytext', 'EqualTo', _('FORM_ERROR_EqualTo'), array('comparedfield' => 'mytext2'));
	
	$fieldarray = array(
		'name' 			=> 'mytext2',
		'defaultvalue'	=> '2cool2be2',
		'type'			=> 'text',
		'label'			=> 'MyText 2');
	$myform->addFieldFromArray($fieldarray);
	$myform->addRuleToField('mytext2', 'NotEmpty', _('FORM_ERROR_NotEmpty'));
	$myform->addRuleToField('mytext2', 'EqualTo', FALSE, array('comparedfield' => 'mytext'));
	
	$fieldarray = array(
		'name' 			=> 'mytextint',
		'defaultvalue'	=> '',
		'type'			=> 'text',
		'label'			=> 'MyTextInt');
	$myform->addFieldFromArray($fieldarray);
	$myform->addRuleToField('mytext', 'NotEmpty', _('FORM_ERROR_NotEmpty'));
	$myform->addRuleToField('mytext', 'Compare', _('FORM_ERROR_EqualTo'), array('compare' => 'more', 'than' => 20));
	
	//Textarea
	$fieldarray = array(
		'name' 			=> 'mytextarea',
		'defaultvalue'	=> 'Hello ! How are you ?',
		'type'			=> 'textarea',
		'label'			=> 'MyTextArea');
	$myform->addFieldFromArray($fieldarray);
	$myform->addRuleToField('mytextarea', 'NotEmpty', _('FORM_ERROR_NotEmpty'));

	//Select
	$content = array('' => 'Choisissez un pays', 1 => 'France', 2 => 'Allemagne', 3 => 'Corée', 10 => 'Japon', 15 => 'Suisse', 23 => 'Royaume-Uni');
	$fieldarray = array(
		'name' 			=> 'myselectbox',
		'defaultvalue'	=> '23',
		'type'			=> 'select',
		'label'			=> 'MySelectBox',
		'content'		=> $content);
	$myform->addFieldFromArray($fieldarray);
	$myform->addRuleToField('myselectbox', 'NotEmpty', _('FORM_ERROR_SELECT_NotEmpty'));

	//Checkbox
	$fieldarray = array(
		'name' 			=> 'mycheckbox1',
		'type'			=> 'checkbox',
		'label'			=> 'J\'ai un chien');
	$myform->addFieldFromArray($fieldarray);
	$fieldarray = array(
		'name' 			=> 'mycheckbox2',
		'type'			=> 'checkbox',
		'label'			=> 'J\'ai un chat');
	$myform->addFieldFromArray($fieldarray);
	$fieldarray = array(
		'name' 			=> 'mycheckbox3',
		'type'			=> 'checkbox',
		'label'			=> 'J\'ai un canari');
	$myform->addFieldFromArray($fieldarray);
	$myform->addFieldGroup('mycheckbox1', 'mycheckbox3', 'Choisissez vos animaux');
	//?utile : $myform->addFieldGroupRule('NotEmpty(mycheckbox1)|NotEmpty(mycheckbox2)&NotEmpty(mycheckbox3)');

	$myform->addButton();
	
	$myform->displayForm();
	
	/*
?>
<form method="post" action="<?=kurl(array('app'=>'test', 'page'=>'formtestpost'));?>" name="myform">
<?
	
	
	$myform->displayField('mytext');
	
	$myform->displayField('mytext2');
	/*
	if (!$myform->fieldValidate('mytext'))
	{
		$errors = $myform->getFieldErrors('mytext');
		foreach($errors as $error)
		{
			echo '<font style="color: #a00;">'._($error).'</font><br />';
		}
	}

	<label for="mytext">mytext : </label>
	<input type="text" name="mytext" value="<?=$myform->getFieldValue('mytext');?>" <?if (!$myform->fieldValidate('mytext')){?>style="background-color: #fcc;"<?}?> />

	<label for="mytext">mytext2  : </label>
	<input type="text" name="mytext2" value="<?=$myform->getFieldValue('mytext2');?>" />
	/
?>
	<label for="mytext">mytextarea  : </label>
	<textarea name="mytextarea"><?=$myform->getFieldValue('mytextarea');?></textarea>
	

	<input type="submit">
</form>
<?
	*/
	KFormFactory::close('formtestpost');
?>