<p>
	{if $hide_link}
	<a href="{kurl mailbox=$mailbox pagenum=$page hide='hide'}">##hide##</a> |
	{else}
	<a href="{kurl mailbox=$mailbox pagenum=$page hide='showall'}">##show all##</a> |
	{/if}
	<a href="{kurl action='expunge'}?mailbox={$mailbox}">##expunge##</a>
</p>
<div class="headbox">
	##pages## :
	{if $page > 1}
		<a href="{kurl mailbox=$mailbox pagenum=$page-1}">##previous page##</a>	 |
	{/if}

	{section name=pagecount loop=$pagecount start=0 step=1}
    {if $smarty.section.pagecount.iteration == $page}
        <strong>{$smarty.section.pagecount.iteration}</strong>&nbsp;
	{else}
		<a href="{kurl mailbox=$mailbox pagenum=$smarty.section.pagecount.iteration}">{$smarty.section.pagecount.iteration}</a>&nbsp;
    {/if}
    {/section}
    	{if $page < $pagecount}
		|	<a href="{kurl mailbox=$mailbox pagenum=$page+1}">##next page##</a>
	{/if}
</div>

<div class="bodybox">
<ul>
<form method="POST"> <!-- formulaire pour suppression mail -->
{foreach item=header from=$messageheaders}
		<li id="mail_{$header->uid}" class="mail{if $header->deleted} deleted{elseif ! $header->seen} unread{/if}">
			<span>
				<input type="checkbox" name="msg_suppr_{$header->uid}" value="{$header->uid}" />
			</span>
			<span>
{if $header->answered} R {/if}
				<a href="{kurl page='msg' mailbox=$mailbox uid=$header->uid}">{if $header->subject}{$header->subject|truncate:80}{else}##NO_SUBJECT##{/if}</a>
			</span>
			<span>
				{$header->from|truncate:35}
			</span>
			<span>
				{$header->date|date_format}
			</span>
		</li>
{/foreach}
</ul>
<br />
<a onclick="javascript:selectAllMails();">##SELECT_ALL##</a><br />
<input type="submit" name="suppr_mail" value="##SUPPR_MAIL##" />
</form>

<script>

function selectAllMails() {ldelim}
    inputElements = document.getElementsByTagName("input");
    for (i = 0 ; i < inputElements.length ; i++) {ldelim}
        if (inputElements[i].getAttribute("type") == "checkbox") {ldelim}
            if (inputElements[i].getAttribute("name").substr(0, 10) == "msg_suppr_") {ldelim}
                inputElements[i].checked = true;
            {rdelim}
        {rdelim}
    {rdelim}
{rdelim}

{foreach item=header from=$messageheaders}
{if ! $header->deleted }
	new Draggable('mail_{$header->uid}',
		{ldelim}
		revert: true,
		starteffect: function(element) {ldelim}
			Element.removeClassName(element, 'mail');
			Element.addClassName(element, 'dragmail');
			new Effect.Opacity(element, {ldelim}duration:0.2, from:1.0, to:0.7{rdelim});
		{rdelim},
		endeffect: function(element) {ldelim}
			new Effect.Opacity(element, {ldelim}duration:0.2, from:0.7, to:1.0{rdelim});
			Element.removeClassName(element, 'dragmail');
			Element.addClassName(element, 'mail');
		{rdelim}
		{rdelim});
{/if}
{/foreach}
</script>
</div>
