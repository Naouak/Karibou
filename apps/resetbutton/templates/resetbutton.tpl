<script type="text/javascript" language="javascript">
	// <![CDATA[
	
new Ajax.PeriodicalUpdater('resetbuttoncontent', '{kurl app="resetbutton" page="content"}',  {literal}
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
	setTimeout("new Ajax.Updater('resetbuttoncontent', '{kurl app="resetbutton" page="content"}', {literal}{asynchronous:true, evalScripts:true}{/literal});", 1000);
	{literal}
	return false;
}
{/literal}
// ]]>
</script>

<h3 class="handle">##RESETBUTTONTITLE##</h3>
<div>
{if $islogged}
<form onsubmit="return resetbuttonpushed()">
	<input type="submit" value="##RESET##" />
</form>
{/if}
##NOTRESETEDFOR##<br />
<span id="resetbuttoncontent">
	{include file="content.tpl"}
</span>
</div>
