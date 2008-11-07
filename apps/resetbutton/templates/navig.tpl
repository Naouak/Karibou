{literal}
<style type="text/css">
	#resetbuttonnavigbar{
		list-style-type: none;
		border-bottom: black solid 1px;
		width: 100%;
		margin:0px;
		clear:both;
	}
	
	#resetbuttonnavigbar > li{
		float:left;
		border: black solid 1px;
		padding: 2px;
		margin: 2px;
		margin-top: 0px;
		width: 100px;
		text-align: center;
	}

	/* INUTILE 
	#resetbuttonnavigbar > li:last-child{
		color: blue;
		float: none;
		
		margin-top: 0px;
		margin-left: 222px;
		width: 100px;
	}
	*/
	#resetbuttonbutton{
		margin: auto;
		text-align: center;
	}
	
	
</style>
{/literal}
<ul id="resetbuttonnavigbar">
	<li>
		<a href="" title="##MINIBUBBLE##"
			onclick="new Ajax.Updater(this.parentNode.parentNode.parentNode, '{kurl app="resetbutton" page="mini"}'
		, {literal}{asynchronous:true, evalScripts:true}{/literal}); return false;" >
			<span class="resetbuttonmini"><span class="text">##MININAVIG##</span></span>
		</a>
	</li>
	<li>
		<a href="" title="##STATSBUBBLE##"
			onclick="resetupdate.stop();clearTimeout(resettimeout); new Ajax.Updater(this.parentNode.parentNode.parentNode, '{kurl app="resetbutton" page="stats"}', {literal}{asynchronous:true, evalScripts:true}{/literal}); return false;" >
			<span id="resetbuttonstats"><span class="text">##STATSNAVIG##</span></span>
		</a>
	</li>
	<li>
		<a href="" title="##MYSTATSBUBBLE##"
			onclick="resetupdate.stop();clearTimeout(resettimeout); new Ajax.Updater(this.parentNode.parentNode.parentNode, '{kurl app="resetbutton" page="mystats"}', {literal}{asynchronous:true, evalScripts:true}{/literal}); return false;" >
			<span id="resetbuttonstats"><span class="text">##MYSTATSNAVIG##</span></span>
		</a>
	</li>
</ul>
