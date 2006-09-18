<h1>##MANAGE##</h1>
<div class="helper">
	##CALENDAR_MUST_SUBSCRIBE##
</div>

{if $permission > _READ_ONLY_}
<p>
	<a href="{kurl page="addCalendar"}"><strong>##ADDCALENDAR##</strong></a>
</p>
{/if}

{if $public_cals|@count > 0}
<br />
<h3>##Public Calendars##</h3>
<ul style="list-style: none;">
{foreach item=cal from=$public_cals}
	<li style="margin-top:10px;"><div class="colorsquare" style="margin: 2px;background-color: #{$cal->getColor()};"></div>
	{$cal->getName()} <a href="{kurl action='subscribe'}?id={$cal->getId()}"><strong>##subscribe##</strong></a></li>
{/foreach}
</ul>
{/if}

<br /><br />

{if $subscribed_cals|@count > 0}
<h3>##Subscribed Calendars##</h3>
<ul style="list-style: none;">
{foreach item=cal from=$subscribed_cals}
	<li style="margin-top:10px;"><div class="colorsquare" style="margin: 2px;background-color: #{$cal->getColor()};"></div>
	{$cal->getName()} <a href="{kurl action='unsubscribe'}?id={$cal->getId()}">##unsubscribe##</a></li>
{/foreach}
</ul>
{/if}

<br /><br />

{if $admin_cals|@count > 0}
<h3>##Admin Calendars##</h3>
<ul style="list-style: none;">
{foreach item=cal from=$admin_cals}
	<li style="margin-top:10px;"><div class="colorsquare" style="margin: 2px;background-color: #{$cal->getColor()};"></div>
	{$cal->getName()} <a href="{kurl action='subscribe'}?id={$cal->getId()}"><strong>##subscribe##</strong></a> <a href="{kurl page='editCalendar' calendarid=$cal->getId()}">##edit##</a></li>
{/foreach}
</ul>
{/if}
<br />