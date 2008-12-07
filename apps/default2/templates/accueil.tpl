Ajouter une application :<br />
<ul>
{foreach item=appName from=$apps}
<li><a href="#" onclick="karibou.instanciateApplication('{$appName}', document.getElementById('appContainer')); return false;">{$appName}</a></li><br />
{/foreach}
</ul>
{*
- {$birthday->getAppName()}<br />
- {$birthday->getMainView()}<br />
- {$birthday->getConfigView()}<br />
- {$birthday->getJsView()}<br />
- {$birthday->getName('fr')}<br />
- {$birthday->getDesc('fr')}<br />
*}
<script type="text/javascript" src="{$karibou_base_url}/themes/js/default2.js"></script>

<script language="javascript">
var karibou = null;

function tabLinkClickedBack (evt) {ldelim}
	karibou.tabLinkClicked(evt);
{rdelim}

Event.observe(window, "load", function() {ldelim}
	karibou = new Karibou("{kurl page="listuserapps"}", "{kurl page="appmainview"}", "{kurl page="appjsview"}", "{kurl page="appsubmit"}", tabLinkClickedBack);
	karibou.createNewTab("default");
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
</script>
<a href="" onclick="karibou.currentTab.startResize(); return false;">Resize</a><br />
<div>
<input type="button" onclick="addTab();" value="[+]" />
<span id="tabsBar"></span>
<input type="button" onclick="closeTab();" value="[-]" />
</div>
<div id="tabsContainer"></div>

