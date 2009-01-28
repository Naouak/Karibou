<h3 class="handle">##CALENDAR##</h3>
<table summary="calendar" class="calendar_month_mini" cellspacing="0" cellpadding="0">
	<caption>
		<a href="{kurl app="calendar" page="" year=$previousMonth->getYear() month=$previousMonth->getMonth() day=$currentDate->getDay()}" title="{$previousMonth->getTime()|date_format:"%B %Y"}">
			&#171;
		</a>
		{$currentDate->getDate("%B %Y")}
		<a href="{kurl app="calendar" page="" year=$nextMonth->getYear() month=$nextMonth->getMonth() day=$currentDate->getDay()}" title="{$nextMonth->getDate("%B %Y")}">
		&#187;
		</a>
	</caption>
	<thead>
		<tr>
			<th><abbr title="Week">W</abbr></th>
			{section name=i loop=$weekDayName}
			<th scope="col"><abbr title="{$weekDayName[i].name}">{$weekDayName[i].abbr|truncate:2:""}</abbr></th>
			{/section}
		</tr>
	</thead>
	<tbody>
{foreach name=days item=day from=$days}
{if $smarty.foreach.days.first}
		<tr>
			<td class="weekofyear">
				{*<a href="{kurl app="calendar" year=$day.date->getYear() week=$day.date->getWeekOfYear()}" title="##WEEK## {$day.date->getWeekOfYear()}">*}{$day.date->getWeekOfYear()}{*</a>*}
			</td>
{elseif $smarty.foreach.days.index % 7 == 0}
		</tr>
		<tr>
			<td class="weekofyear">
				{*<a href="{kurl app="calendar" page="" year=$day.date->getYear() week=$day.date->getWeekOfYear()}" title="##WEEK## {$day.date->getWeekOfYear()}">*}{$day.date->getWeekOfYear()}{*</a>*}
			</td>
{/if}
{if ($day.date->getMonth()!=$currentDate->getMonth())}
			<td{if ($day.date->isToday())} class="today"{else} class="othermonth"{/if}>
				{$day.date->getDay()}
			</td>
{else}
			<td{if ($day.date->isToday())} class="today"{elseif ($day.events)} class="eventday"{/if}>
				<a href="{kurl app="calendar" page="" year=$day.date->getYear() month=$day.date->getMonth() day=$day.date->getDay()}" title="{$day.date->getDate("%A %d %B %Y")}">{$day.date->getDay()}</a>
			</td>
{/if}
{if $smarty.foreach.days.last}
		</tr>
{/if}
{/foreach}
	</tbody>
</table>