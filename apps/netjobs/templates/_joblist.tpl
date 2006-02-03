		<ul class="joblist">
	{foreach from=$myJobs item="myJob"}
			<li>
				<span class="title"><a href="{kurl page="jobdetails" jobid=$myJob->getInfo("id")}">{if ($myJob->getInfo("title") != "")}{$myJob->getInfo("title")}{else}##JOB_EMPTYTITLE##{/if}</a></span>
				{*<span class="type">{$myJob->getInfo("description")|truncate:30:"..."}&nbsp;</span>
				<span class="datetime">{$myJob->getInfo("datetime")}&nbsp;</span>*}
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
				<span class="">
					{$myJob->getCompanyInfo("name")}
				</span>
			</li>
	{/foreach}
		</ul>