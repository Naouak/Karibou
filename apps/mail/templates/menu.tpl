<div class="menubox">
<ul>
{foreach item=mbox from=$mailboxes}
	<li id="mailfolder_{$mbox}"><a href="{kurl mailbox=$mbox}">{$mbox}</a></li>
{/foreach}
</ul>
</div>
<script>
{foreach item=mbox from=$mailboxes}
	Droppables.add("mailfolder_{$mbox}",
		{ldelim}
			hoverclass: 'drophover',
			greedy: true,
			onDrop: function(element, dropon)
			{ldelim}
				element.style.visibility = "hidden";
				new Ajax.Updater('maillist', '{kurl page="list" mailbox=$mailbox pagenum=$page}', 
					{ldelim}
						asynchronous:true,
						evalScripts:true,
						postBody: element.id+"={$mbox}"
					{rdelim});
			{rdelim}
		{rdelim});
{/foreach}
</script>