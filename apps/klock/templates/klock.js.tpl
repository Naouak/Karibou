// {literal}
// <script>

var klockClass = Class.create(KApp, {
	initialize: function ($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);
		var app = this;

		this.wasAnalog = false;

		// We do define some drawing functions
		this.drawingFunctions = {
			"analog": function(date) {
				if(!app.wasAnalog) {
					app.getElementById("klock_disp").innerHTML = '<object data="{/literal}{kurl app="klock" page="svg" imprecision="$imprecision"}{literal}" width="200" height="200" type="image/svg+xml" style="margin: auto;"></object>';
					app.wasAnalog = true;
				}
			},
			"binary": function(date) {
				var decimalToBinary = function(dec, digits) {
					// First : how long is the number we want ?
					var len = Math.floor(Math.log(dec) / Math.LN2) + 1;

					// We'll take the longer between len and digits
					len = Math.max(digits, len);

					var out = "";

					// All that is remaining is to find the value of each digit
					for(var i=0; i < len; i++) {
						var mod = dec % 2;
						dec = Math.floor(dec / 2);

						out = mod.toString() + out;
					}

					return out;
				};

				// Need H/M/S
				var h_dec = date.getHours();
				var m_dec = date.getMinutes();
				var s_dec = date.getSeconds();

				// Time to make some base conversion
				var h_bin = decimalToBinary(h_dec, 5);
				var m_bin = decimalToBinary(m_dec, 6);
				var s_bin = decimalToBinary(s_dec, 6);

				// Generating div's content
				var content = "<span style='font-size: 120%; font-weight: bold;'>"+h_bin+"</span> h<br />"+
					"<span style='font-size: 110%; font-weight: bold;'>"+m_bin+"</span> m<br />"+
					"<span style='font-size: 100%; font-weight: bold;'>"+s_bin+"</span> s";

				// And... display !
				app.getElementById("klock_disp").innerHTML = content;
			},
			"digital": function(date) {
				// Need H/M/S
				var h = date.getHours().toString();
				var m = date.getMinutes().toString();
				var s = date.getSeconds().toString();

				if(h.length == 1) h = "0" + h;
				if(m.length == 1) m = "0" + m;
				if(s.length == 1) s = "0" + s;

				// Generating div's content
				var content = "<span style='font-size: 200%; font-weight: bold;'>"+h+":"+m+":"+s+"</span>";

				// And... display !
				app.getElementById("klock_disp").innerHTML = content;
			}
		};

		// Now we decide for a imprecision and a time diff
		this.makeTimeDiff();

		this.updater = new PeriodicalExecuter(function(){
			app.draw();
		}, 1);
	},

	onClose: function() {
		this.updater.stop();
	},

	onConfig: function() {
		this.makeTimeDiff();
		if(this.config["mode"] != "analog") this.wasAnalog = false;
		this.draw();
	},

	makeTimeDiff: function() {
		this.diff = this.config["imprecision"] * 60000 * (Math.random() * 2 - 1);
	},

	draw: function() {
		var date = new Date();
		date.setTime(date.getTime() + this.diff);
		this.drawingFunctions[this.config["mode"]](date);
	}
});

// </script>
// {/literal}