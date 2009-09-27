BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Karibou/dday//NONSGML Jours J//EN
X-WR-CALNAME:Jours J
X-WR-TIMEZONE:UTC
X-WR-CALDESC:Les jours J du Karibou de TL1

{if !isset($DDempty)}
{foreach from=$ddaylist key=key item=dd}
BEGIN:VEVENT
DTSTART;VALUE=DATE:{$dd.vcalDateStart}
DTEND;VALUE=DATE:{$dd.vcalDateEnd}
{if $dd.link}URL:{$dd.link}{/if}
SUMMARY:{$dd.event}{if $dd.desc} - {$dd.desc}{/if}

END:VEVENT
{/foreach}
{/if}

END:VCALENDAR
