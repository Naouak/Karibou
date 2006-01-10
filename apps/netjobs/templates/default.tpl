<h1>##NETJOBS_TITLE##</h1>
<h3>##NETJOBS_JOBLIST##</h3>
<div class="netjobs">
	<a href="{kurl page="jobedit"}">##JOBADD##</a>

	<div class="jobs">
		<h4>##LASTJOBS##</h4>
		{include file="_joblist.tpl"}
		<a href="{kurl page="joblist"}">##JOB_COUNT1## {$jobcount} ##JOB_COUNT2##</a>
	</div>
	
	<div class="companies">
		<h4>##COMPANIES##</h4>
		<ul>
	{foreach from=$myCompanies item="myCompany"}
			<li>
				<span class="name"><a href="{kurl page="companydetails" companyid=$myCompany->getInfo("id")}">{$myCompany->getInfo("name")|truncate:30:"..."}</a></span>
				<span class="description">{$myCompany->getInfo("description")|truncate:30:"..."}</span>
				<span class="datetime">{$myCompany->getInfo("datetime")}&nbsp;</span>
			</li>
	{/foreach}
		</ul>
	</div>
</div>