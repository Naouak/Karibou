<h1>##SENDMESSAGETOADMIN##</h1>
<div class="contact">
	<p>##SENDMESSAGETOADMIN_DESCRIPTION##<p>
	{if isset($sent)}
		{if ($sent)}
		<div class="success">
			##MESSAGESENT_SUCCESS##
		</div>
		{else}
		<div class="error">
			##MESSAGESENT_ERROR##
		</div>
		{/if}
	{/if}
	<form action="{kurl action="send"}" method="post">
		<label for="from">##FROM_EMAIL## :</label>
		<input type="text" name="from" id="from" value="" />
		<br /><br />
		<label for="subject">##SUBJECT## :</label>
		<input type="text" name="subject" id="subject" value="" size="40" />
		<br /><br />
		<label for="message">##MESSAGE## :</label>
		<textarea name="message" rows="10" cols="60" id="message" /></textarea>
		<br /><br />
		<input type="submit" value="##SEND_MESSAGE##" class="button" />
	</form>
</div>