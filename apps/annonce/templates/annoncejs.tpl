{literal}
var annonceClass = Class.create(KApp, {
	initialize: function ($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);
	},
	update: function (id) {
		new Ajax.Request({/literal}'{kurl action="update"}'{literal}, {
			method: 'post',
			parameters: 'update=' + encodeURIComponent(id),
			app: this,
			});
		this.refresh();
		return false;
	}
});
{/literal}
