BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN

{if !isset($DDempty)}
{foreach from=$ddaylist key=key item=dd}
BEGIN:VEVENT
DTSTART:{$dd.vcalDate}T000000Z
DTEND:{$dd.vcalDate}T235959Z
SUMMARY:{$dd.event}{if $dd.desc} - {$dd.desc}{/if}

END:VEVENT
{/foreach}
{/if}

END:VCALENDAR
