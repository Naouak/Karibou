function add_application (appUrl) {
	new Ajax.Updater('default_container', appUrl, {asynchronous:true, evalScripts:true, onComplete:handlerFunc, insertion: "top"});
}

var gDisplayedHomeApps = false;
function editHomeApps (hiddenMessage, visibleMessage) {
	gDisplayedHomeApps = !gDisplayedHomeApps;
	if ( gDisplayedHomeApps ) {
		document.getElementById("personalise_button").innerHTML = hiddenMessage;
	} else {
		document.getElementById("personalise_button").innerHTML = visibleMessage;
	}
	new Effect.toggle(document.getElementById('default_page_configbar'), 'slide', { duration: 0.5 });
}

function shadeApp (appContentId) {
	rootDiv = document.getElementById(appContentId);
	if (rootDiv.style.height != "") {
		rootDiv.style.height = "";
		rootDiv.parentNode.style.minHeight = "50px";
	} else {
		rootDiv.style.height = "16px";
		rootDiv.parentNode.style.minHeight = "0px";
	}
}

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
  }
});

