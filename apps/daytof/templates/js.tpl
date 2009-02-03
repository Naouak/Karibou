{literal}

daytofClass = Class.create(KApp, {
	initialize: function($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);
		{/literal}
		this.baseURL = '{kurl app="daytof" page="datof" tofnum="9999"}';
		{literal}
		this.urls = [];
		for (var i = 0 ; i < this.config["maxtof"] ; i++) {
			this.urls[i] = this.baseURL.replace("9999", i);
		}
		this.urlIndex = 0;
		this.displayNewPicture();
		this.pe = new PeriodicalExecuter(function (pe) {
			pe.app.displayNewPicture();
		}, this.config["speed"]);
		this.pe.app = this;
	},
	onDestroy: function() {
		this.pe.stop();
	},
	onRefresh: function() {
		this.displayNewPicture();
	},
	displayNewPicture: function() {
		new Ajax.Updater(this.getElementById('daTofContainer'), this.urls[this.urlIndex]);
		this.urlIndex++;
		if (this.urlIndex >= this.urls.length)
			this.urlIndex = 0; 
	},
	onConfig: function() {
		this.urls = [];
		if (this.urlIndex >= this.config["maxtof"])
			this.urlIndex = 0;
		for (var i = 0 ; i < this.config["maxtof"] ; i++) {
			this.urls[i] = this.baseURL.replace("9999", i);
		}
		this.pe.stop();
		this.pe = new PeriodicalExecuter(function (pe) {
			pe.app.displayNewPicture();
		}, this.config["speed"]);
		this.pe.app = this;
		this.displayNewPicture();
	}
});

{/literal}
