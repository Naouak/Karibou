{if ($pagenum != 1)}
##PAGE## # {$pagenum}
{/if}
   {if ($permission > _READ_ONLY_)}
   	{assign var=showpicture value=true}
   {else}
      {assign var=showpicture value=false}
   {/if}
	<ul>
        {foreach item=m from=$post}
        <li class="{cycle values="one,two"}">
            <span class="user">{userlink user=$m->getAuthorObject() showpicture=$showpicture}{*<a href="{kurl app='annuaire' username=$m->getAuthorLogin()}">{$m->getAuthor()}</a>*}</span>
            <span class="time"><acronym title="{$m->getDate()|date_format:"%A %d %B %Y @ %H:%M"}">{$m->getDate()|date_format:"%H:%M"}</acronym></span>
            <span class="message">{$m->getPost()|wordwrap:34:" ":true}</span>
        </li>
        {/foreach}
	</ul>