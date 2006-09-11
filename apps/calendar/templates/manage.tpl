<h1>##MANAGE##</h1>
<div class="helper">
	##LOGOUT_TO_SAVE##
</div>

<p>
	<a href="{kurl page="addCalendar"}"><strong>##ADDCALENDAR##</strong></a>
</p>

<h3>##Public Calendars##</h3>
<ul>
{foreach item=cal from=$public_cals}
	<li>{$cal->getName()} <a href="{kurl action='subscribe'}?id={$cal->getId()}"><strong>##subscribe##</strong></a></li>
{/foreach}
</ul>

<h3>##Subscribed Calendars##</h3>
<ul>
{foreach item=cal from=$subscribed_cals}
	<li>{$cal->getName()} <a href="{kurl action='unsubscribe'}?id={$cal->getId()}">##unsubscribe##</a></li>
{/foreach}
</ul>

<h3>##Admin Calendars##</h3>
<ul>
{foreach item=cal from=$admin_cals}
	<li>{$cal->getName()} <a href="{kurl action='subscribe'}?id={$cal->getId()}"><strong>##subscribe##</strong></a> <a href="{kurl page='editCalendar' calendarid=$cal->getId()}">##edit##</a></li>
{/foreach}
</ul>
