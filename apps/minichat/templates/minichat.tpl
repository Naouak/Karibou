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

		new Ajax.Updater(content_id, '{/literal}{kurl app="minichat" page="content" pagenum=$pagenum maxlines=$max}{literal}', {
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


<h1>Minichat</h1>
<div class="minichat">
	
	<div id="minichat_live">
	{include file="content.tpl" max=$config.max.normal}	</div>

    {if $permission > _READ_ONLY_}
    
    <form action="{kurl action="post"}" method="post" id="minichat_live_form" onsubmit="return submit_mc_form('minichat_live_form', 'minichat_live');">
        <input type="text" name="post" size="40" id="message" />
        <input type="submit" value="##MINICHAT_SEND##" class="button" />
    </form>
    {/if}

    <p>
    <a href="{kurl page=""}" {*onclick="new Ajax.Updater('minichat_live', '{kurl app="minichat" page="content"}', {literal}{asynchronous:true, evalScripts:true}{/literal}); return false;"*}>##CURRENTCHAT##</a>
     -  
    ##LASTCHAT## :
    {section name=p loop=$pages}
        {if not $smarty.section.p.first}
         |
        {/if}
        <a href="{kurl pagenum="$pages[p]"}"{* onclick="new Ajax.Updater('minichat_live', '{if isset($pages[p]) && $pages[p] != ""}{kurl app="minichat" page="content" pagenum=$pages[p]}{else}{kurl app="minichat" page="content"}{/if}', {literal}{asynchronous:true, evalScripts:true}{/literal}); return false;"*}>{$pages[p]}</a>
    {/section}
    </p>

</div>