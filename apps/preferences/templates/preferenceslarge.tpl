<h1>##PREFERENCES##</h1>
<div class="preferences">
	<fieldset>
		<legend>##LANGUAGE##</legend>
		<div class="flags" style="width: 200px;">
			<form action="{kurl app="preferences" action=post}" method="post" name="french">
				<input type="hidden" name="lang" value="fr" />
				<div class="french flag" title="Français"><a href="#" onClick="javascript:document.french.submit(); return false;"><span>Français</span></a></div>
			</form>
			<form action="{kurl app="preferences" action=post}" method="post" name="toEnglish">
				<input type="hidden" name="lang" value="en" />
				<div class="english flag" title="English"><a href="#" onClick="javascript:document.toEnglish.submit(); return false;"><span>English</span></a></div>
			</form>
		</div>
		<br />
	</fieldset>
	<fieldset>
		<legend>##CONFIGURE_EMAILFW##</legend>
		<div class="emailforward"><a href="{kurl app="emailforward"}">##CONFIGURE_EMAILFW##</a></div>
	</fieldset>


	<fieldset>
		<legend>##CHANGEPASSWORD##</legend>
		<div class="changepassword"><a href="{kurl app="changepassword"}">##CHANGEPASSWORD##</a></div>
	</fieldset>
</div>