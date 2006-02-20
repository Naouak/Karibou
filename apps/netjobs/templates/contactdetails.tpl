<h1>##NETJOBS_TITLE##</h1>
<h3>##JOB_CONTACTDETAILS##</h3>
<div class="netjobs">
	<div  class="contactdetails">
		{include file="_contactdetails.tpl"}
	
		{if ($myContact->canUpdate())}
		<div class="toolbox">
			<a href="{kurl app="addressbook" page="editnj" profile_id=$myContact->getProfileInfo("id")}" class="edit"><span>##CONTACTEDIT##</span></a>
			{*
			{if ($myContact->canWrite())}
			<form method="post" action="{kurl page="contactsave"}" id="deletecontact" name="delete">
				<input type="hidden" name="contactiddelete" value="{$myContact->getProfileInfo("id")}">
				<a href="#" onclick="if(confirm('##COMPANYDELETE_ASKIFSURE##')){ldelim}document.getElementById('deletecontact').submit(){rdelim}else{ldelim}return false;{rdelim};" class="delete"><span>##CONTACTDELETE##</span></a>
			</form>
			{/if}
			*}
		</div>
		{/if}
	</div>
</div>