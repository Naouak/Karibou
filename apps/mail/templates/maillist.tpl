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
	<a href="{kurl mailbox=$mailbox pagenum=$page-1}">##previous page##</a>	 |

	{section name=pagecount loop=$pagecount start=0 step=1}
    {if $smarty.section.pagecount.iteration == $page}
        <strong>{$smarty.section.pagecount.iteration}</strong>&nbsp;
	{else}
		<a href="{kurl mailbox=$mailbox pagenum=$smarty.section.pagecount.iteration}">{$smarty.section.pagecount.iteration}</a>&nbsp;
    {/if}
    {/section}
	|	<a href="{kurl mailbox=$mailbox pagenum=$page+1}">##next page##</a>
</div>

<div class="bodybox">
<ul>
{foreach item=header from=$messageheaders}
		<li id="mail_{$header->uid}" class="mail{if $header->deleted} deleted{elseif ! $header->seen} unread{/if}">
			<span>
{if $header->answered} R {/if}
				<a href="{kurl page='msg' mailbox=$mailbox uid=$header->uid}">{$header->subject|truncate:80}</a>
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

<script>
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
