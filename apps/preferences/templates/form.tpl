
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
			<legend>##Theme##</legend>
			<label for="theme">
				<span>##Your favourite theme : ##</span>
			</label>
			<select name="theme" id="theme">
				<option value="" {if $currenttheme == ""}selected{/if}>##I don't know##</option>
				{foreach from=$themes key=name item=theme}
				<option value="{$name}" {if $currenttheme == $name} selected {/if}>{$name} - {$theme->getDescription()}</option>
				{/foreach}
			</select>
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
