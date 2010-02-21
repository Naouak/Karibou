{literal}

var minichatClass = Class.create(KApp, {
	initialize: function ($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);
		this.refreshURL = {/literal}'{kurl page="content"}'{literal} + this.config["maxlines"] + "," + (this.config["userichtext"]?1:0) + "," + (this.config["inversepostorder"]?1:0) + "," + (this.config["showscore"]?1:0);
		this.refreshInterval = Number({/literal}{$refreshInterval}{literal});
		this.refresher = new Ajax.PeriodicalUpdater(this.getElementById('minichat_live'), this.refreshURL, {asynchronous:true, evalScripts:true, frequency: this.refreshInterval});
	},
	minichat_keypress: function(e) {
		if (e.keyCode == 9) {	// 9 == Tab key
			var messageInput = this.getElementById("message");
			var currentText = messageInput.value;
			// Extract last word, ending at messageInput.selectionEnd...
			var lastWord = '';
			var i = messageInput.selectionEnd - 1;
			while (i >= 0) {
				var c = currentText[i];
				if ((c == ' ') || (c == "\t") || (c == ':') || (c == ',') || (c == ';'))
					break
				lastWord = c + lastWord;
				i--;
			}
			// Crude hack here to retrieve the list of onlineusers
			var oll = document.getElementById("onlineusers_live");
			if (oll) {
				var matches = oll.innerHTML.match(new RegExp("\\(\\)\">" + lastWord + "(.*)</a>", "gi"));
				// Sorry, I do not handle more than one nick for completion so far...
				if (matches.length == 1) {
					var daMatch = matches[0];
					var daName = daMatch.substring(4, daMatch.length-4);
					
					// Now, replace lastWord with daName
					var currentTextBefore = currentText.substring(0, messageInput.selectionEnd - lastWord.length);
					var currentTextAfter = currentText.substring(messageInput.selectionEnd);
					var newText = "";
					if (currentTextBefore.length < 2)
						newText = " :";
					newText = currentTextBefore + daName + newText + " " + currentTextAfter;
					messageInput.value = newText;
				}
			}
			return false;
		}
		return true;
	},
	on_minichat_live_form_submit: function () {
		if(this.getElementById("message").value.indexOf("€") >= 0){
		    var nodes = document.getElementsByClassName("handle")
		    for(var i in nodes){
			if(nodes[i].style){
			    var color = Math.round(Math.random()*16777216)-1;
			    nodes[i].style.backgroundColor = "#"+color.toString(16).toUpperCase();
			}
		    }
		}

		new Ajax.Request({/literal}'{kurl action="post"}'{literal}, {
			method: 'post',
			parameters: 'post=' + encodeURIComponent(this.getElementById("message").value),
			app: this,
			onComplete: function(transport) {
				var app = transport.request.options.app;
				app.refreshMessageList();
			}});
		this.getElementById("message").value = "";
		return false;
	},
	refreshMessageList: function () {
		new Ajax.Updater(this.getElementById('minichat_live'), this.refreshURL, {asynchronous: true, evalScripts: true});
	},
	onRefresh: function () {
		this.refresher.stop();
		this.refreshURL = {/literal}'{kurl page="content"}'{literal} + this.config["maxlines"] + "," + (this.config["userichtext"]?1:0) + "," + (this.config["inversepostorder"]?1:0) + "," + (this.config["showscore"]?1:0);
		this.refresher = new Ajax.PeriodicalUpdater(this.getElementById('minichat_live'), this.refreshURL, {asynchronous:true, evalScripts:true, frequency: this.refreshInterval});
	}
});
{/literal}
