<h1>##PREFERENCES##</h1>
<div class="preferences">
	<form action="{kurl app='preferences' action=post}" method="post">
		<fieldset>
			<legend>##LANGUAGE##</legend>
				<input type="radio" name="lang" value="fr" id="french" />
				<label for="french">
					<span>Français</span>
				</label>
				<br />
				<input type="radio" name="lang" value="en" id="english" />
				<label for="english">
					<span>English</span>
				</label>
		</fieldset>	
		<fieldset>
			<legend>##Localization##</legend>
			<input type="radio" name="localization" value="true" id="yloc" />
			<label for="yloc">
				<span>##I want to be localized##</span>
			</label>
			<br />
			<input type="radio" name="localization" value="true" id="nloc" />
			<label for="nloc">
				<span>##I don't want to be localized##</span>
			</label>
		</fieldset>
		<fieldset>
			<legend>##Validation##</legend>
			<input type="submit" value="##Save my preferences##" />
		</fieldset>
	</form>
	
	<div class="changepassword">
		<h2>##CHANGEPASSWORD##</h2>
		<a href="{kurl app='changepassword'}">##CHANGEPASSWORD##</a>
	</div>
	
</div>