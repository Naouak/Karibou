{literal}

onlineusersClass = Class.create(KApp, {
	initialize: function ($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);
		this.refresher = new Ajax.PeriodicalUpdater(this.getElementById('onlineusers_live'), '{/literal}{kurl page="list"}{literal}', 
                            {evalScripts: true, frequency: 10, app: this, onSuccess: function (transport) {
                                var msg = "##NOBODYONLINE##";
                                var numUsers = transport.responseText.split('class="userlink"').length - 1;
                                if (numUsers > 1)
                                    msg = numUsers + " ##ONLINEUSERS##";
                                else if (numUsers == 1)
                                    msg = "##ONEONLINEUSER##";
				transport.request.options.app.setTitle(msg);
                            }
			});
	},
	setUserState: function () {
		new Ajax.Updater(document.getElementById('onlineusers_live'), {/literal}'{kurl page="setuserstate"}'{literal}, {
			method: 'post',
			parameters: 'userState=' + encodeURIComponent(this.getElementById("userStateSetter").value) + '&userMood=' + this.getElementById("userMoodSetter").value
		});
		return false;
	}
});
{/literal}
