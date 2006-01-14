	{if ($onlineusers|@count)>0}
		<ul>
		{foreach from=$onlineusers key=key item=user}
		    <li>
				<a href="{kurl app="annuaire" username=$user.object->getLogin()}" class="userlink" onMouseover="showhint('<img src=\'{$user.object->getPicturePath()}\' /></a>{if ($user.object->getFirstname() != "")}<strong>{$user.object->getFirstname()} {$user.object->getLastname()}</strong>{else}<em>{$user.object->getLogin()}</em>{/if}<br />{if $smarty.now-$user.timestamp != 0} ##LASTACTION## {$smarty.now-$user.timestamp|date_format:"%Mm%Ss"} ##AGO##{/if}','profile');" onMouseout="hidehint()">{if $user.object->getSurname() != ""}{$user.object->getSurname()}{elseif $user.object->getFirstname() != "" || $user.object->getLastname() != ""}{$user.object->getFirstname()} {$user.object->getLastname()}{else}{$user.object->getLogin()}{/if}</a>
				
				{if $islogged}
				<a href="{kurl app="flashmail" page="writeto" userid=$user.object->getId()}" class="sendflashmaillink" onclick="new Ajax.Updater('flashmail_headerbox_answer', '{kurl app="flashmail" page="headerbox_writeto" userid=$user.object->getId()}',{literal} {asynchronous:true, evalScripts:true}{/literal}); document.getElementById('flashmail_headerbox_answer').className='show'; return false;"><span>FlashMail</span></a>
				{/if}
			</li>
			{/foreach}
		</ul>
	{else}
		<p>##NOUSERCONNECTED##</p>
	{/if}
