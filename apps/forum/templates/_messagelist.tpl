		<ul class="messagelist">
	{foreach from=$myMessages item="myMessage"}
			<li>
				<span class="title"><a href="{kurl page="messageedit" forumid=$myForum->getInfo("id") messageid=$myMessage->getInfo("id")}">{if ($myMessage->getInfo("subject") != "")}{$myMessage->getInfo("subject")}{else}##KF_MESSAGE_EMPTYTITLE##{/if}</a></span>
				{*
				<span class="ago">
					##AGO1##
					{assign var="since" value=$myJob->getSecondsSinceLastUpdate()}
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
				*}
			</li>
	{/foreach}
		</ul>