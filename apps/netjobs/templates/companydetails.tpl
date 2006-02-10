<h1>##NETJOBS_TITLE##</h1>
{if isset($myCompany) && $myCompany != FALSE}
<h3>##NETJOBS_COMPANYTITLE## &quot;{$myCompany->getInfo("name")}&quot;</h3>

<div class="netjobs">
	<div  class="companydetails">
		{include file="_companydetails.tpl"}
		
		{if ($myCompany->canUpdate())}
		<div class="toolbox">
			<a href="{kurl page="companyedit" companyid=$myCompany->getInfo("id")}" class="edit"><span>##COMPANYEDIT##</span></a>
			{if ($myCompany->canWrite())}
			<form method="post" action="{kurl page="companysave"}" id="deletecompany" name="delete">
				<input type="hidden" name="companyiddelete" value="{$myCompany->getInfo("id")}">
				<a href="#" onclick="if(confirm('##COMPANYDELETE_ASKIFSURE##')){ldelim}document.getElementById('deletecompany').submit(){rdelim}else{ldelim}return false;{rdelim};" class="delete"><span>##COMPANYDELETE##</span></a>
			</form>
			{/if}
		</div>
		{/if}
		
		<br class="clear" />
	</div>
</div>
{else}
##COMPANYNOTFOUND##
{/if}