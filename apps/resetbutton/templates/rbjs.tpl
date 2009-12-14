{literal}

var resetbuttonClass = Class.create(KApp, {
	initialize: function ($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);
		this.refresher = null;
		this.displayedButton();

		this.delta = 0;
		this.lastClick = new Date().getTime();
		this.lastClicker = "";
		this.lastDisplayedClicker = "";

		var shown = this.getElementById("resethour").innerHTML.split(":");
		this.lastClick = new Date().getTime() - (parseInt(shown[0]) * 3600 + parseInt(shown[1]) * 60 + parseInt(shown[2])) * 1000;
	},

	importJson: function (transport) {
		this.delta = new Date().getTime() - new Date(transport.getHeader("Date")).getTime()
		try{
			var data = transport.responseText.evalJSON(true);
		}catch(e){
			this.lastClick = new Date.getTime();
		}

		this.lastClick = data.lastClick * 1000;
		this.lastClicker = data.userlink;
	},

	resetButton: function () {
		this.stopRefresher();
		new Ajax.Request(
			"{/literal}{kurl action="reset"}{literal}",
			{
				app: this,
				method: "post",
				asynchronous: true,
				evalScripts: false,
				onComplete: function (transport) {
					transport.request.options.app.importJson(transport);
					transport.request.options.app.displayedButton();
				}
			}
		);
	},

	showButton: function () {
		// Display the button tab
		this.stopRefresher();
		this.setTitle("##RESETBUTTONTITLE##");
		new Ajax.Updater(this.getElementById("currentView"), "{/literal}{kurl page='state_html'}{literal}", {app: this, asynchronous: true, evalScripts: false, onComplete: function (transport) {
				transport.request.options.app.displayedButton();
			}});
	},

	displayedButton: function () {
		// This function is called when the button has been displayed, to setup the refresher...
		this.counter = 0;
		this.refresher1 = new PeriodicalExecuter(this.updateTimer.bind(this), 0.9);
		this.refresher2 = new PeriodicalExecuter((
			function(){
					new Ajax.Request("{/literal}{kurl page='state'}{literal}", {asynchronous: true, evalScripts: false, onComplete: this.importJson.bind(this)});
			}
		).bind(this), 10);
	},

	updateTimer: function () {
		var counter = this.getElementById("resethour");
		var userlink = this.getElementById("lastresetby");

		var time = parseInt((new Date().getTime() - this.lastClick - this.delta) / 1000);
		
		var h = parseInt(time / 3600);
		var m = parseInt(time / 60) - h * 60;
		var s = time - m * 60 - h * 3600;

		if(h < 10) h = "0" + h;
		if(m < 10) m = "0" + m;
		if(s < 10) s = "0" + s;

		counter.innerHTML = h + ":" + m + ":" + s;

		// Avoid unnecessary changes
		if(this.lastDisplayedClicker != this.lastClicker) {
			this.lastDisplayedClicker = this.lastClicker;
			userlink.innerHTML = this.lastClicker;
		}
	},

	showStats: function () {
		// Display the stats tab
		this.stopRefresher();
		this.setTitle("##STATS##");
		new Ajax.Updater(this.getElementById("currentView"), "{/literal}{kurl page="stats"}{literal}", {asynchronous: true, evalScripts: false});
	},

	showMyStats: function () {
		// Display the mystats tab
		this.stopRefresher();
		this.setTitle("##MYSTATS##");
		new Ajax.Updater(this.getElementById("currentView"), "{/literal}{kurl page="mystats"}{literal}", {asynchronous: true, evalScripts: false});
	},

	stopRefresher: function () {
		if (this.refresher1) {
			this.refresher1.stop();
			this.refresher1 = null;
		}
		if (this.refresher2) {
			this.refresher2.stop();
			this.refresher2 = null;
		}
	}
});

{/literal}
