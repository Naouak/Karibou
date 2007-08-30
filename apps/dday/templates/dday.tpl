<h3 class="handle">##DDAY##</h3>
{if !isset($DDempty)}
	<table>
	{foreach from=$ddaylist key=key item=dd}

				<tr>
				<td>{$dd.event}<br /><font size="1">{$dd.date}</font></td>
				<td WIDTH="15%" VALIGN=TOP><b>##DORJ##-{$dd.JJ}</b></td>
				</tr>

	{/foreach}
	</table>
{/if}

