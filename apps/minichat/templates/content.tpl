{if ($pagenum != 1)}
##PAGE## # {$pagenum}
{/if}
	<ul>
	{foreach item=m from=$post}
        <li class="{cycle values="one,two"}">
		{if strpos($m->getPost(), "/me") === 0}
			<span class="time"><acronym title="{$m->getDate()|date_format:"%A %d %B %Y @ %H:%M"}">{$m->getDate()|date_format:"%H:%M"}</acronym></span>
			<span class="action">
				<em>* <span class="user">{userlink user=$m->getAuthorObject() showpicture=true noicon=true}</span>
				<span class="message">{$m->getPost()|substr:4}</span></em>
			</span>
		{else}
			<span class="user">{userlink user=$m->getAuthorObject() showpicture=true}</span>
			<span class="time"><acronym title="{$m->getDate()|date_format:"%A %d %B %Y @ %H:%M"}">{$m->getDate()|date_format:"%H:%M"}</acronym></span>
			<span class="message">{$m->getPost()}</span>
		{/if}
        </li>
        {/foreach}
	</ul>