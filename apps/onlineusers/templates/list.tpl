	{if ($onlineusers|@count)>0}
		<ul>
		{foreach from=$onlineusers key=key item=user}
		    <li>
				{userlink user=$user.object showpicture=$islogged}
{if ($islogged)}
	{if (!$noflashmail)}
		<a href="{kurl app="flashmail" page="writeto" userid=$user.object->getId()}" class="sendflashmaillink"
            onclick="FlashmailManager.Instance.newFlashmail('{$user.object->getSurname()|escape:'javascript'}', {$user.object->getId()}); return false;"><span>Flash</span></a>
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
