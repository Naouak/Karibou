<div id="calendrier">
	<table summary="calendrier">
		<caption><a href="{kurl app="calendrier" page="" YearArgument=$previousMonth->getYear() MonthArgument=$previousMonth->format('%m')}" title="{$previousMonth->format('%B %Y')}">&#171;</a>{$title}<a href="{kurl app="calendrier" page="" YearArgument=$nextMonth->getYear() MonthArgument=$nextMonth->format('%m')}" title="{$nextMonth->format('%B %Y')}">&#187;</a></caption>
		<thead>
			<tr>
				{section name=i loop=$daysOfTheWeek}
				<th scope="col"><abbr title="{$daysOfTheWeek[i].name}">{$daysOfTheWeek[i].abbr}</abbr></th>
				{/section}
			</tr>
		</thead>
		<tbody>
			{section name=i loop=$day}
				{if $smarty.section.i.first}
				<tr>
				{elseif $smarty.section.i.index % 7 == 0}
				</tr><tr>
				{/if}
					<td>{$day[i]->getDay()}</td>
				{if $smarty.section.i.last}
				</tr>
				{/if}
			{/section}
		</tbody>
	</table>
</div>