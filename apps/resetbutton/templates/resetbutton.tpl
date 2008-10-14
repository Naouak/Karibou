<script type="text/javascript" language="javascript">
	// <![CDATA[
var resettimeout;
	
resetupdate = new Ajax.PeriodicalUpdater('resetbuttoncontent', '{kurl app="resetbutton" page="content"}',  {literal}
                            {evalScripts: true, frequency: 5, 
                            onSuccess: function (transport) {
                                getElementById("resetbuttoncontent").innerHTML = transport.responseText;
                            }});
	
	
function resetbuttonpushed() {
{/literal}
	var url = '{kurl app="resetbutton" page="reset"}';
{literal}
	new Ajax.Request(url, {
		method: 'post',
		parameters: ''
	});
	{/literal}
	// 1.5 seconds should be enough for the query
	resettimeout = setTimeout("new Ajax.Updater('resetbuttoncontent', '{kurl app="resetbutton" page="content"}', {literal}{asynchronous:true, evalScripts:true}{/literal});", 1000);
	{literal}
	return false;
}
{/literal}


// ]]>
</script>

<h3 class="handle">##RESETBUTTONTITLE##</h3>
{include file="navig.tpl"}
<div>
{if $islogged}
<form onsubmit="return resetbuttonpushed()">
	<input  id="resetbuttonbutton" type="submit" value="##RESET##" />
</form>
{/if}
##NOTRESETEDFOR##<br />
<span id="resetbuttoncontent">
	{include file="content.tpl"}
</span>

</div>
<script type="text/javascript" language="javascript">
	// <![CDATA[
	{literal}
function updatecounter(){
		time = document.getElementById("resethour").innerHTML.split(':');
		time[2] = parseInt(time[2]) + 1;
		if(time[2]%60 != time[2]){
			time[1] = parseInt(time[1]) + 1;
			time[2] = time[2]%60;
			if(time[1]%60 != time[1]){
				time[0] = parseInt(time[0]) + 1;
				time[1] = time[1]%60;
				if(time[0]<10) time[0] = "0"+time[0];
			}
			if(time[1]<10) time[1] = "0"+time[1];
		}
		if(time[2]<10) time[2] = "0"+time[2];
		document.getElementById("resethour").innerHTML = time[0] + ":" + time[1] + ":" + time[2];
		setTimeout("updatecounter()", 1000);
}
{/literal}
	updatecounter();
	// ]]>
</script>
