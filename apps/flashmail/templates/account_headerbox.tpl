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
			if (document.getElementById(div_id).innerHTML.length > 10)
			{
				f.className = "flashmail showed";
			}
		}
		return false;
	}

	function flashmail_headerbox_update()
	{
		new Ajax.Updater('account_flashmail_headerbox_full', '{/literal} {kurl app="flashmail" page="account_headerbox_content"}{literal} ', {asynchronous:true, evalScripts:false, onComplete:flashmail_duplicate_unreadlist});
		return false;
	}
	
	function flashmail_duplicate_unreadlist()
	{
		//Utilisation d'une recopie du innerHTML en javascript pour �viter d'ex�cuter 2 fois le m�me code pour 
		//l'affichage du nombre de flashmails et de la liste des flashmails (depuis que les 2 �l�ments ne sont
		//plus au m�me endroit.
		document.getElementById('flashmail_headerbox_unreadlist').innerHTML = document.getElementById('flashmail_headerbox_unreadlist_TMP').innerHTML;

		if (document.getElementById('flashmail_headerbox_unreadlist_TMP').innerHTML.length < 10) {
			document.getElementById('flashmail_headerbox_unreadlist').className = "flashmail dontshow";
		}
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

		document.getElementById('flashmail_headerbox_answer').innerHTML= '##LOADING##';

		flashmail_headerbox_update()
	
		return false;
	}
{/literal}
	// ]]>
	</script>

<div id="account_flashmail_headerbox_full">
{include file="account_headerbox_content.tpl"}
</div>