<div class="gmail">
{$title}
{if $config == false}
	<p>##NOCONFIG##</p>
	<p><a href="{kurl page="config"}">##LINKCONFIG##</a></p>
{elseif $title == 'Failed'}
	<h1>##ERROR##</h1>
	<p>##LOADFAILED##</p>
	<p><a href="{kurl page="config"}">##LINKCONFIG##</a></p>
{else}
	<h1>{$title}</h1>
	{if $nbMessages == 0}
	<p>##NOMESSAGE##</p>
	{else}
	<h2>{$nbMessages} {if $nbMessages == 1} ##UNREADMESSAGE## {else} ##UNREADMESSAGES## {/if}</h2>
	<ul>
	<li class="header"><span class="gmailauthor">##SENDER##</span><span class="gmailtitle">##SUBJECT##</span></li>
	{foreach item=item from=$items}
	<li><span class="gmailauthor" title="{$item.emailAuthor}">{$item.author}</span><span class="gmailtitle"><a href="{$item.link}" title="{$item.summary}">{$item.title->text|truncate:60}</a></span></li>
	{/foreach}
	</ul>
	{/if}
{/if}
</div>
