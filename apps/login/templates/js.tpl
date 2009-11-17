{literal}

var loginClass = Class.create(KApp, {
	initialize: function ($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);
	},
	cryptLoginData: function () {
		if (KeyPair) {
			var crypted = encryptedString2(KeyPair, this.getElementById("_pass").value);
			var hiddenCrypt = document.createElement("input");
			hiddenCrypt.setAttribute("type", "hidden");
			hiddenCrypt.setAttribute("name", "_crypt");
			hiddenCrypt.setAttribute("value", crypted);
			this.getElementById("_pass").value = "";
			this.getElementById("loginForm").appendChild(hiddenCrypt);
		}
	}
});

{/literal}
