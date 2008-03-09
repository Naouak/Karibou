<h1>##CHANGEPASSWORD_TITLE##</h1>
<div class="changepassword">
	<p>##CHANGEPASSWORD_DESCRIPTION##<p>
	{if isset($message)}
		{if ($message == "OK")}
		<div class="success">
			##PASSWORDCHANGED_SUCCESS##
		</div>
		{elseif ($message == "MINLENGTH")}
		<div class="error">
			##PASSWORDCHANGED_ERROR_MINLENGTH##
		</div>
		{elseif ($message == "DONTMATCH")}
		<div class="error">
			##PASSWORDCHANGED_ERROR_DONTMATCH##
		</div>
		{else}
		<div class="error">
			##PASSWORDCHANGED_ERROR_UNKNOWN##
		</div>		
		{/if}
	{/if}
	<form action="{kurl action="changepassword"}" method="post">
		<label for="currentpassword">##CURRENTPASSWORD## :</label>
		<input type="password" name="currentpassword" id="currentpassword" value="" />
		<br />
		<label for="newpassword1">##NEWPASSWORD1## :</label>
		<input type="password" name="newpassword1" id="newpassword1" value="" />
		<br />
		<label for="newpassword2">##NEWPASSWORD2## :</label>
		<input type="password" name="newpassword2" id="newpassword2" value="" />
		<br />
		<input type="submit" value="##CHANGE_PASSWORD##" class="button" />
	</form>
</div>
