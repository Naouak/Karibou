<div class="gmail">
{if $config == false}
	<p>##NOCONFIG##</p>
{elseif $title == 'Failed'}
	<h1>##ERROR##</h1>
	<p>##LOADFAILED##</p>
{else}
	<h1>{$title}</h1>
	{if $nbMessages == 0}
	<p>##NOMESSAGE##</p>
	{else}
	<h2>{$nbMessages} {if $nbMessages == 1} ##UNREADMESSAGE## {else} ##UNREADMESSAGES## {/if}</h2>
	<ul class="big">
		<li class="header">
			<span class="gmailauthor">##SENDER##</span>
			<span class="gmailtitle">##SUBJECT##</span>
		</li>
		{include file="_maillist.tpl" maxauthorlength="30" maxtitlelength="60"}
	</ul>
	{/if}
{/if}
	<p><a href="{kurl page="config"}">##LINKCONFIG##</a></p>
</div>
