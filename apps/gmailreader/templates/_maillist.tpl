		{foreach item=item from=$items}
		<li>
			<span class="gmailauthor" title="{$item.emailAuthor}">{$item.author|truncate:$maxauthorlength}</span>
			<span class="ago">
					##AGO1##
					{assign var="since" value=$item.issued}
					{if $since > 86400}
						{$since/86400|string_format:"%d"} ##DAY##{if $since/86400|string_format:"%d" >= 2}##S##{/if}
					{elseif $since > 3600}
						{$since/3600|string_format:"%d"} ##HOUR##{if $since/3600|string_format:"%d" >= 2}##S##{/if}
					{elseif $since > 60}
						{$since/60|string_format:"%d"} ##MINUTE##{if $since/60|string_format:"%d" >= 2}##S##{/if}
					{else}
						{$since} ##SECOND##{if $since >= 2}##S##{/if}
					{/if}
					##AGO2##
			</span>
			<span class="gmailtitle"><a href="{$item.link}" title="{$item.summary}">{$item.title->text|truncate:$maxtitlelength:"...":true}</a></span>
		</li>
		{/foreach}
		{if $nomessage} 
		{$nomessage}
		{/if}