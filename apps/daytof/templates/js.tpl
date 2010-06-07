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
		this.timeout = 0;
	},
	onDestroy: function() {
		if (this.timeout)
			window.clearTimeout(this.timeout);
	},
	onRefresh: function() {
		this.displayNewPicture();
	},
	displayNewPicture: function() {
		if (this.timeout)
			clearTimeout(this.timeout);
		new Ajax.Updater(this.getElementById('daTofContainer'), this.urls[this.urlIndex]);
		this.urlIndex++;
		if (this.urlIndex >= this.urls.length)
			this.urlIndex = 0; 
		this.timeout = window.setTimeout(this.displayNewPicture.bind(this), this.config["speed"] * 1000);
	},
	onConfig: function() {
		this.urls = [];
		if (this.urlIndex >= this.config["maxtof"])
			this.urlIndex = 0;
		for (var i = 0 ; i < this.config["maxtof"] ; i++) {
			this.urls[i] = this.baseURL.replace("9999", i);
		}
		this.displayNewPicture();
	}
});

{/literal}
