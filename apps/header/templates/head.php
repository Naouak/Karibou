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
	<link rel="stylesheet" type="text/css" href="<?=$this->vars['karibou']['base_url'].$this->vars['cssFile'];?>" media="screen" title="Normal" />
<?
	foreach($this->vars['styles'] as $style)
	{
?>
		<link rel="alternate stylesheet" type="text/css" href="<?=$this->vars['karibou']['base_url'].$style['home_css'];?>" media="screen" title="<?=$style['titre'];?>" />
<?
	}
?>

	<script type="text/javascript" src="<?=$this->vars['karibou']['base_url']?>/themes/js/prototype.js"></script>
	<script type="text/javascript" src="<?=$this->vars['karibou']['base_url']?>/themes/js/scriptaculous.js"></script>
	<script type="text/javascript" src="<?=$this->vars['karibou']['base_url']?>/themes/js/karibou.js"></script>
	<script type="text/javascript">
		/**
		 * Ouverture d'un popup
		 *
		 * La méthode popup permet d'ouvrir une fenêtre aux dimensions et positions voulues
		 */
		function popup(adresse, nom, hauteur, largeur, haut, gauche){
			window.open(adresse, nom,'menubar=false, status=false, location=false, scrollbar=false, resizable=false, height='+hauteur+', width='+largeur+', top='+haut+', left='+gauche);
		}
		
		/**
		 * Barre de navigation de Karibou
		 *
		 * La nouvelle barre de navigation de Karibou sépare les applications par catégories :
		 * Communiquer, Organiser, Partager, Jobs et Administration
		 * Elle va permettre le développement de nouvelles applications et leur intégration
		 * plus simple dans l'intranet.
		 */
		var navCategories = new Array("Communicate","Organize","Share","Jobs","Admin");
		function LoadSiteNavigation() {
			HideAppsLinks();
		}
		function ShowAppsLinks(strMenu) {
		  HideAppsLinks();
		  document.getElementById(strMenu).style.visibility="visible";
		  return false;
		}
		function HideAppsLinks() {
			for(i in navCategories) {
				if (document.getElementById("menu"+navCategories[i])) {
					with(document.getElementById("menu"+navCategories[i]).style) {
						visibility="hidden";
					}
				}
			}
		}
		
		/**
		 * Gestion des raccourcis clavier
		 *
		 * keyCheck va nous permettre d'ajouter des raccourcis clavier à Karibou. Je l'ai pour l'instant testé
		 * sous Firefox 1.5 (Win) et Internet Explorer 6.0 (Win), à voir s'il fonctionne sous Opera, Konqueror
		 * et les autres OS...
		 */
		function keyCheck(e)
		{
			var key, inputType;
            
			//Récupération des touches pressées
			if(window.event)
			{
				//Pour Internet Explorer
				key = window.event.keyCode;
				inputType = window.event.srcElement.tagName.toLowerCase();
			}
			else
			{
				//Pour FireFox & autres
				key = e.which;
				inputType = e.target.nodeName.toLowerCase()
			}

			//Désactive les raccourcis lorsque le focus est dans les champs de saisie
			if (inputType != 'input' && inputType != 'textarea' && inputType != 'select') {
				//Efface la touche tapée sous ie pour éviter qu'elle soit transmise et affichée dans le champ 
				//cible dans le cas d'un focus
				if (window.event)
					window.event.keyCode = "";

				//a : affichage des flashmails
				if (key == 97)
					flashmail_blinddown('flashmail_headerbox_unreadlist');
				//r : raffraichir les flashmails
				if (key == 114)
					flashmail_headerbox_update();
				//f : focus sur la boite rechercher
				if (key == 102)
					document.forms['search'].elements['keywords'].focus();
			}
		}

		/**
		 * Afficher ou chacher? 
		 */
		function toggle_display(div_id)
		{
			var f = document.getElementById(div_id);
			if (f.className == "showed")
			{
					f.className = "dontshow";
			}
			else
			{
				if (f.innerHTML.length > 10)
				{
					f.className = "showed";
				}
			}
		}
		
		/**
		 * Envoi des données du minichat
		 */
		function submit_mc_form(form_id, content_id)
		{
			var f = document.getElementById(form_id);
			inputList = f.getElementsByTagName('input');
			var queryComponents = new Array();
			for( i=0 ; i < inputList.length ; i++ )
			{
				myInput = inputList.item(i);
				if( myInput.type == 'file' ) return true;
				if( myInput.name )
				{
					queryComponents.push(
					  encodeURIComponent(myInput.name) + "=" +
					  encodeURIComponent(myInput.value) );
					 
					myInput.value = "";
				}
			}

			var post_vars = queryComponents.join("&");

			new Ajax.Updater(content_id, '<?=kurl(array('app'=>"minichat", 'page'=>"content"));?>', {
					asynchronous:true,
					evalScripts:true,
					method:'post',
					postBody:post_vars,
					onComplete:process_dynamics
				});

			return false;
		}
	</script>
<? hook(array('name'=>"html_head")); ?>
</head>
