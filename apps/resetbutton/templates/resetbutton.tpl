<script type="text/javascript" language="javascript">
	// <![CDATA[
	
new Ajax.PeriodicalUpdater('resetbuttoncontent', '{kurl app="resetbutton" page="content"}',  {literal}
                            {evalScripts: true, frequency: 10, 
                            onSuccess: function (transport) {
                                getElementById("resetbuttoncontent").innerHTML = transport.responseText;
                            }});
	
	
function reset() {
{/literal}
	var url = '{kurl app="resetbutton" page="content"}';
{literal}
	new Ajax.Request(url, {
		method: 'post',
		parameters: 'reset=resseting'
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

<span id="resetbuttoncontent">
	{include file="content.tpl"}
</span>

