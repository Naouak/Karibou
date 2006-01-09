<h1>##EMAILFORWARD##</h1>
<div class="emailforward">
	<div class="helper">##EMAILFORWARD_DESCRIPTION##</div>
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
	<form action="{kurl action="save"}" method="post">
		<label for="email">##EMAILFORWARD_EMAIL## :</label>
		<input type="text" name="email" id="email" value="" />
		<br />
		<input type="submit" value="##EMAILFORWARD_CHANGE##" class="button" />
	</form>
</div>
