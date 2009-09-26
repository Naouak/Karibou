{literal}

var videoClass = Class.create(KApp, {
	initialize: function ($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);
		alert("Initializing video");
	},
	beforeOverlay: function() {
		this.getElementById("videoObject").style.visibility = "hidden";
	},
	afterOverlay: function() {
		this.getElementById("videoObject").display = "block";
	}
});
{/literal}
