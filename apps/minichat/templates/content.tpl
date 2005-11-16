{*
	<script type="text/javascript" language="javascript">
	// <![CDATA[

setInterval("new Ajax.Updater('minichat_live', '{kurl app="minichat" page="content" pagenum=$pagenum maxlines=$maxlines}', {literal}{asynchronous:true, evalScripts:true}{/literal});", 6000);

	// ]]>
	</script>
*}
{if ($pagenum != 1)}
##PAGE## # {$pagenum}
{/if}
	<ul>
        {foreach item=p from=$post}
        <li class="{cycle values="one,two"}">
            <span class="user"><a href="{kurl app='annuaire' username=$p->getAuthorLogin()}">{$p->getAuthor()}</a></span>
            <span class="time"><acronym title="{$p->getDate()|date_format:"%A %d %B %Y @ %H:%M"}">{$p->getDate()|date_format:"%H:%M"}</acronym></span>
            <span class="message">{$p->getPost()|wordwrap:15:" ":true|escape:"html"}</span>
        </li>
        {/foreach}
	</ul>