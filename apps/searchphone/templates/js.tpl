{literal}

var searchphoneClass = Class.create(KApp, {
	initialize: function ($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);
	},
	on_phoneSearch_submit: function (event) {
		var phone = this.getElementById("phone").value;
		var goodText = "";
		for (var i = 0 ; i < phone.length ; i++) {
			if ((phone[i] >= '0') && (phone[i] <= '9'))
				goodText += phone[i];
		}
		if (goodText[0] == '0')	// Delete the first zero, sometimes users don't use it...
			goodText = goodText.substr(1);
		new Ajax.Updater(this.getElementById('results'), '{/literal}{kurl page="number"}{literal}' + goodText);
		return false;
	}
});
{/literal}
