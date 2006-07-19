	<script type="text/javascript" language="javascript">
	// <![CDATA[

setInterval("new Ajax.Updater('onlineusers_live', '{kurl app="onlineusers" page="list"}', {literal}{asynchronous:true, evalScripts:true}{/literal});", 20000);

	// ]]>
	</script>

<h3 class="handle">##ONLINEUSERS##</h3>
<div class="onlineusers">
<div id="onlineusers_live">
{include file="list.tpl"}
</div>
</div>
