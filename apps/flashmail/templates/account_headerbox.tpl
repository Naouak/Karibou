	<script type="text/javascript" language="javascript">
	// <![CDATA[
{*Cant use class name hide or hidden (using dontshow)... IE seems to react to
special classname... (sucks)*}
{literal}
	function flashmail_blinddown(div_id)
	{
		var f = document.getElementById(div_id);
		if (f.className == "flashmail showed")
		{
		        f.className = "flashmail dontshow";
		}
		else
		{
		        f.className = "flashmail showed";
		}

		return false;
	}

	function flashmail_headerbox_update()
	{
		new Ajax.Updater('flashmail_headerbox_full', '{/literal} {kurl app="flashmail" page="headerbox_content"}{literal} ', {asynchronous:true, evalScripts:true});
		return false;
	}
{/literal}
	// ]]>
	</script>

	<script type="text/javascript" language="javascript">
	// <![CDATA[
		setInterval("flashmail_headerbox_update()", 40000);
	// ]]>
	</script>
	
	
	<script type="text/javascript" language="javascript">
	// <![CDATA[
{literal}
	function submit_flashmail_form(form_id, content_id)
	{
		var f = document.getElementById(form_id);

		var queryComponents = new Array();
		
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
			}
		}
	
		areaList = f.getElementsByTagName('textarea');
		for( i=0 ; i < areaList.length ; i++ )
		{
			myArea = areaList.item(i);
			if( myArea.name )
			{
				queryComponents.push(
					encodeURIComponent(myArea.name) + "=" + 
					encodeURIComponent(myArea.value)
				);
			}
		}

		var post_vars = queryComponents.join("&");

		new Ajax.Updater(content_id, '{/literal}{kurl app="flashmail" page="send"}{literal}', {
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
	
{include file="account_headerbox_content.tpl"}
