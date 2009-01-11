<input id="homeAppAddButton" type="button" onclick="new Effect.toggle($('homeAppAdder'), 'appear'); new Effect.toggle($('homeAppAddButton')); return false;" value="Ajouter une application" /><br />

<div id="homeAppAdder" style="display : none">
Search : <input type="text" name="filterAppList" id="filterAppList" onkeyup="filterAppList();" length="150" />
<input id="homeAppAddCloseButton" type="button" value="Close" onclick="new Effect.toggle($('homeAppAdder'), 'appear'); new Effect.toggle($('homeAppAddButton'));  return false;" /><br />
<div id="homeAppList">
{foreach key=appName item=appObject from=$apps}
<p class="homeAppChoice">
{$appObject->getName($lang)} : {$appObject->getDesc($lang)}
&nbsp;&nbsp;&nbsp;
<a onclick="karibou.instanciateApplication('{$appName}'); return false;">Ajouter</a> <br />
</p>
{/foreach}
</div>
</div>


<div id="configViewer">config</div>

<script type="text/javascript" src="{$karibou_base_url}/themes/js/default2.js"></script>
<script type="text/javascript" src="{$karibou_base_url}/themes/js/scal.js"></script>

<script language="javascript">
var karibou = null;

function filterAppList () {ldelim}
	var val = document.getElementById("filterAppList").value.toLowerCase();
	var targetNode = document.getElementById("homeAppList");
	var subNode = targetNode.firstChild;
	while (subNode) {ldelim}
		if (subNode.nodeType == Node.ELEMENT_NODE) {ldelim}
			if (String(subNode.innerHTML).toLowerCase().indexOf(val) == -1)
				subNode.style.display = "none";
			else
				subNode.style.display = "block";
		{rdelim}
		subNode = subNode.nextSibling;
	{rdelim}
{rdelim}

function tabLinkClickedBack (evt) {ldelim}
	karibou.tabLinkClicked(evt);
{rdelim}

Event.observe(window, "load", function() {ldelim}
	karibou = new Karibou("{kurl page="appmainview"}", "{kurl page="appjsview"}", "{kurl page="appsubmit"}", "{kurl page="appconfig"}", "{kurl page="appjsconfigview"}", "{kurl page="savehome"}", tabLinkClickedBack);
	karibou.loadUrl("{kurl page="homeconfig"}");
{rdelim});

function $app (obj) {ldelim}
	return karibou.getAppFromNode(obj);
{rdelim}

function addTab () {ldelim}
	var name = prompt("Tab name ?", "");
	if ((name) && (name.length > 0))
		karibou.createNewTab(name);
{rdelim}

function closeTab () {ldelim}
	karibou.closeCurrentTab();
{rdelim}

function resizeTab () {ldelim}
	karibou.currentTab.startResize();
	document.getElementById("resizeLink").style.display = "none";
	document.getElementById("cancelResizeLink").style.display = "block";
	document.getElementById("doneResizeLink").style.display = "block";
{rdelim}

function cancelResizeTab () {ldelim}
	karibou.currentTab.cancelResize();
	document.getElementById("resizeLink").style.display = "block";
	document.getElementById("cancelResizeLink").style.display = "none";
	document.getElementById("doneResizeLink").style.display = "none";
{rdelim}

function doneResizeTab () {ldelim}
	karibou.currentTab.doneResize();
	document.getElementById("resizeLink").style.display = "block";
	document.getElementById("cancelResizeLink").style.display = "none";
	document.getElementById("doneResizeLink").style.display = "none";
{rdelim}

</script>
<a href="" onclick="resizeTab(); return false;" id="resizeLink">Resize</a>
<a href="" onclick="cancelResizeTab(); return false;" id="cancelResizeLink" style="display: none;">Cancel</a> 
<a href="" onclick="doneResizeTab(); return false;" id="doneResizeLink" style="display: none;">Done</a>
<br />
<div>
<input type="button" onclick="addTab();" value="[+]" />
<span id="tabsBar"></span>
<input type="button" onclick="closeTab();" value="[-]" />
</div>
<div id="tabsContainer"></div>
