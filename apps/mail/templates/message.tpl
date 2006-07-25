<h1>##WEBMAIL_TITLE## ##FOR## {$username}</h1>
<h2>{$header->subject}</h2>


<div class="headbox">
	##FROM## :
{foreach name=from item=addr from=$header->from}
	{if ! $smarty.foreach.from.first} , {/if}
	{$addr->personal} &lt;<a href="mailto:{$addr->mailbox}@{$addr->host}">{$addr->mailbox}@{$addr->host}</a>&gt;
{/foreach}
	<br />
	##FOR## :
{foreach name=to item=addr from=$header->to}
	{if ! $smarty.foreach.to.first} , {/if}
	{$addr->personal} &lt;<a href="mailto:{$addr->mailbox}@{$addr->host}">{$addr->mailbox}@{$addr->host}</a>&gt;
{/foreach}
	<br />
{if $header->cc}
	##CC## :
{foreach name=cc item=addr from=$header->cc}
	{if ! $smarty.foreach.cc.first} , {/if}
	{$addr->personal} &lt;<a href="mailto:{$addr->mailbox}@{$addr->host}">{$addr->mailbox}@{$addr->host}</a>&gt;
{/foreach}
	<br />
{/if}
	<br />
	##Date## : {$header->date}<br />
	##SUBJECT## : {$header->subject}
</div>
<div class="headbox">
	{if $uid_next}<a href="{kurl page='msg' mailbox=$mailbox uid=$uid_next}">##next##</a> | {/if}
	{if $uid_prev}<a href="{kurl page='msg' mailbox=$mailbox uid=$uid_prev}">##previous##</a> | {/if}
	<a href="{kurl page='reply' mailbox=$mailbox uid=$uid}">##Reply##</a> |
	<a href="{kurl page='reply' mailbox=$mailbox uid=$uid opt='all'}">##Reply All##</a> |
	<a href="{kurl page='remove'}?mailbox={$mailbox}&uid={$uid}">##Remove##</a>
</div>
<div class="bodybox">
	{$body}
</div>
{if $attachments}
<div class="attachbox">
	<ul>
	{foreach item=attach from=$attachments}
	{assign var="attributes" value=$attach->getAttributes()}
		<li><a href="{kurl page='att' mailbox=$mailbox uid=$uid attachment=$attach->getPartNo()}">
			{$attributes.filename} ({$attach->getSize()})
		</a></li>
	{/foreach}
	</ul>
</div>
{/if}