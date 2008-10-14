<h4>##NAVIGBAR##</h4>
<a href="" title="##MINIBUBBLE##"
	onclick="new Ajax.Updater('resetbutton_0_content', '/karibou/website/default/miniappeditview/resetbutton_0'
, {literal}{asynchronous:true, evalScripts:true}{/literal}); return false;" >
	<span class="resetbuttonmini"><span class="text">##MININAVIG##</span></span>
</a>

<a href="" title="##STATSBUBBLE##"
	onclick="resetupdate.stop();clearTimeout(resettimeout); new Ajax.Updater('resetbutton_0_content', '{kurl app="resetbutton" page="stats"}', {literal}{asynchronous:true, evalScripts:true}{/literal}); return false;" >
	<span id="resetbuttonstats"><span class="text">##STATSNAVIG##</span></span>
</a>
<hr />