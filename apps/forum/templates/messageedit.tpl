<h1>##KF_FORUM_TITLE##</h1>
<h3>##KF_FORUM_DESCRIPTION##</h3>
<div class="forum">
	<a href="{kurl app="wiki" page="help"}" onclick="javascript:popup(this.href, 'wiki_help', '800', '900', '200', '200');return false;" >##TITLE_WIKI_SYNTAX##</a>
	
	<form action="{kurl action="messagesave"}" method="post" class="messageedit">

		{if (isset($myForum))}
			<input type="hidden" name="forumid" value="{$myForum->getInfo("id")}">
		{/if}
		{if (isset($myMessage))}
			<input type="hidden" name="messageid" value="{$myMessage->getInfo("id")}">
		{/if}

		<fieldset>
			<legend>{if isset($myMessage)}##KF_MESSAGE_MODIFY##{else}##KF_MESSAGE_ADD##{/if}</legend>
			<ul>
				<li class="subject">
					<label for="subject">##KF_MESSAGE_SUBJECT## :</label>
					<input type="text" id="subject" name="messageinfos[subject]" value="{if isset($myMessage)}{$myMessage->getInfo("subject")|escape:"html"}{/if}" />
				</li>
				<li class="description">
					<label for="description">##KF_MESSAGE_DESCRIPTION## :</label>
					<textarea id="description" name="messageinfos[description]">{if isset($myMessage)}{$myMessage->getInfo("description")|escape:"html"}{/if}</textarea>
				</li>
			</ul>
		</fieldset>

		<div class="button">
			<input type="submit" value="##KF_MESSAGE_SAVE##" />
		</div>
	</form>
</div>