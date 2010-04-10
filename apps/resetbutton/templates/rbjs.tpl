{literal}

var resetbuttonClass = Class.create(KApp, {
	initialize: function ($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);
		this.displayedButton();

		this.delta = 0;
		this.lastClick = new Date().getTime();
		this.lastClicker = "";

		var shown = this.getElementById("resethour").innerHTML.split(":");
		this.lastClick = new Date().getTime() - (parseInt(shown[0]) * 3600 + parseInt(shown[1]) * 60 + parseInt(shown[2])) * 1000;

		pantie.listenTo('resetbutton-*-reset', this.importJson.bind(this));
	},

	importJson: function (rawData) {
		var data = jsonParse(rawData);
		this.delta = new Date().getTime() - new Date(data.date).getTime()
		this.lastClick = data.lastClick * 1000;
		this.lastClicker = data.userlink;
		this.updateTimer();
	},

	resetButton: function () {
		new Ajax.Request("{/literal}{kurl action="reset"}{literal}", {app: this, method: "post", asynchronous: true, evalScripts: false});
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
		this.refresher1 = new PeriodicalExecuter(this.updateTimer.bind(this), 0.5);
	},

	updateTimer: function () {
		var counter = this.getElementById("resethour");
		var userlink = this.getElementById("lastresetby");

		var time = parseInt((new Date().getTime() - this.lastClick - this.delta) / 1000);
		
		var h = parseInt(time / 3600);
		var m = parseInt(time / 60) - h * 60;
		var s = time - m * 60 - h * 3600;

		if (h < 10) h = "0" + h;
		if (m < 10) m = "0" + m;
		if (s < 10) s = "0" + s;

		counter.innerHTML = h + ":" + m + ":" + s;
		if (this.lastClicker != "")
			userlink.innerHTML = this.lastClicker;
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
	}
});

{/literal}
