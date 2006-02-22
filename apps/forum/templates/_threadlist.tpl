		<ul class="messagelist">
	{foreach from=$myMessages item="myMessage"}
			<li>
				<span class="title"><a href="{kurl page="discussiondetails" forumid=$myForum->getInfo("id") messageid=$myMessage->getId()}">{if ($myJob->getInfo("title") != "")}{$myJob->getInfo("title")}{else}##JOB_EMPTYTITLE##{/if}</a></span>
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
				<span class="company">
				{if $myJob->getCompanyInfo("id") !== FALSE}
					##JOB_IN## <a href="{kurl page="companydetails" companyid=$myJob->getCompanyInfo("id")}">{$myJob->getCompanyInfo("name")}</a>
				{else}
					##JOB_IN## <em>##COMPANYUNKNOWN##</em>
				{/if}
				</span>
			</li>
	{/foreach}
		</ul>