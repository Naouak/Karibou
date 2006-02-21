<h1>##NETJOBS_TITLE##</h1>
<h3>
	{if isset($myCompany)}
		##NETJOBS_JOBLIST_BY_COMPANY## &quot;{$myCompany->getInfo("name")}&quot;
	{elseif isset($search)}
		##NETJOBS_JOBLIST_FOR_SEARCH## &quot;{$search}&quot;
	{else}
		##NETJOBS_JOBLIST##
	{/if}
</h3>
<div class="netjobs">
	<strong>{$jobcount}</strong> {if $jobcount > 1}##NETJOBS_JOBLIST_OFFERSFOUND##{else}##NETJOBS_JOBLIST_OFFERFOUND##{/if}

	{assign var="nbpages" value=$jobcount/$maxjobs}
	{if $nbpages > 1}
		{if $pagenum > 1}
			<a href="{kurl page="joblist" maxjobs=$maxjobs pagenum=$pagenum-1 companyid=$companyid search=$search}">&lt;&lt; ##NETJOBS_JOBLIST_PREVIOUSPAGE##</a>
		{/if}
		&nbsp;&nbsp;&nbsp;&nbsp;##PAGE## #{$pagenum}&nbsp;&nbsp;&nbsp;&nbsp;
		{if ($pagenum < $nbpages)}
			<a href="{kurl page="joblist" maxjobs=$maxjobs pagenum=$pagenum+1 companyid=$companyid search=$search}">##NETJOBS_JOBLIST_NEXTPAGE## &gt;&gt;</a>
		{/if}
		{section name=jobs loop=($jobcount/$maxjobs) start=1 step=1}
			<a href="{kurl pageid=$smarty.section.jobs.iteration}">{$smarty.section.jobs.iteration}a</a>
		{/section}
	{/if}

	{include file="_joblist.tpl"}
	
	<a href="{kurl page="jobedit"}" class="addjob"><span>##JOBADD##</span></a>
</div>