<br />
<a href="" title="back"
	onclick="new Ajax.Updater('{$id}_content', '{kurl page="miniappeditview" miniapp=$id}', {ldelim}asynchronous:true, evalScripts:true{rdelim}); return false;" >
	<span class="edit"><span class="text">back</span></span>
</a>

<form id="{$id}_form" method="POST" 
	action="{kurl action='configapp'}" enctype="multipart/form-data"
	onsubmit="return submit_form('{$id}_form', '{$id}_content')">
<input type="hidden" name="miniappid" value="{$id}" />
{hook name=$id}
<input type="submit" value="save" />
</form>