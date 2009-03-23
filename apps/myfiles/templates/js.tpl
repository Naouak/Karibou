{literal}

var myfilesClass = Class.create(KApp, {
	initialize: function ($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);
	},
	changeFolder: function (path) {
		new Ajax.Updater(this.getElementById('myfiles_list'), {/literal}'{kurl page="list"}'{literal}, {asynchronous:true, method: 'post', parameters: 'folder=' + path});
	}
});

{/literal}
