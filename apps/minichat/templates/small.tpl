	<script type="text/javascript" language="javascript">
	// <![CDATA[

{literal}
	function submit_mc_form(form_id, content_id)
	{
		var f = document.getElementById(form_id);
		inputList = f.getElementsByTagName('input');
		var queryComponents = new Array();
		for( i=0 ; i < inputList.length ; i++ )
		{
			myInput = inputList.item(i);
			if( myInput.type == 'file' ) return true;
			if( myInput.name )
			{
				queryComponents.push(
	        	  encodeURIComponent(myInput.name) + "=" +
	        	  encodeURIComponent(myInput.value) );
	        	 
	        	myInput.value = "";
			}
		}

		var post_vars = queryComponents.join("&");

		new Ajax.Updater(content_id, '{/literal}{kurl app="minichat" page="content" pagenum=$pagenum maxlines=$maxlines userichtext=$userichtext}{literal}', {
				asynchronous:true,
				evalScripts:true,
				method:'post',
				postBody:post_vars
			});

		return false;
	}
{/literal}
	// ]]>
	</script>
	
	<script type="text/javascript" language="javascript">
	// <![CDATA[
if (window.minichat_ajax_updater !== undefined) {ldelim}
	window.minichat_ajax_updater.stop();
{rdelim}
window.minichat_ajax_updater = new Ajax.PeriodicalUpdater('minichat_live', '{kurl app="minichat" page="content" pagenum=$pagenum maxlines=$maxlines userichtext=$userichtext}', {ldelim}asynchronous:true, evalScripts:true, frequency:{$config.refresh.small}{rdelim});
	// ]]>
	</script>


<h3 class="handle">Mini Chat</h3>
<div class="minichat">
	<div id="minichat_live">
		{include file="content.tpl"}
	</div>
    {if $permission > _READ_ONLY_}
    <form action="{kurl action="post"}" method="post" id="minichat_live_form" onsubmit="return submit_mc_form('minichat_live_form', 'minichat_live');">
        <input type="text" name="post" id="message" 
class="minichatMessage" />
        <input type="submit" value="##MINICHAT_SEND##" class="button" />
    </form>
    {/if}
    <div class="chathistorylink"><a href="{kurl app="minichat" page=""}">##VIEWCHATHISTORY##...</a></div>
</div>
