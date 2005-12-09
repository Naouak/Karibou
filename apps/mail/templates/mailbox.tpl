<h1>##TITLE## ##FOR## {$username}</h1>
<h2>##MAILBOX_VIEW##</h2>
{if $loggedin}
{include file="menumail.tpl"}
<div id="maillist">
{include file="maillist.tpl"}
</div>
{else}
{$message}
{/if}
