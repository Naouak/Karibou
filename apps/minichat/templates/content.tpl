{if ($pagenum != 1)}
##PAGE## # {$pagenum}
{/if}

	<ul>
        {foreach item=p from=$post}
        <li class="{cycle values="one,two"}">
            <span class="user"><a href="{kurl app='annuaire' username=$p->getAuthorLogin()}">{$p->getAuthor()}</a></span>
            <span class="time"><acronym title="{$p->getDate()|date_format:"%A %d %B %Y @ %H:%M"}">{$p->getDate()|date_format:"%H:%M"}</acronym></span>
            <span class="message">{$p->getPostXHTML()}</span>
        </li>
        {/foreach}
	</ul>