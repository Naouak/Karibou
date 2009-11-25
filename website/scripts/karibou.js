/**
 * Open a popup.
 */
function popup(url, name, height, width, top, left) {
	window.open(url, name, 'menubar=false, status=false, location=false, scrollbar=false, resizable=false, height='+height+', width='+width+', top='+top+', left='+left);
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

Event.observe(window, "load", function(evt) {
	Ajax.Base.prototype._initialize = Ajax.Base.prototype.initialize;
	Ajax.Base.prototype.initialize = function(options) {
			if (options.method == undefined)
				options.method = "get";
			this._initialize(options);
		};
});

