	{if ($onlineusers|@count)>0}
		<ul>
		{foreach from=$onlineusers key=key item=user}
		    <li>
				{userlink user=$user.object showpicture=$islogged}
{if ($islogged)}
	{if (!$noflashmail)}
		<a href="{kurl app="flashmail" page="writeto" userid=$user.object->getId()}" class="sendflashmaillink" onclick="new Ajax.Updater('flashmail_headerbox_answer', '{kurl app="flashmail" page="headerbox_writeto" userid=$user.object->getId()}',{literal} {asynchronous:true, evalScripts:true}{/literal}); document.getElementById('flashmail_headerbox_answer').className='show'; return false;"><span>Flash</span></a>
	{/if}
	{if $user.mood}
		<i>(##{$user.mood}##)</i>
	{/if}
	{if $user.message}
		<i>{$user.message}</i>
	{/if}
{/if}
			</li>
		{/foreach}
		</ul>
	{else}
		<p>##NOUSERCONNECTED##</p>
	{/if}
