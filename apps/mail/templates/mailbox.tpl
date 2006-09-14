<h1>##WEBMAIL_TITLE## ##FOR## {$username}</h1>
<div class="warning">
##MAIL_WEBMAILADVICE##
</div>
<h2>##MAILBOX_VIEW## : {$messagecount} ##messages##</h2>
{if $loggedin}
{include file="menumail.tpl"}
<div id="maillist">
{include file="maillist.tpl"}
</div>
{else}
{$message}
{/if}
