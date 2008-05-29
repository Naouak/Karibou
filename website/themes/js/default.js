function add_application (appUrl) {
	new Ajax.Updater('default_container', appUrl, {asynchronous:true, evalScripts:true, onComplete:handlerFunc, insertion:insertFunc });
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

