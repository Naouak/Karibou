

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

/**
 * Open a popup.
 */
function popup(url, name, height, width, top, left) {
	window.open(url, name, "menubar=false, status=false, location=false, scrollbar=false, resizable=false, height='+height+', width='+width+', top='+top+', left='+left);
}

/**
* Keyboard shortcuts handler
*/
Event.observe(document, "keypress", function(evt) {
	var key = evt.charCode;
	var inputType = evt.element().tagName.toLowerCase();
	// Disable keyboard shortcuts when an input field is focused
	if (inputType != 'input' && inputType != 'textarea' && inputType != 'select') {
		// r : refresh flashmails
		if (key == 114)
			FlashmailManager.Instance.refreshFlashmails();
		// f : focus search field
		if (key == 102)
			document.forms['search'].elements['keywords'].focus();
	}
});

