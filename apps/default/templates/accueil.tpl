{if $keychainError}
<div class="home">
<br />
{if $secondAttempt}
<strong>##INVALID_PASSWORD##</strong><br />
{/if}
##KEYCHAIN_PROBLEM_PASSWORD_OUT_OF_SYNC##<br />
##OLD_PASSWORD_NEEDED##<br />
<form action="{kurl page="keychain"}" method="post">
	##OLD_PASSWORD_FIELD## : <input type="password" name="old_password" id="old_password" /><br />
	<input type="submit" />
</form>
</div>
{else}
<script type="text/javascript" src="{$karibou_base_url}/themes/js/default2.js"></script>
<script type="text/javascript" src="{$karibou_base_url}/themes/js/Barrett.js"></script>
<script type="text/javascript" src="{$karibou_base_url}/themes/js/BigInt.js"></script>
<script type="text/javascript" src="{$karibou_base_url}/themes/js/RSA.js"></script>
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

function showAddAppList() {ldelim}
	new Effect.toggle($('homeAppAdder'), 'appear');
	new Effect.toggle($('homeAppAddButton'));
	setTimeout("$('filterAppList').focus();", 1300);
{rdelim}

// The RSA key
var KeyPair = new RSAKeyPair(
	"{$pubkey_exp}",
	"",
	"{$pubkey_mod}"
);

</script>
{if $loggedUser}
<div class="default2_customize">

<a href="" onclick="customizeTab(); return false;">Customize</a><input id="homeAppAddButton" type="button" onclick="showAddAppList(); return false;" value="Ajouter une application" /><br />

<span id="customizeSection" style="display: none;">
<ul>
<li><a href="" onclick="resizeTab(); return false;" id="resizeLink">Resize</a></li>
<span id="columnsLinks">
<li><a href="" onclick="addColumn(); return false;" id="addColumnLink">Add a column</a></li>
<li><a href="" onclick="removeLastColumn(); return false;" id="removeColumnLink">Remove the last column</a></li>
</span>
<span id="resizeLinks">
<li><a href="" onclick="cancelResizeTab(); return false;" id="cancelResizeLink">Cancel</a></li>
<li><a href="" onclick="doneResizeTab(); return false;" id="doneResizeLink">Done</a></li>
</span>
</ul>
</span>


<div id="homeAppAdder" style="display : none">
Search : <input type="text" name="filterAppList" id="filterAppList" onkeyup="filterAppList();" length="150" />
<input id="homeAppAddCloseButton" type="button" value="Close" onclick="new Effect.toggle($('homeAppAdder'), 'appear'); new Effect.toggle($('homeAppAddButton'));  return false;" /><br />
<div id="homeAppList">
{foreach key=appName item=appObject from=$apps}
<p class="homeAppChoice">
{$appObject->getName("$lang")} : {$appObject->getDesc("$lang")}
&nbsp;&nbsp;&nbsp;
<a onclick="karibou.instanciateApplication('{$appName}'); $('filterAppList').focus(); return false;">Ajouter</a> <br />
</p>
{/foreach}
</div>
</div>


</div>
{/if}
<br />
<div>
{if $loggedUser}
<input type="button" onclick="addTab();" value="+" />
{/if}
<span id="tabsBar"></span>
{if $loggedUser}
<input type="button" onclick="closeTab();" value="-" />
{/if}
</div>
<div id="tabsContainer"></div>
{/if}
