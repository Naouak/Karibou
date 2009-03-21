Attention, attention : on '{$lang}' est en train de bosser !<br /><br />

{if $loggedUser}
<input id="homeAppAddButton" type="button" onclick="new Effect.toggle($('homeAppAdder'), 'appear'); new Effect.toggle($('homeAppAddButton')); document.getElementById('filterAppList').focus(); return false;" value="Ajouter une application" /><br />

<div id="homeAppAdder" style="display : none">
Search : <input type="text" name="filterAppList" id="filterAppList" onkeyup="filterAppList();" length="150" />
<input id="homeAppAddCloseButton" type="button" value="Close" onclick="new Effect.toggle($('homeAppAdder'), 'appear'); new Effect.toggle($('homeAppAddButton'));  return false;" /><br />
<div id="homeAppList">
{foreach key=appName item=appObject from=$apps}
<p class="homeAppChoice">
{$appObject->getName("$lang")} : {$appObject->getDesc("$lang")}
&nbsp;&nbsp;&nbsp;
<a onclick="karibou.instanciateApplication('{$appName}'); return false;">Ajouter</a> <br />
</p>
{/foreach}
</div>
</div>
{/if}

<script type="text/javascript" src="{$karibou_base_url}/themes/js/default2.js"></script>
<script type="text/javascript" src="{$karibou_base_url}/themes/js/scal.js"></script>
<script type="text/javascript" src="{$karibou_base_url}/themes/js/Barrett.js"></script>
<script type="text/javascript" src="{$karibou_base_url}/themes/js/BigInt.js"></script>
<script type="text/javascript" src="{$karibou_base_url}/themes/js/RSA.js"></script>

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

function customizeTab() {ldelim}
	new Effect.toggle("customizeSection");
	document.getElementById("columnsLinks").style.display = "block";
	document.getElementById("resizeLinks").style.display = "none";
	document.getElementById("resizeLink").style.display = "block";
{rdelim}

function resizeTab () {ldelim}
	document.getElementById("resizeLinks").style.display = "block";
	document.getElementById("columnsLinks").style.display = "none";
	document.getElementById("resizeLink").style.display = "none";
	karibou.currentTab.startResize();
{rdelim}

function cancelResizeTab () {ldelim}
	new Effect.toggle("customizeSection");
	karibou.currentTab.cancelResize();
{rdelim}

function doneResizeTab () {ldelim}
	new Effect.toggle("customizeSection");
	karibou.currentTab.doneResize();
{rdelim}

function addColumn () {ldelim}
	karibou.currentTab.addColumn();
{rdelim}

function removeLastColumn () {ldelim}
	karibou.currentTab.removeLastColumn();
{rdelim}

// The RSA key
var KeyPair = new RSAKeyPair(
	"{$pubkey_exp}",
	"",
	"{$pubkey_mod}"
);

</script>
{if $loggedUser}
<a href="" onclick="customizeTab(); return false;">Customize</a><br />
<span id="customizeSection" style="display: none;">
<a href="" onclick="resizeTab(); return false;" id="resizeLink">- Resize</a><br />
<span id="columnsLinks">
<a href="" onclick="addColumn(); return false;" id="addColumnLink">- Add a column</a><br />
<a href="" onclick="removeLastColumn(); return false;" id="removeColumnLink">- Remove the last column</a>
</span>
<span id="resizeLinks">
<a href="" onclick="cancelResizeTab(); return false;" id="cancelResizeLink">- Cancel</a><br />
<a href="" onclick="doneResizeTab(); return false;" id="doneResizeLink">- Done</a>
</span>
</span>
{/if}
<br />
<div>
{if $loggedUser}
<input type="button" onclick="addTab();" value="[+]" />
{/if}
<span id="tabsBar"></span>
{if $loggedUser}
<input type="button" onclick="closeTab();" value="[-]" />
{/if}
</div>
<div id="tabsContainer"></div>
