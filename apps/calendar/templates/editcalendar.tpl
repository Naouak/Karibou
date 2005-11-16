<h1>##CALENDAR##</h1>
{include file="messages.tpl"}
<h2>{if isset($event)}##EDITCALENDAR##{else}##ADDCALENDAR##{/if}</h2>
{if !isset($calendar) && isset($calendarid)}(##ERROR_SO_ADDING##){/if}

<form action="{kurl page="saveCalendar"}" method="post">
{if isset($calendar)}
	<input type="hidden" name="calendarid" value="{$calendar->getId()}" />
{/if}
	<label for="name">##CALENDARNAME## : </label>
	<input type="text" name="name" id="name" value="{if isset($calendar)}{$calendar->getName()}{/if}" />
	<br />
	<label for="destination">##CALENDARDEST## : </label>
	<select name="destination" id="destination">
		<option value="0">Perso</option>
{foreach item=group from=$grouplist}
		<option value="{$group->getid()}">{$group->getName()} (group)</option>
{/foreach}
	</select>
	<br />
	<label for="type">##CALENDARTYPE## : </label>
	<select name="type" id="type">
		<option value="private" {if isset($calendar) && $calendar->getType() == 'private'}SELECTED{/if}>##PRIVATE##</option>
		<option value="public" {if isset($calendar) && $calendar->getType() == 'public'}SELECTED{/if}>##PUBLIC##</option>
	</select>
	<br />
	<input type="submit" value="##SAVECALENDAR##" />
	(<a href="{kurl page=""}">##CANCEL##</a>)
</form>