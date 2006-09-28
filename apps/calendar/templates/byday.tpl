<h1>##DAYVIEW## : {$today->getDate("%c")}</h1>
{include file="messages.tpl"}
{if ($cals|@count == 0)}
<div class="helper">
##NOCALENDAR##
</div>
{/if}
<script>
var start_date = {ldelim}
	year: '{$today->getYear()}',
	month: '{$today->getMonth()}',
	day: '{$today->getDay()}'
{rdelim}
var stop_date = {ldelim}
	year: '{$today->getYear()}',
	month: '{$today->getMonth()}',
	day: '{$today->getDay()}'
{rdelim}

function ctrl_dates_start()
{ldelim}
	var f = document.forms[0];
	f.enddateDay.value 		= f.startdateDay.value;
	f.enddateMinute.value 	= f.startdateMinute.value;
	f.enddateHour.value 	= f.startdateHour.value;
	f.enddateYear.value 	= f.startdateYear.value;
	f.enddateMonth.value 	= f.startdateMonth.value;
{rdelim}

function close_editinline_popup()
{ldelim}
	document.getElementById("divEditEvent").innerHTML = "";
	return false;
{rdelim}

</script>
<script type="text/javascript" src="/themes/js/calendar.js"></script>


<div id="calendar_sidebar">
{*
<a href="{kurl page='manage'}">##MANAGE##</a><br />
<br />
<a href="{kurl page='addEvent'}">##ADDEVENT##</a><br />
*}
{include file="small.tpl"}
<h3>##NEXTDAYEVENTS##</h3>
<ul>
{foreach item=event from=$nextday_events}
	<li>
{if $event->parent}
				<span class="event_hours">{$event->startdate|date_format:"%x %Hh%M"} -
				{$event->stopdate|date_format:"%x %Hh%M"}</span>
{else}
				<span class="event_hours">{$event->startdate|date_format:"%Hh%M"} -
				{$event->stopdate|date_format:"%Hh%M"}</span>
{/if}
		{$event->summary}
	</li>
{/foreach}
</ul>

{if $cals|@count > 0}
<fieldset>
	<legend>##CALENDAR_COLORS##</legend>
	<ul style="list-style: none; padding: 0px; margin: 2px; padding-bottom: 10px; font-size: 0.8em;">
	{foreach from=$cals item="cal"}
		<li style="clear: both;">
			<div class="colorsquare" style="background-color: #{$cal->getColor()};"></div>
			<div style="float: left;">{$cal->getName()}</div>
			<br style="clear: both;" />
		</li>
	{/foreach}
	</ul>
</fieldset>
{/if}

</div>

<div id="calendar">

<div id="calendar-hours" ></div>
<div id="calendar-select" ></div>

<script>
	KCalendar.createHours("calendar-hours");
	KCalendar.createSelect("calendar-select", "{kurl page='addEventInline'}");
</script>

<div class="events">
{foreach name=col item=events from=$columns}
{if !$smarty.foreach.col.first}
<div class="newcolumn_{$colnum}">
{/if}
{foreach item=event from=$events}
	<div class="event event_size_{$colnum} start_{$event->start_class} size_{$event->size_class} blue" style="background-color: #{$event->getCalendarColor()}">
	<div class="event_box event_size_{$colnum} start_{$event->start_class} size_{$event->size_class} lightblue" style="background-color: #{$event->getCalendarColor(2)}">
		<div class="event_content light_blue" >
			<h3>{$event->summary}</h3>
			<p>{$event->getDescriptionXHTML()}</p>
			<p>
{if $event->parent}
				<span class="event_hours">{$event->startdate|date_format:"%x %Hh%M"} -
				{$event->stopdate|date_format:"%x %Hh%M"}
				{if $event->location} - [{$event->location}]{/if}
				</span>
{else}
				<span class="event_hours">{$event->startdate|date_format:"%Hh%M"} -
				{$event->stopdate|date_format:"%Hh%M"}
				{if $event->location} - [{$event->location}]{/if}
				</span>
{/if}
				<br />
{if $event->admin}
				<a href="{kurl page='editEvent' calendarid=$event->calendarid eventid=$event->uid}">##EDITEVENT##</a>
				<form action="{kurl page='deleteEvent'}" method="POST" name="deleteEvent{$event->uid}">
                    <input type="hidden" name="calendarid" value="{$event->calendarid}" />
                    <input type="hidden" name="eventid" value="{$event->uid}" />
                    <a href="#" onClick="confirm('##DELETEEVENT_JS##')&&document.deleteEvent{$event->uid}.submit()&&false; return false;">##DELETEEVENT##</a>
				</form>
{/if}
			</p>
		</div>
	</div>
	</div>
{/foreach}
{/foreach}
{section name=fin start=1 loop=$smarty.foreach.col.iteration}
</div>
{/section}
</div>

</div>

<div id="divEditEvent" class="hide"></div>
