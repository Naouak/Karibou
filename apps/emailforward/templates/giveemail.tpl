<h1>##EMAILFORWARD##</h1>
<div class="emailforward">
{*	<div class="helper">##EMAILFORWARD_DESCRIPTION##</div>*}

	{if isset($message)}
		{if ($message == "OK")}
		<div class="success">
			##EMAILFORWARD_SUCCESS##
		</div>
		{elseif ($message == "EMAILNOTVALID")}
		<div class="error">
			##EMAILFORWARD_ERROR_EMAILNOTVALID##
		</div>
		{else}
		<div class="error">
			##EMAILFORWARD_ERROR_UNKNOWN##
		</div>		
		{/if}
	{/if}
	
{if isset($email) && $email != ""}
	<div class="helper enabled">
			##EMAILFORWARD_ENABLED_MSG##
	</div>
{else}
	<div class="helper disabled">
			##EMAILFORWARD_DISABLED_MSG##
	</div>
{/if}

	
	<form action="{kurl action="save"}" method="post">
		<fieldset>
			<legend>##EMAILFORWARD##</legend>
			<div class="email">
				<label for="email">##EMAILFORWARD_EMAIL## :</label>
				<input type="text" name="email" id="email" value="{$email}" />
			</div>
			<input type="submit" value="##EMAILFORWARD_CHANGE##" class="button" />
		</fieldset>
	</form>
</div>
