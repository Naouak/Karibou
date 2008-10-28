<h3 class="handle">##KLOCKTITLE##</h3>

<div style="text-align: center;" id="{$id}_klock_content">
</div>

<script type="text/javascript">
	{literal}
	var klock_mode = "{/literal}{$mode}{literal}";
	var imprecision = {/literal}{$imprecision}{literal};
	
	var seconds = document.getElementById("seconds");
	var minutes = document.getElementById("minutes");
	var hours   = document.getElementById("hours");

	var serverTimeAtLoad = {/literal}{$time}{literal};
	var clientTimeAtLoad = (new Date()).getTime();

	var diff = serverTimeAtLoad * 1000 - clientTimeAtLoad;
	var timeError = 1000 * imprecision * (Math.random() * 2 - 1);

	/**
	 * Gets the time zone shift
	 */
	function getTzShift() {
		var serverTz = {/literal}{$tz}{literal};
		var clientTz = - (new Date()).getTimezoneOffset() * 60;
		return clientTz * 1000 - serverTz * 1000;
	}

	/**
	 * Returns an imprecise date object.
	 */
	function makeDate() {
		var currentDate = new Date();
		currentDate.setTime(currentDate.getTime() + diff + timeError + getTzShift());
		return currentDate;
	}

	/**
	 * Puts a fuzzy clock into the klock container
	 */
	function showKlockTextual(date) {
		$('{/literal}{$id}{literal}_klock_content').innerHTML = "Il est probable que l'heure actuelle soit entre minuit de hier soir et le minuit qui arrive.<br />PS: ce mode n'est pas encore implémenté... Patience !";
	}

	/**
	 * Puts an analogical clock into the klock container
	 */
	function showKlockAnalog() {
		$('{/literal}{$id}{literal}_klock_content').innerHTML = '<object data="{/literal}{kurl app="klock" page="svg" imprecision="$imprecision"}{literal}" width="200" height="200" type="image/svg+xml" style="margin: auto;"></object>';
	}

	/**
	 * Puts a binary clock into the klock container
	 */
	function showKlockBinary(date) {
		// Need H/M/S
		var h_dec = date.getHours();
		var m_dec = date.getMinutes();
		var s_dec = date.getSeconds();

		// Time to make some base conversion
		var h_bin = decimalToBinary(h_dec, 4);
		var m_bin = decimalToBinary(m_dec, 6);
		var s_bin = decimalToBinary(s_dec, 6);

		// Generating div's content
		var content = "<span style='font-size: 120%; font-weight: bold;'>"+h_bin+"</span> h<br />"+
			"<span style='font-size: 110%; font-weight: bold;'>"+m_bin+"</span> m<br />"+
			"<span style='font-size: 100%; font-weight: bold;'>"+s_bin+"</span> s";

		// And... display !
		$('{/literal}{$id}{literal}_klock_content').innerHTML = content;
	}

	/**
	 * Puts a digital clock into the klock container
	 */
	function showKlockDigital(date) {
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
		$('{/literal}{$id}{literal}_klock_content').innerHTML = content;
	}

	/**
	 * Converts an integer to its binary representation (in a string)
	 */
	function decimalToBinary(dec, digits) {
		// First : how long is the number we want ?
		var len = Math.floor(Math.log(dec) / Math.LN2);

		// We'll take the longer between len and digits
		len = Math.max(digits, len);

		var out = "";

		// All that is remaining is to find the value of each digit
		for(var i=0; i < len; i++) {
			var mod = dec % 2;
			var dec = Math.floor(dec / 2);

			out = mod.toString() + out;
		}

		return out;
	}

	/**
	 * Display the appropriate klock into the the klock container
	 */
	function showKlock(date) {
		if(klock_mode == "textual") {
			showKlockTextual(date);
			setTimeout("showKlock(makeDate())", 1000);
		} else if(klock_mode == "binary") {
			showKlockBinary(date);
			setTimeout("showKlock(makeDate())", 1000);
		} else if(klock_mode == "digital") {
			showKlockDigital(date);
			setTimeout("showKlock(makeDate())", 1000);
		} else if(klock_mode == "analog") {
			showKlockAnalog();
		}
	}

	showKlock(makeDate());
	{/literal}
</script>