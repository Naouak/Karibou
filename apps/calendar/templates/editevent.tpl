<h1>##CALENDAR##</h1>
{include file="messages.tpl"}
<h2>{if isset($event)}##EDITEVENT##{else}##ADDEVENT##{/if}</h2>
{*<a href="{kurl app="wiki" page="help"}">##TITLE_WIKI_SYNTAX##</a>*}
<a href="{kurl app="wiki" page="help"}" onclick="javascript:popup(this.href, 'wiki_help', '800', '900', '200', '200');return false;" >##TITLE_WIKI_SYNTAX##</a>
{if !isset($event) && isset($calendarid) && isset($eventid)}(##ERROR_SO_ADDING##){/if}
{if ($calendars|@count > 0)}
<form action="{kurl page="saveEvent"}" method="post">
	<fieldset class="largefieldset">
{if isset($event)}
	<input type="hidden" name="eventid" value="{$eventid}" />
	<input type="hidden" name="calendarid" value="{$calendarid}" />
{/if}
	<label for="calendarid">##CALENDAR##</label>
{if isset($event)}
		{foreach key=key item=calendar from=$calendars}
			{if ($calendarid == $calendar->getId())}<strong>[{$calendar->getCalendarType()}] {$calendar->getName()} ({$calendar->getType()})</strong>{/if}
		{/foreach}
{else}
	<select name="calendarid" id="calendarid">
		{foreach key=key item=calendar from=$calendars}
			<option value="{$calendar->getId()}">[{$calendar->getCalendarType()}] {$calendar->getName()} ({$calendar->getType()})</option>
		{/foreach}
	</select>
{/if}
	<br class="spacer" />
	<label for="summary">##SUMMARY##</label>
	<input type="text" name="summary" id="summary" value="{if isset($event)}{$event->summary}{/if}" />
	<br class="spacer" />
	<label for="description">##DESCRIPTION##</label>
	<textarea name="description" id="description">{if isset($event)}{$event->description}{/if}</textarea>
	<br class="spacer" />
	<label for="startdate">##STARTDATE##</label>
		{html_select_date all_extra='onchange="javascript:ctrl_dates();"'  day_extra='class="input_xs"' month_extra='class="input_m"' year_extra='class="input_s"' prefix="startdate" start_year="-1" end_year="+3" field_order="DMY" time=$event->startdate}{*time="YYYY-MM-DD"*}
		<span class="text">##AT##</span>
		{html_select_time hour_extra='class="input_xs"' minute_extra='class="input_xs"' prefix="startdate" use_24_hours=true minute_interval=15 display_seconds=false time=$event->startdate}
	<br class="spacer" />
	<label for="enddate">##ENDDATE##</label>
		{html_select_date day_extra='class="input_xs"' month_extra='class="input_m"' year_extra='class="input_s"' prefix="enddate" start_year="-1" end_year="+3" field_order="DMY" time=$event->stopdate}
		<span class="text">##AT##</span>
		{html_select_time hour_extra='class="input_xs"' minute_extra='class="input_xs"' prefix="enddate" use_24_hours=true minute_interval=15 display_seconds=false time=$event->stopdate}
{*
	<br class="spacer" />
	<label for="summary">##SPEAKER##</label>
	<input type="text" name="speaker" id="speaker" value="{if isset($event)}{$event->speaker}{/if}" />
*}
	<br class="spacer" />
	<label for="location">##LOCATION##</label>
	<input type="text" name="location" id="location" value="{if isset($event)}{$event->location}{/if}" />
	<br class="spacer" />
	<label for="category">##CATEGORY##</label>
	<select name="category" id="category">
		<option value="lecture" {if isset($event) && $event->category == "lecture"}SELECTED{/if}>##LECTURE##</option>
		<option value="conference" {if isset($event) && $event->category == "conference"}SELECTED{/if}>##CONFERENCE##</option>
		<option value="meeting" {if isset($event) && $event->category == "meeting"}SELECTED{/if}>##MEETING##</option>
		<option value="rendezvous" {if isset($event) && $event->category == "rendezvous"}SELECTED{/if}>##RENDEZVOUS##</option>
		<option value="party" {if isset($event) && $event->category == "party"}SELECTED{/if}>##PARTY##</option>
	</select>
	<br class="spacer" />
	<label for="priority">##PRIORITY##</label>
	<select name="priority" id="priority">
		<option value="1" {if isset($event) && $event->priority == "1"}SELECTED{/if}>##LOW##</option>
		<option value="2" {if isset($event)}{if $event->priority == "2"}SELECTED{/if}{else}SELECTED{/if}>##NORMAL##</option>
		<option value="3" {if isset($event) && $event->priority == "3"}SELECTED{/if}>##HIGH##</option>
	</select>
	<br class="spacer" />
	<label for="recurrence">##RECURRENCE##</label>
	<select name="recurrence" id="recurrence">
		<option value="" {if isset($event) && $event->recurrence == ""}SELECTED{/if}>##NONE##</option>
		<option value="D1 #0" {if isset($event) && $event->recurrence == "everyday"}SELECTED{/if}>##EVERYDAY##</option>
	</select>
	<br class="spacer" />
	<input class="button" type="submit" value="##SAVEEVENT##" />
	(<a href="{kurl page=""}">##CANCEL##</a>)
	</fieldset>
</form>
{else}
<div class="error">
	##NOCALENDAR##
	<br />
	##CANTADDEVENT##
</div>
{/if}