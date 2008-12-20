{literal}

daytof2Class = Class.create(KApp, {
	initialize: function($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);
		{/literal}
		this.urls = ['{kurl app="daytof" page="datof" tofnum="0"}', '{kurl app="daytof" page="datof" tofnum="1"}', '{kurl app="daytof" page="datof" tofnum="2"}'];
		{literal}
		this.urlIndex = 0;
		this.displayNewPicture();
		this.pe = new PeriodicalExecuter(function (pe) {
			pe.app.displayNewPicture();
		}, 30);
		this.pe.app = this;
	},
	onDestroy: function() {
		this.pe.stop();
	},
	displayNewPicture: function() {
		new Ajax.Updater(this.getElementById('daTofContainer'), this.urls[this.urlIndex]);
		this.urlIndex++;
		if (this.urlIndex >= this.urls.length)
			this.urlIndex = 0; 
	}
});

{/literal}
