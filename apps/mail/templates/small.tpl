<h3 class="handle">Mail</h3>
<div class="mail">
	{if $messagecount >= 0}
		<div class="messagesnb">
			<a href="mail/">{if $messagecount == 1}1 ##MESSAGEININBOX##{elseif $messagecount > 1}{$messagecount} ##MESSAGESININBOX##{else}##NOMESSAGEININBOX##{/if}</a><br />
		</div>

		{if $quota}
			<div class="quota">
				<div class="bar" style="width: {$quota.usage/$quota.limit*100|@round}%">
					Quota : <strong>{$quota.usage/1000|@round}MB</strong> / {$quota.limit/1000|@round}MB
				</div>
			</div>
		{/if}
	
		<table>
			<thead>
				<tr>
					<th>##FROM## - ##SUBJECT##</th>
					<th class="date">##Date##</th>
				</tr>
			</thead>
			<tbody>
		{foreach item=header from=$messageheaders}
			{if $header->deleted}
					<tr class="deleted">
			{elseif ! $header->seen}
					<tr class="unread">
			{elseif $header->answered}
					<tr class="replied">
			{else}
					<tr class="read">
			{/if}
						<td class="message">
							<!-- <input type="checkbox" name="delmsg{$header->uid}" value="{$header->uid}" /> -->
						
							<a href="{kurl page='msg' mailbox="INBOX" uid=$header->uid}">{$header->from}</a> - 
							<a href="{kurl page='msg' mailbox="INBOX" uid=$header->uid}">{$header->subject|truncate:40}</a>
						</td>
						<td class="date">
							{$header->date|date_format:"%d-%b"}
						</td>
					</tr>
		{/foreach}
			</tbody>
		</table>
	{/if}
</div>
