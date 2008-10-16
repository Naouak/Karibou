

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

		// r : refresh flashmails
        if (key == 114)
            FlashmailManager.Instance.refreshFlashmails();
        // f : focus sur la boite rechercher
        if (key == 102)
            document.forms['search'].elements['keywords'].focus();
    }
});