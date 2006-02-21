<h1>##NETJOBS_TITLE##</h1>
<h3>##NETJOBS_JOBLIST##</h3>
<div class="netjobs">
	<div class="jobs">
		<h4><span>##LASTJOBS##</span></h4>
		{include file="_joblist.tpl"}
		{if ($jobcount>1)}
		<a href="{kurl page="joblist"}" class="viewalljobs"><span>##JOB_COUNT1## {$jobcount} ##JOB_COUNT2##{if $jobcount>1}##S##{/if}</span></a>
		{/if}
		<a href="{kurl page="jobedit"}" class="addjob"><span>##JOBADD##</span></a>
		<form action="{kurl action="jobsearchredirect"}" method="post" class="searchjob">
			<input type="text" class="searchfield" name="jobsearch" />
			<input type="submit" value="##JOBSEARCH##" class="searchbutton" />
		</form>
	</div>
	
	<div class="companies">
		<h4><span>##COMPANIES##</span></h4>
{include file="_companylist.tpl"}
	</div>
	
	<br class="clear">
</div>