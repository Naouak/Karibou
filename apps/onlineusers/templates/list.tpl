	{if ($onlineusers|@count)>0}
		<ul>
		{foreach from=$onlineusers key=key item=user}
		    <li title="{if ($user.object->getFirstname() != "")}{$user.object->getFirstname()} {$user.object->getLastname()}{else}{$user.object->getLogin()}{/if}{if $smarty.now-$user.timestamp != 0} (##LASTACTION## {$smarty.now-$user.timestamp|date_format:"%Mm%Ss"} ##AGO##){/if}">
				{if $user.object->getSurname() != ""}
				<a href="{kurl app="annuaire" username=$user.object->getLogin()}" class="userlink">{$user.object->getSurname()}</a>
				{elseif $user.object->getFirstname() != "" || $user.object->getLastname() != ""}
			   <a href="{kurl app="annuaire" username=$user.object->getLogin()}" class="userlink">{$user.object->getFirstname()} {$user.object->getLastname()}</a>
				{else}
				<a href="{kurl app="annuaire" username=$user.object->getLogin()}" class="userlink">{$user.object->getLogin()}</a>
				{/if}
				
				{if $islogged}
				<a href="{kurl app="flashmail" page="writeto" userid=$user.object->getId()}" class="sendflashmaillink" onclick="new Ajax.Updater('flashmail_headerbox_answer', '{kurl app="flashmail" page="headerbox_writeto" userid=$user.object->getId()}',{literal} {asynchronous:true, evalScripts:true}{/literal}); document.getElementById('flashmail_headerbox_answer').className='show'; return false;"><span>FlashMail</span></a>
				{/if}
			</li>
			{/foreach}
		</ul>
	{else}
		<p>##NOUSERCONNECTED##</p>
	{/if}
