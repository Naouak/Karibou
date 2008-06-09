<h3>##WEATHER##</h3>
##CITY## : <select name="cityList" onchange="this.form.city.value = this.value;">
		<option value = "FRXX0129" {if $city=="FRXX0129"}selected{/if}>Ajaccio</option>
		<option value = "FRXX0019" {if $city=="FRXX0019"}selected{/if}>Brest</option>
		<option value = "FRXX0212" {if $city=="FRXX0212"}selected{/if}>Chamonix</option>
		<option value = "FRXX0036" {if $city=="FRXX0036"}selected{/if}>Dijon</option>
		<option value = "UZXX0014" {if $city=="UZXX0014"}selected{/if}>Dzizak</option>
		<option value = "FRXX0151" {if $city=="FRXX0151"}selected{/if}>Dunkerque</option>
		<option value = "FRXX0153" {if $city=="FRXX0153"}selected{/if}>Grenoble</option>
		<option value = "FRXX0052" {if $city=="FRXX0052"}selected{/if}>Lille</option>
		<option value = "FRXX0055" {if $city=="FRXX0055"}selected{/if}>Lyon</option>
		<option value = "FRXX0059" {if $city=="FRXX0059"}selected{/if}>Marseille</option>
		<option value = "FRXX0068" {if $city=="FRXX0068"}selected{/if}>Montpellier</option>
		<option value = "FRXX0072" {if $city=="FRXX0072"}selected{/if}>Nantes</option>
		<option value = "FRXX0165" {if $city=="FRXX0165"}selected{/if}>Nimes</option>
		<option value = "FRXX0073" {if $city=="FRXX0073"}selected{/if}>Nice</option>
		<option value = "FRXX0074" {if $city=="FRXX0074"}selected{/if}>Orleans</option>
		<option value = "FRXX0076" {if $city=="FRXX0076"}selected{/if}>Paris</option>
		<option value = "FRXX0085" {if $city=="FRXX0085"}selected{/if}>Rouen</option>
		<option value = "FRXX0095" {if $city=="FRXX0095"}selected{/if}>Strasbourg</option>
		<option value = "FRXX0099" {if $city=="FRXX0099"}selected{/if}>Toulouse</option>
		<option value = "FRXX0278" {if $city=="FRXX0278"}selected{/if}>Villeneuve d'Ascq</option>
</select><br />
##MSG_NEW_TOWN##<br />
##CITY_CODE## : <input type="text" name="city" size="8" value="{$city}" /><br />
##NUMBEROFDAY## : <input type="text" name="number_day" value="{$number_day}" /><br />
