{foreach item=mbox from=$mailboxes}
{$mbox->name}{$mbox->delimiter}{$mbox->attributes}<br />
{/foreach}