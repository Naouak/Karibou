	{if ($onlineusers|@count)>0}
		<ul>
		{foreach from=$onlineusers key=key item=user}
		    <li>{if ($islogged)}
				{userlink user=$user.object showpicture=$islogged showlocation=$islogged away=$user.away}
{else}
    {userlink user=$user.object showpicture=$islogged showlocation=$islogged}
{/if}
{if ($islogged)}
	{if (!$noflashmail)}
		<a href="#" class="sendflashmaillink" onclick="FlashmailManager.Instance.newFlashmail('{$user.object->getSurname()|escape:'quotes'|escape:'html'}', {$user.object->getId()}); return false;"><span>Flash</span></a>
	{/if}
	{if $user.mood}
		<i>(##{$user.mood}##)</i>
	{/if}
	{if $user.message}
		<i>{$user.message|strip_tags:true}</i>
	{/if}
{/if}
			</li>
		{/foreach}
		</ul>
	{else}
		<p>##NOUSERCONNECTED##</p>
	{/if}
