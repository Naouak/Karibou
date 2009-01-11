{literal}

var minichatClass = Class.create(KApp, {
	initialize: function ($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);
		this.refreshURL = {/literal}'{kurl page="content"}'{literal} + this.config["maxlines"] + "," + (this.config["userichtext"]?1:0) + "," + (this.config["inversepostorder"]?1:0);
		this.refreshInterval = Number({/literal}{$refreshInterval}{literal});
		this.refresher = new Ajax.PeriodicalUpdater(this.getElementById('minichat_live'), this.refreshURL, {asynchronous:true, evalScripts:true, frequency: this.refreshInterval});
		this.refresher.start();
	},
	postMessage: function () {
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
		new Ajax.Updater(this.getElementById('minichat_live'), this.refreshURL, {asynchronous: true, evelScripts: true});
	},
	onRefresh: function () {
		this.refresher.stop();
		this.refreshURL = {/literal}'{kurl page="content"}'{literal} + this.config["maxlines"] + "," + (this.config["userichtext"]?1:0) + "," + (this.config["inversepostorder"]?1:0);
		this.refresher = new Ajax.PeriodicalUpdater(this.getElementById('minichat_live'), this.refreshURL, {asynchronous:true, evalScripts:true, frequency: this.refreshInterval});
		this.refresher.start();
	}
});
{/literal}
