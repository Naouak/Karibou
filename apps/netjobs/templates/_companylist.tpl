		<ul class="companylist">
	{foreach from=$myCompanies item="myCompany"}
			<li>
				<span class="name"><a href="{kurl page="companydetails" companyid=$myCompany->getInfo("id")}">{$myCompany->getInfo("name")|truncate:30:"..."}</a></span>
				<span class="2description">{$myCompany->getInfo("description")|truncate:30:"..."}</span>
				{$myCompany->getInfo("joboffers")}
				{*
				<span class="ago">
					##AGO1##
					{assign var="since" value=$myCompany->getSecondsSinceLastUpdate()}
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