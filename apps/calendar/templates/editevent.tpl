	<script type="text/javascript" language="javascript">
	// <![CDATA[
{literal}
	function ctrl_dates_start()
	{
		var f = document.forms[0];
		f.enddateDay.value 		= f.startdateDay.value;
		f.enddateMinute.value 	= f.startdateMinute.value;
		f.enddateHour.value 	= f.startdateHour.value;
		f.enddateYear.value 	= f.startdateYear.value;
		f.enddateMonth.value 	= f.startdateMonth.value;
	}
	//Ajouter le contrôle des dates de fin (pour éviter les dates inversées)
{/literal}
	// ]]>
	</script>

<h1>##CALENDAR##</h1>
{include file="messages.tpl"}
<h2>{if isset($event)}##EDITEVENT##{else}##ADDEVENT##{/if}</h2>
{*<a href="{kurl app="wiki" page="help"}">##TITLE_WIKI_SYNTAX##</a>*}
<a href="{kurl app="wiki" page="help"}" onclick="javascript:popup(this.href, 'wiki_help', '800', '900', '200', '200');return false;" >##TITLE_WIKI_SYNTAX##</a>
{if !isset($event) && isset($calendarid) && isset($eventid)}(##ERROR_SO_ADDING##){/if}
{if ($calendars|@count > 0)}
<form action="{kurl page="saveEvent"}" method="post" name="editEvent">
{include file="editevent_form.tpl"}

	<input class="button" type="submit" value="##SAVEEVENT##" />
	(<a href="{kurl page=""}">##CANCEL##</a>)
</form>
{else}
<div class="error">
	##NOCALENDAR##
	<br />
	##CANTADDEVENT##
</div>
{/if}
