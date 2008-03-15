

var KSortable = Object.extend(Sortable, {
  serialize: function(element) {
    var element = $(element);
    var sortableOptions = this.options(element);
    var options = Object.extend({
      tag:  sortableOptions.tag,
      only: sortableOptions.only,
      name: element.id
    }, arguments[1] || {});
    
    var items = $(element).childNodes;
    var queryComponents = new Array();
    
    for(var i=0; i<items.length; i++)
      if(items[i].tagName && items[i].tagName==options.tag.toUpperCase() &&
        (!options.only || (Element.Class.has(items[i], options.only))))
        queryComponents.push(
          encodeURIComponent(options.name) + "[]=" + 
          encodeURIComponent(items[i].id) );

    return queryComponents.join("&");
  },
  
  dropAll: function(element) {
    var element = $(element);
    var sortableOptions = this.options(element);
    var options = Object.extend({
      tag:  sortableOptions.tag,
      only: sortableOptions.only,
      name: element.id
    }, arguments[1] || {});
    
    var items = $(element).childNodes;
    var queryComponents = new Array();
    
    for(var i=0; i<items.length; i++)
    {
      if(items[i].tagName && items[i].tagName==options.tag.toUpperCase() &&
        ( !options.only || (Element.Class.has(items[i], options.only)) ) )
      {
           Effect.Fade( items[i].id );
      }
    }
  }
});

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
        *@TODO: Delete this ?
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
        Event.observe(window, "load", function() { LoadSiteNavigation(); });
        
/**
* Gestion des raccourcis clavier
*/
Event.observe(document, "keypress", function(evt) {
    var key, inputType;
    
    //Récupération des touches pressées
    if (window.event) {
        // Pour Internet Explorer
        key = window.event.keyCode;
        inputType = window.event.srcElement.tagName.toLowerCase();
    } else {
        // Pour FireFox & autres
        key = evt.which;
        inputType = evt.target.nodeName.toLowerCase()
    }

    // Désactive les raccourcis lorsque le focus est dans les champs de saisie
    if (inputType != 'input' && inputType != 'textarea' && inputType != 'select') {
        // Efface la touche tapée sous ie pour éviter qu'elle soit transmise et affichée dans le champ 
        // cible dans le cas d'un focus
        if (window.event)
            window.event.keyCode = "";

        // a : affichage des flashmails
        if (key == 97)
            flashmail_blinddown('flashmail_headerbox_unreadlist');
        // r : raffraichir les flashmails
        if (key == 114)
            flashmail_headerbox_update();
        // f : focus sur la boite rechercher
        if (key == 102)
            document.forms['search'].elements['keywords'].focus();
    }
});