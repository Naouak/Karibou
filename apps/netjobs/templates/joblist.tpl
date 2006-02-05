<h1>##NETJOBS_TITLE##</h1>
<h3>{if isset($myCompany)}##NETJOBS_JOBLIST_BY_COMPANY## &quot;{$myCompany->getInfo("name")}&quot;{else}##NETJOBS_JOBLIST##{/if}</h3>
<div class="netjobs">
	<a href="{kurl page="jobedit"}" class="addjob"><span>##JOBADD##</span></a>
	{include file="_joblist.tpl"}
	
	{assign var="nbpages" value=$jobcount/$maxjobs}
	{if $nbpages > 1}
		{section name=jobs loop=($jobcount/$maxjobs) start=1 step=1}
			<a href="{kurl pageid=$smarty.section.jobs.iteration}">{$smarty.section.jobs.iteration}a</a>
		{/section}
	{/if}
</div>