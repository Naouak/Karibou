		<ul class="companylist">
	{foreach from=$myCompanies item="myCompany"}
			<li>
				<span class="name"><a href="{kurl page="companydetails" companyid=$myCompany->getInfo("id")}">{$myCompany->getInfo("name")|truncate:30:"..."}</a></span>
				<span class="nboffers">{if $myCompany->getInfo("joboffers")>1}{$myCompany->getInfo("joboffers")} ##JOB_OFFER####S##{elseif $myCompany->getInfo("joboffers") == 1}{$myCompany->getInfo("joboffers")} ##JOB_OFFER##{else}##JOB_NOOFFER##{/if}</span>
				<br class="clear" />
			</li>
	{/foreach}
		</ul>