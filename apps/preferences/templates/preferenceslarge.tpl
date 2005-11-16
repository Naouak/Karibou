<div id="preferences">
	 <h1>##PREFERENCES##</h1>
	 <form action="{kurl action=post}" method="post">
		  <strong>##PREFERENCES_LANGUAGE##</strong>:
		  <select name="lang">
				<option value="fr"{if ($langpref=="fr")} SELECTED{/if}>Francais</option>
				<option value="en"{if ($langpref=="en")} SELECTED{/if}>English</option>
		  </select>
		  <br />
		  <input type="submit" value="##UPDATE##">
	 </form>
</div>