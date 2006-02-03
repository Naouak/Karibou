<h1>##NETJOBS_TITLE##</h1>
<h3>##NETJOBS_JOBLIST##</h3>
<div class="netjobs">
	<div class="jobs">
		<h4><span>##LASTJOBS##</span></h4>
		{include file="_joblist.tpl"}
		<a href="{kurl page="joblist"}" class="viewalljobs"><span>##JOB_COUNT1## {$jobcount} ##JOB_COUNT2##</span></a>
		<a href="{kurl page="jobedit"}" class="addjob"><span>##JOBADD##</span></a>
	</div>
	
	<div class="companies">
		<h4><span>##COMPANIES##</span></h4>
		<ul class="companylist">
	{foreach from=$myCompanies item="myCompany"}
			<li>
				<span class="name"><a href="{kurl page="companydetails" companyid=$myCompany->getInfo("id")}">{$myCompany->getInfo("name")|truncate:30:"..."}</a></span>
				<span class="2description">{$myCompany->getInfo("description")|truncate:30:"..."}</span>
				<span class="datetime">{$myCompany->getInfo("datetime")}&nbsp;</span>
			</li>
	{/foreach}
		</ul>
	</div>
	
	<hr class="clear">
</div>