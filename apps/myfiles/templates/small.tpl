<script type="text/javascript" language="javascript">
// <![CDATA[

{literal}
function changeFolder(folderLink) {
	var targetFolder = encodeURIComponent(folderLink);
	new Ajax.Updater('myfiles_list', {/literal}'{kurl app="myfiles" page="list"}', {literal}{asynchronous:true, evalScripts:true, method: 'post', parameters: 'folder=' + targetFolder});
	return false;
}
{/literal}

// ]]>
</script>

<h3 class="handle">##MYFILES##</h3>
<div class="myfiles">
<div id="myfiles_list">
{include file="list.tpl"}
</div>
</div>
