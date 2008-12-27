<? echo '<?xml version="1.0" encoding="UTF-8"?>';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<base href="http://<?=$_SERVER['HTTP_HOST'].str_replace("index.php","",$_SERVER['SCRIPT_NAME']);?>" />

	<meta name="robots" content="noindex, follow">
	<meta name="googlebot" content="noindex, follow">
	
	<title>
		<?=_('HEADER_PAGE_TITLE');?> :: <?=_('KPOWERED');?>
	</title>
	<link rel="shortcut icon" TYPE="image/x-icon" HREF="./themes/favicon.ico">
	<link rel="stylesheet" type="text/css" href="<?=$this->vars['karibou']['base_url'].$this->vars['cssFile'];?>" media="screen" title="Normal" />
<?
	foreach($this->vars['styles'] as $style)
	{
?>
		<link rel="alternate stylesheet" type="text/css" href="<?=$this->vars['karibou']['base_url'].$style['home_css'];?>" media="screen" title="<?=$style['titre'];?>" />
<?
	}
?>
<!--[if lt IE 8]>
<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE8.js" type="text/javascript"></script>
<![endif]-->
	<script type="text/javascript" src="<?=$this->vars['karibou']['base_url']?>/themes/js/prototype.js"></script>
	<script type="text/javascript" src="<?=$this->vars['karibou']['base_url']?>/themes/js/scriptaculous.js"></script>
	<script type="text/javascript" src="<?=$this->vars['karibou']['base_url']?>/themes/js/karibou.js"></script>
	<script type="text/javascript" src="<?=$this->vars['karibou']['base_url']?>/themes/js/kform.js"></script>
	<script type="text/javascript" src="<?=$this->vars['karibou']['base_url']?>/themes/js/hintbox.js"></script>
<? hook(array('name'=>"html_head")); ?>
</head>
