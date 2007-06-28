	<script type="text/javascript" language="javascript">
	// <![CDATA[
<? /*Cant use class name hide or hidden (using dontshow)... IE seems to react to
special classname... (sucks)*/ ?>
var inittitle = document.title;
var fmtoggle=0;
var intv;


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


function blinktitle(title){
	if(fmtoggle == 0){
		document.title = title;
		fmtoggle=1;
	}else{
		document.title = inittitle;
		fmtoggle=0;
	}	

}
	function flashmail_headerbox_update()
	{
		new Ajax.Updater('account_flashmail_headerbox_full', '<?=kurl(array('app'=>"flashmail", 'page'=>"account_headerbox_content"));?>', {asynchronous:true, evalScripts:false, onComplete:flashmail_duplicate_unreadlist});
		return false;
	}

	function flashmail_duplicate_unreadlist()
	{
		var test = document.getElementById("account_flashmail_read_link");
		if(test){
			intv = setInterval('blinktitle("Nouveau flashmail")',1000);
		}else{
			document.title=inittitle;
			clearInterval(intv);
		}

		//Utilisation d'une recopie du innerHTML en javascript pour éviter d'exécuter 2 fois le même code pour 
		//l'affichage du nombre de flashmails et de la liste des flashmails (depuis que les 2 éléments ne sont
		//plus au même endroit.
		document.getElementById('flashmail_headerbox_unreadlist').innerHTML = document.getElementById('flashmail_headerbox_unreadlist_TMP').innerHTML;

		if (document.getElementById('flashmail_headerbox_unreadlist_TMP').innerHTML.length < 10) {
			document.getElementById('flashmail_headerbox_unreadlist').className = "flashmail dontshow";
		}
	}

	// ]]>
	</script>

	<script type="text/javascript" language="javascript">
	// <![CDATA[
		setInterval("flashmail_headerbox_update()", 40000);
	// ]]>
	</script>
	
	
	<script type="text/javascript" language="javascript">
	// <![CDATA[
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

		new Ajax.Updater(content_id, '<?=kurl(array('app'=>"flashmail", 'page'=>"send"));?>', {
				asynchronous:true,
				evalScripts:true,
				method:'post',
				postBody:post_vars
			});

		document.getElementById('flashmail_headerbox_answer').innerHTML= '<?=_('LOADING');?>';

		flashmail_headerbox_update()
	
		return false;
	}
	// ]]>
	</script>

<div id="account_flashmail_headerbox_full">
<? include("account_headerbox_content.php"); ?>
</div>
