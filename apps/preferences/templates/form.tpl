{literal}
<style type="text/css">
	.frenchflag, .englishflag{
		display:inline-block;
		width: 40px;
		height: 25px;
		font-size: 0pt;
	}

	.frenchflag{
		background-image: url(/themes/karibou/images/preferences/flags/fr.png);
	}
	
	.englishflag{
		background-image: url(/themes/karibou/images/preferences/flags/uk.png);
	}
	
	.preferences fieldset, .preferences .changepassword{
		border: solid grey 1px;
		margin-top: 20px;
		padding: 10px;
	}
	
	.preferences .changepassword h2, .preferences legend{
		color: green;
		font-size: 12pt;
		background: white;
	}
	
	.preferences .changepassword h2{
		margin-top: -20px;
	}
</style>
{/literal}

<div class="preferences">
	<form action="{kurl app='preferences' action=post}" method="post">
		<fieldset>
			<legend>##LANGUAGE##</legend>
				<input type="radio" name="lang" value="fr" id="french" {if $lang=="fr" || !isset($lang)}checked{/if} />
				<label for="french">
					<span class="frenchflag">Français</span>
				</label>
				<br />
				<input type="radio" name="lang" value="en" id="english" {if $lang=="en"}checked{/if} />
				<label for="english">
					<span class="englishflag">English</span>
				</label>
		</fieldset>	
		<fieldset>
			<legend>##Localization##</legend>
			<input type="radio" name="localization" value="true" id="yloc" {if $localization=="true" || !isset($localization)}checked{/if} />
			<label for="yloc">
				<span>##I want to be localized##</span>
			</label>
			<br />
			<input type="radio" name="localization" value="false" id="nloc" {if $localization=="false"}checked{/if} />
			<label for="nloc">
				<span>##I don't want to be localized##</span>
			</label>
		</fieldset>
		<fieldset>
			<legend>##Validation##</legend>
			<input type="submit" value="##Save my preferences##" />
		</fieldset>
	</form>
	
	<fieldset>
		<legend>##CHANGEPASSWORD##</legend>
		<a href="{kurl app='changepassword'}">##CHANGEPASSWORD##</a>
	</fieldset>
	
</div>
