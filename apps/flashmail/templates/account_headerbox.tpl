{literal}
<script type="text/javascript" language="javascript">
	// <![CDATA[
/* Cant use class name hide or hidden (using dontshow)... IE seems to react to special classname... (sucks) */
var inittitle = document.title;
var fmtoggle=0;
var intv = null;

function blinktitle(title) {
	if (fmtoggle == 0) {
		document.title = title;
		fmtoggle = 1;
	} else {
		document.title = inittitle;
		fmtoggle = 0;
	}	

}

function stop_blink() {
	document.title=inittitle;
	clearInterval(intv);
	intv = null;
}

function flashmail_headerbox_update() {
	new Ajax.Updater('account_flashmail_headerbox_full', '{/literal}{kurl app="flashmail" page="accout_headerbox_content"}{literal}', {asynchronous:true, evalScripts:false, onComplete:flashmail_duplicate_unreadlist});
	return false;
}

function flashmail_duplicate_unreadlist() {
	var test = document.getElementById("account_flashmail_read_link");
	if (test) {
		if (intv == null)
			intv = setInterval('blinktitle("Nouveau flashmail")',1000);
	} else {
		stop_blink();
	}

	//Utilisation d'une recopie du innerHTML en javascript pour éviter d'exécuter 2 fois le même code pour 
	//l'affichage du nombre de flashmails et de la liste des flashmails (depuis que les 2 éléments ne sont
	//plus au même endroit.
	document.getElementById('flashmail_headerbox_unreadlist').innerHTML = document.getElementById('flashmail_headerbox_unreadlist_TMP').innerHTML;
	if (document.getElementById('flashmail_headerbox_unreadlist_TMP').innerHTML.length < 10) {
		document.getElementById('flashmail_headerbox_unreadlist').className = "flashmail";
        document.getElementById('flashmail_headerbox_unreadlist').style.display = 'none';
	}
}

	setInterval("flashmail_headerbox_update()", 40000);
	
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

		document.getElementById('flashmail_headerbox_answer').innerHTML= '{/literal}##LOADING##{literal}';

		flashmail_headerbox_update()
	
		return false;
	}
	// ]]>
	</script>
{/literal}
<div id="account_flashmail_headerbox_full">
{include file="account_headerbox_content.tpl"}
</div>
