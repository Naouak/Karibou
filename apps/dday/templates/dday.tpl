{if !isset($DDempty)}
	<table>
	{foreach from=$ddaylist key=key item=dd}
				{if $dd.desc}<tr onmouseover="showhint('{$dd.desc|escape:'hexentity'}', 'hint_profile')" onmouseout="hidehint()" class="userlink">
				{/if}
				<td>{if $dd.link}<a target="_blank" href="{$dd.link}">{$dd.event}</a>{else}{$dd.event}{/if}<br /><font size="1">{$dd.date}</font></td>
				<td width="15%" valign="top"><b>
{if $dd.JJ>0} {t}D{/t}-{$dd.JJ}
{else} <font color="red">{t}D{/t}</font>
{/if}
				</b></td>
				</tr>
	{/foreach}
	</table>
{/if}

