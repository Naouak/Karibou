{literal}

var resetbuttonClass = Class.create(KApp, {
	initialize: function ($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);
		this.refresher = null;
		this.counter = 0;
		this.displayedButton();
	},
	resetButton: function () {
		this.stopRefresher();
		new Ajax.Updater(this.getElementById("currentView"), "{/literal}{kurl action="reset"}{literal}", {app: this, asynchronous: true, evalScripts: false, onComplete: function (transport) {
				transport.request.options.app.displayedButton();
			}});
	},
	showButton: function () {
		// Display the button tab
		this.stopRefresher();
		this.setTitle("##RESETBUTTONTITLE##");
		new Ajax.Updater(this.getElementById("currentView"), "{/literal}{kurl page="state"}{literal}", {app: this, asynchronous: true, evalScripts: false, onComplete: function (transport) {
				transport.request.options.app.displayedButton();
			}});
	},
	displayedButton: function () {
		// This function is called when the button has been displayed, to setup the refresher...
		this.counter = 0;
		this.refresher = new PeriodicalExecuter(function(pe) {
				pe.app.updateTimer();
				pe.app.counter++;
				if (pe.app.counter > 10)
					pe.app.showButton();
			}, 1);
		this.refresher.app = this;
	},
	updateTimer: function () {
		target = this.getElementById("resethour");
		if (target) {
			time = target.innerHTML.split(':');
			time[0] = parseInt(time[0]);
			time[1] = parseInt(time[1]);
			time[2] = parseInt(time[2]) + 1;
			if (time[2] % 60 != time[2]) {
				time[1]++;
				time[2] = time[2] % 60;
				if (time[1] % 60 != time[1]) {
					time[0]++;
					time[1] = time[1] % 60;
				}
			}
			output = "";
			for (var i = 0 ; i < 3 ; i++) {
				if (time[i] < 10)
					output += "0";
				output += time[i];
				if (i < 2)
					output += ":";
			}
			target.innerHTML = output;
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
		if (this.refresher) {
			this.refresher.stop();
			this.refresher = null;
		}
	}
});

{/literal}
