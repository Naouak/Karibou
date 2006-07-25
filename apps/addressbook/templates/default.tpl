<h1>##TITLE##</h1>
<div class="helper">
	##ADDRESSBOOK_DESCRIPTION##
</div>
<h3>##CONTACT_LIST##</h3>
<ul>
{foreach item=addr from=$addresses}
	<li>
		<a href="{kurl page='' profile_id=$addr.id }">{$addr.firstname} {$addr.lastname}</a>
		(<a href="{kurl page='' profile_id=$addr.id act='edit'}">##ADDRESSBOOK_EDIT_LINK##</a>)
	</li>
{/foreach}
</ul>