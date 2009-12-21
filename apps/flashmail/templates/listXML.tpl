<?xml version="1.0" encoding="UTF-8" ?>

<flashmails>{foreach item=flashmail from=$flashmails}
    {if $flashmail->isArchive()}
    <flashmail class="archive" id="{$flashmail->getId()}">
    {elseif !$flashmail->isRead()}
    <flashmail class="unread" id="{$flashmail->getId()}">
    {elseif $flashmail->isRead()}
    <flashmail class="read" id="{$flashmail->getId()}">
    {/if}
        <date full="{$flashmail->getInfo("date")|date_format:"%A %d %B %Y @ %H:%M.%S"}" short="{$flashmail->getInfo("date")|date_format:"%H:%M"}" />
        <author id="{$flashmail->getAuthorId()}" link="{kurl app='annuaire' username=$flashmail->getAuthorLogin()}" name="{$flashmail->getAuthorDisplayName()|escape}" />
        <message><![CDATA[{$flashmail->getMessage()|escape:'html'}]]></message>
        <oldmessage><![CDATA[{$flashmail->getOldMessage()|escape:'html'}]]></oldmessage>
    </flashmail>
{/foreach}</flashmails>
