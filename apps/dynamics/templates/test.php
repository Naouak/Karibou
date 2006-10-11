
<div id="javascript_dynamics" style="border: 2px solid green;">
TEST
</div>

<script type="text/javascript" language="javascript">
// <![CDATA[
var dynamics_divid = 'javascript_dynamics';

/**
 * Librairie JavaScript pour la sérialisation
 */

function PhpArray2Js(tabphp_serialise) {
   this.php = corrigerChainePHP(tabphp_serialise);
   var dim = this.extraireDimTab();
   
   if (dim == 1)
   {
		this.tabjs = this.transformer(dim);
		this.ok = true;
	}
	else
	{
		this.ok = false;
	}
}

PhpArray2Js.prototype.retour = function() {
        // retourne le tableau JS
        return this.tabjs;
}

PhpArray2Js.prototype.transformer = function(dim) {
   // méthode principale qui transforme la chaîne sérialisée en un tableau Javascript
        // dim est la dimension du tableau PHP
   var tab = new Array();
   // extrait un groupe de dim données (indice - valeur)
   for (var i=0;i<dim;i++) {
       // extrait un indice : numérique ou littéral
       var indice = this.extraireIndice();
       if (indice == -1) return;
       // extrait une valeur : tableau, null, booléen, numérique ou littéral
       var valeur = this.extraireValeur();
       if (valeur == -1) tab[indice] = undefined;
       else {
           switch (valeur[0]) {
               case "N" : tab[indice] = null; break;
               case "b" : tab[indice] = valeur[1] ? true : false; break;
               case "i" : tab[indice] = parseInt(valeur[1]); break;
               case "d" : tab[indice] = parseFloat(valeur[1]); break;
               case "s" : tab[indice] = valeur[1]; break;
               case "a" : tab[indice] = this.transformer(valeur[1]); break;
               default  : tab[indice] = undefined;
           }
       }
   }
   // en fin de groupe de données, supprime le "}" final
   this.php = this.php.substring(1);
   return tab;
}

PhpArray2Js.prototype.extraireDimTab = function() {
   // extrait la dimension N du tableau de "a:N:{"
   var reg = this.php.match(/^a:(\d+):\{/);
   if (reg != null && reg != -1) {
       // on coupe le texte de l'entité détectée
       this.php = this.php.substring(reg[0].length);
       return reg[1];
   }
   else return -1;
}

PhpArray2Js.prototype.extraireIndice = function() {
   // extrait l'indice d'un tableau
   // cet indice peut être de la forme "i:\d+" ou "s:\d+:\"\w+\""
   var retour;
   var reg = this.php.match(/^((i):(\d+);|(s):\d+:"([^"]+)";)/);
   if (reg != -1) {
       // on coupe le texte de la chaîne détectée
       this.php = this.php.substring(reg[0].length);
       if (reg[2] == "i") retour = reg[3];
       else if (reg[4] == "s") retour = reg[5];
       else retour = -1;
   }
   else retour = -1;
   return retour;
}

PhpArray2Js.prototype.extraireValeur = function() {
   // extrait une valeur au début de this.php
   // cette valeur est de type "a:\d+:{" ou "N" ou "b:[01]" ou "i:\d+" ou "i:[\d\.]+" ou "s:\d+:\"\w+\""
   // on tente de détecter une valeur en tête de texte
   var retour;
   var reg = this.php.match(/^((N);|(b):([01]);|(i):(\d+);|(d):([\d\.]+);|(s):\d+:"([^"]*)";|(a):(\d+):\{)/);
   if (reg != -1) {
       // on coupe le texte de la valeur détectée
       this.php = this.php.substring(reg[0].length);
       // retour est un tableau contenant le type et la valeur de la donnée détectée dans la chaîne
       if (reg[2] == "N") retour = new Array("N", null); // valeur nulle
       else if (reg[3] == "b") retour = new Array("b", reg[4]); // booléen (true/false)
       else if (reg[5] == "i")  retour = new Array("i", reg[6]); // entier
       else if (reg[7] == "d")  retour = new Array("d", reg[8]); // entier double ou flottant
       else if (reg[9] == "s") retour = new Array("s", remplacerQuotes(reg[10])); // chaîne de caractères
       else if (reg[11] == "a") retour = new Array("a", reg[12]); // sous-tableau
       else retour = -1;
   }
   else retour = -1;
   return retour;
}

function corrigerChainePHP(chaine) {
   // remplace les &quot; en " uniquement autour des chaînes de caractères
   //chaine = chaine.replace(/:&quot;/g, ':"');
   //chaine = chaine.replace(/&quot;;/g, '";');
   //chaine = chaine.replace(/&quot/g, '');
   return chaine;
}

function remplacerQuotes(chaine) {
   // remplace les &quot; à l'intérieur des chaînes de caractères
   return chaine.replace(/&quot;/g, '\"');
}

//Affichage du tableau

//Pour afficher le tableau, voici une méthode et une fonction qui simule (en plus simple) la fonction PHP var_dump() :
PhpArray2Js.prototype.var_dump = function() {
   // affiche le tableau
   return var_dump(this.tabjs);
}

function var_dump(tab) {
   // fonction analogue à var_dump en PHP, mais plus simple
   if (arguments.length == 2) var indent = arguments[1] + "\t";
   else var indent = "\t";
   var i = 0;
   var elements = "";
   for (var elt in tab) {
       elements += (i ? ",\n " : " ") + indent + "[" + elt + "]:";
       switch (typeof tab[elt]) {
           case "string" :
               elements += "\"" + tab[elt] + "\""; break;
           case "number" :
               elements += tab[elt]; break;
           case "object" :
               if (tab[elt] == null) elements += "*null*";
               else if (tab[elt]) elements += var_dump(tab[elt], indent); break;
           case "undefined" :
               elements += "*undefined*"; break;
           default : elements += tab[elt];
       }
       i++;
   }
   return "tableau(" + i + "){\n" + elements + "\n" + (arguments[1] ? arguments[1] : "") + "}";
}

/**
 * userlink en javascript
 */
function userlink(params)
{
	urlparams = new Array();
	urlparams['app'] = 'annuaire';
	urlparams['username'] = params['username'];

	userlink_txt = "<a href=\"" + kurl(urlparams) + "\" class=\"userlink\"";
	userlink_txt += " onMouseover=\"showhint('<img src=" + params['picturepath'] + " /><span>" + params['displayname'] + "</span>','hint_profile');\" onMouseout=\"hidehint()\"";
	userlink_txt += ">" + params['displayname'] + "</a>";

	return userlink_txt;
}
/**
 * kurl en javascript
 */
function kurl(params)
{
	var app = "";
	var page = "";
	var url = "";
	var first = true;
	var proto = "http";

	//Ajout pour eviter les eventuels problemes de notice
	if (typeof params == 'object')
	{
		for (param in params)
		{
			if (typeof params[param] == 'string')
			{
				switch(param)
				{
					case 'proto':
						proto = params[param];
					break;
					case 'app':
						app = params[param];
					break;
					case 'page':
						page = params[param];
					break;
					//Interprétation
					default:
						if (!first) url += ',';
						url += params[param];
						first = false;
					break;
				}
			}
		}
	/*
    	foreach(params as $key => $value)
    	{
    		switch($key)
    		{
    			case "proto":
    				$proto = $value;
    				break;
    			case "server":
    				$server = $value;
    				break;
    			case "app":
    					$app = $value;
    				break;
    			case "page":
    					$page = $value;
    				break;
    			case "action":
    				$page = $value;
    				$url = "";
    				break 2;
    			default:
                    //Creation d'un nouvel argument, instanciation de la classe a partir du nom du parametre
					$myApp = $appList->getApp($app);
                    $class = $myApp->getArgClass($page, $key) ;
					if (class_exists($class))
					{
						$arg = new $class($value);
					}
					else
					{
						Debug::kill("[Unknown] Class : ".$class." / Key : ".$key." / Page  :".$page);
						var_dump($app, $page, $key, $arg);
						$ar = debug_backtrace();
						foreach($ar as $a)
							echo $a['file'].$a['line'].'<br />'."\n";
						die;
					}

    				//S'il y a plusieurs arguments, on les separe par des virgules
    				if( !$first ) $url .= ',';

    				//Concatenation de la fin de l'URL
    				$url .= $arg->getUrlArgument();
    				$first = false;
    				break;
    		}
    	}
		*/
	}
    else
    {
		//La variable passée en paramètre n'est pas un tableau
    }
	
    if( app != '' ) app = app + '/';
    if( page != '' ) page = page + '/';
    
	url = '<?=$GLOBALS['config']['base_url'];?>' + '/' + app + page + url;
	
	/*
	if(isset(server))
	{
		url = proto."://".server.url;
	}
	*/
	return url;
}

/**
 * Gestion des informations du dynamics
 */
	//Met à jour le dynamics
	function update_dynamics()
	{
	//, onComplete:continue
		new Ajax.Updater(dynamics_divid, '<?=kurl(array('app'=>"dynamics", 'page'=>""));?>', {asynchronous:true, evalScripts:false, onComplete:process_dynamics});
		return false;
	}
	
	//Retourne la valeur du dynamics
	function get_dynamics()
	{
		var f = document.getElementById(dynamics_divid);
		return f.innerHTML;
	}

	//Interprête les informations du dynamics
	function process_dynamics()
	{
		//Pour récupérer simplement le tableau Javascript
		var dynamics_serialized = get_dynamics();
		var tableau = new PhpArray2Js(dynamics_serialized);
		var dynamics_var = tableau.retour();
		//Nécessaire pour le fonctionnement de dynamics
		dynamics_var = dynamics_var['dynamics'];
		var f = document.getElementById('dynamics');
		var list = '';
		if (dynamics_var)
		{
			if (f.innerHTML.length == 0)
			{
				//La div dynamics ne contient pas de contenu : on initialise la structure (sans les valeurs)
			
				/**
				 * Gestion de l'affichage des utilisateurs en ligne
				 */
				 /*
				if (dynamics_var['onlineusers'].length > 0)
				{
				*/
					//Création de la div du nombre d'utilisateurs (clickable)
					//f.innerHTML += 	'<div id="onlineusers_nb"><a href="#" onClick="toggle_display(\'onlineusers_list\'); return false;">&nbsp;</a></div>';
						
					//list += '<div id="onlineusers_list">' +	dynamics_var['onlineusers'].length + ' utilisateur(s) :';

					//Création de la div de liste du nombre d'utilisateurs
					list = '<div id="onlineusers" class="showed"></div>';
					f.innerHTML += list;
				/*
				}
				/*
				else if (dynamics_var['onlineusers'].length == 0)
				{
					document.getElementById('onlineusers_nb').innerHTML = dynamics_var['onlineusers'].length;
				}
				else
				{
					document.getElementById('onlineusers_nb').innerHTML = '';
				}
				*/
				
				/**
				 * Création de la zone minichat
				 */
				list = '<div id="minichat" class="showed"></div>';
				f.innerHTML += list;
			}
			
			/**
			 * Mise à jour des valeurs
			 */
			//Nombre d'utilisateurs
			/*
			var f1 = document.getElementById('onlineusers_nb');
			var a1 = f1.getElementsByTagName("A");
			if (a1)
				a1[0].innerHTML = dynamics_var['onlineusers'].length;
			*/
			
			/**
			 * Liste des utilisateurs
			 */
			var f2 = document.getElementById('onlineusers');
			f2.innerHTML = '';
			f2.innerHTML = '<h2>' + dynamics_var['onlineusers'].length + ' <?=strtolower(_('ONLINEUSERS'));?></h2><ul></ul>';

			var u2 = f2.getElementsByTagName("UL");
			list = '';
			for(i = 0; i < dynamics_var['onlineusers'].length; i++)
			{
				userlinkparams = dynamics_var['onlineusers'][i];
				
				list += '<li>';
				//list += '<a href="' + kurl(urlparams) + '" class="userlink">' + dynamics_var['onlineusers'][i]['displayname'] + '</a>';
				list += userlink(userlinkparams);
				urlparams = Array();
				
				urlparams['app'] = "flashmail";
				urlparams['page'] = "writeto";
				urlparams['userid'] = dynamics_var['onlineusers'][i]['id'];
				list += '&nbsp;&nbsp;<a href="' + kurl(urlparams) + '" class="sendflashmaillink" onclick="new Ajax.Updater(\'flashmail_headerbox_answer\', \'' + kurl(urlparams) + '\', {asynchronous:true, evalScripts:true}); document.getElementById(\'flashmail_headerbox_answer\').className=\'show\'; return false;"><span>FlashMail</span></a>';
				list += '</li>';
			}
			u2[0].innerHTML = list;
			
			/**
			 * Affichage du MiniChat
			 */
			var f3 = document.getElementById('minichat');
			f3.innerHTML = '';
			f3.innerHTML = '<h2><?=_('APP_MINICHAT');?></h2><ul></ul>';

			var u3 = f3.getElementsByTagName("UL");
			list = '';
			for(i = 0; i < dynamics_var['minichat'].length; i++)
			{
				userlinkparams = dynamics_var['minichat'][i];
				//alert(userlinkparams['displayname']);
				list += '<li>';
				list += userlink(userlinkparams);
				urlparams = Array();
				//alert('a');
				urlparams['app'] = "flashmail";
				urlparams['page'] = "writeto";
				urlparams['userid'] = dynamics_var['minichat'][i]['id'];
				list += '';
				list += ' : ' + userlinkparams['message'];
				list += '</li>';
			}
			u3[0].innerHTML = list;
			
			urlparams = Array();
			urlparams['app'] = "minichat";
			urlparams['page'] = "";
			urlparams['action'] = "post";
			
			
			f3.innerHTML += 
			'<form action="' + kurl(urlparams) + '" method="post" id="minichat_live_form" onsubmit="return submit_mc_form(\'minichat_live_form\', \'minichat_live\');">' +
						'<input type="text" name="post" size="14" id="message" />' +
						'<input type="submit" value="<?=_('MINICHAT_SEND');?>" class="button" />' +
						'</form>';
		}
		
		//return dynamics_var;
	}
	

	//Réalise l'affichage des informations du dynamics
	function show_dynamics()
	{
		var f = document.getElementById(div_id);
		
		
		
		f.innerHTML= data;
		return false;
	}
// ]]>

	//1. Récupération via Ajax.Updater (update)
	//update_dynamics();
	update_dynamics();
	setInterval("update_dynamics()", 10000);
	//2. Get (retour de valeur) + Unserialize (get & unserialize)
	//3. Interprétation (process)
	//var tab = process_dynamics();
	//4. Affichage
</script>