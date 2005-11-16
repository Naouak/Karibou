<div class="write">
	<form action="{kurl app="flashmail" page="send"}" method="POST" id="flashmail_live_form" onsubmit="document.getElementById('flashmail_headerbox_answer').className='dontshow'; return submit_flashmail_form ('flashmail_live_form', 'flashmail_headerbox_full'); return true;">
		<div class="close"><a href="{kurl page="flashmail"}" onclick="document.getElementById('flashmail_headerbox_answer').className='dontshow'; return false;">##CLOSE##</a></div>

{if isset($flashmail)}
		<input type="hidden" name="omsgid" value="{$flashmail->getId()}" />
		<input type="hidden" name="to_user_id" value="{$flashmail->getAuthorId()}" />
		<div class="to">
			##TO## : <a href="{kurl app="annuaire" username=$flashmail->getAuthorLogin()}" class="user">{$flashmail->getAuthorSurname()}</a>
		</div>
		<div class="omessage">##ORIGINAL_MESSAGE## :
			<span class="text">{$flashmail->getMessage()}</span>
		</div>
{elseif isset($user)}
		<input type="hidden" name="omsgid" value="" />
		<input type="hidden" name="to_user_id" value="{$user->getId()}" />
<div class="close"><a href="{kurl page="flashmail"}" onclick="document.getElementById('flashmail_headerbox_answer').className='dontshow'; return false;">##CLOSE##</a></div>
		<div class="to">
			##TO## :
			<a href="{kurl app="annuaire" username=$user->getLogin()}" class="user">{if ($user->getSurname() != "")}{$user->getSurname()}{elseif ($user->getFirstname() != "")}{$user->getFirstname()}{else}{$user->getLogin()}{/if}</a>
		</div>
{else}
		<input type="hidden" name="omsgid" value="" />
		<label for="to_user_id">##TO## :</label><input type="text" name="to_user_id" value="" />
{/if}
		<div class="message">
			<label for="message">##MESSAGE## :</label><textarea rows="4" cols="20" name="message"></textarea>
		</div>
		<input type="submit" value="##SEND##" class="button" />
	</form>
</div>
