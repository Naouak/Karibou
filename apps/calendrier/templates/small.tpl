<h3>Calendrier</h3>
<table summary="calendrier">
	<caption><a href="{kurl app="calendrier" page="byDay" year=$previousMonth->getYear() month=$previousMonth->format('%m')}" title="{$previousMonth->format('%B %Y')}">&#171;</a>{$currentMonth->format('%B %Y')}<a href="{kurl app="calendrier" page="byDay" year=$nextMonth->getYear() month=$nextMonth->format('%m')}" title="{$nextMonth->format('%B %Y')}">&#187;</a></caption>
	<thead>
		<tr>
			<th><abbr title="Week">W</abbr></th>
			{section name=i loop=$weekDayName}
			<th scope="col"><abbr title="{$weekDayName[i].name}">{$weekDayName[i].abbr}</abbr></th>
			{/section}
		</tr>
	</thead>
	<tbody>
		{section name=i loop=$day}
			{if $smarty.section.i.first}
				<tr><td><a href="{kurl app='calendrier' page='byWeek' week=$day[i]->getWeekOfYear()}" title="{$day[i]->format('%A %d %B %Y')}">{$day[i]->getWeekOfYear()}</a></td>
			{elseif $smarty.section.i.index % 7 == 0}
				</tr><tr><td><a href="{kurl app='calendrier' page='byWeek' week=$day[i]->getWeekOfYear()}" title="{$day[i]->format('%A %d %B %Y')}">{$day[i]->getWeekOfYear()}</a></td>
			{/if}
			<td><a href="{kurl app="calendrier" page="byDay" year=$day[i]->getYear() month=$day[i]->getMonth() day=$day[i]->getDay()}" title="{$day[i]->format('%A %d %B %Y')}">{$day[i]->getDay()}</a></td>
			{if $smarty.section.i.last}
				</tr>
			{/if}
		{/section}
	</tbody>
</table>