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
<form action="{kurl page="keychainreset"}" method="post"><input type="submit" value="##IGNORE##" /></form>
</div>
{else}
<script type="text/javascript" src="{$karibou_base_url}/scripts/default2.js"></script>

<script language="javascript">
var karibou = null;

{literal}
function filterAppList (evt) {
	if (evt.keyCode == 27) {
		new Effect.toggle($('homeAppAdder'), 'appear');
		$('homeAppAddButton').value = "##Add an application##";
		evt.stop();
		return false;
	}
	var val = document.getElementById("filterAppList").value.toLowerCase();
	var targetNode = document.getElementById("homeAppList");
	var subNode = targetNode.firstChild;
	var count = 0;
	var foundNode = null;
	while (subNode) {
		if (subNode.nodeType == Node.ELEMENT_NODE) {
			if (String(subNode.innerHTML).toLowerCase().indexOf(val) == -1) {
				subNode.style.display = "none";
			} else {
				subNode.style.display = "block";
				count++;
				foundNode = subNode;
			}
		}
		subNode = subNode.nextSibling;
	}
	if ((evt) && (count == 1)) {
		if (evt.keyCode == 13) {
			var appName = foundNode.attributes.getNamedItem("kappName").nodeValue;
			karibou.instanciateApplication(appName);
			document.getElementById("filterAppList").value = "";
			document.getElementById("filterAppList").focus();
			filterAppList(null);
		}
	}
}

{/literal}
Event.observe(window, "load", function() {ldelim}
	karibou = new Karibou("{kurl page="appmainview"}", "{kurl page="appjsview"}", "{kurl page="appsubmit"}", "{kurl page="appconfig"}", "{kurl page="appjsconfigview"}", "{kurl page="appgetdata"}", "{kurl page="appmodify"}", "{kurl page="appdelete"}", "{kurl page="savehome"}");
	karibou.loadUrl("{kurl page="homeconfig"}");
{rdelim});

function $app (obj) {ldelim}
	return karibou.getAppFromNode(obj);
{rdelim}

function customizeTab() {ldelim}
        karibou.unlock();
	new Effect.toggle("customizeSection",'appear',{literal}{ duration: 0.3 }{/literal});
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

function toggleAddAppList() {ldelim}
	karibou.unlock();
	new Effect.toggle($('homeAppAdder'), 'slide', {ldelim} afterFinish: function(eff) {ldelim} $('filterAppList').focus(); {rdelim} {rdelim} );
	if ($('homeAppAddButton').value == "##Close##")
		$('homeAppAddButton').value = "##Add an application##";
	else
		$('homeAppAddButton').value = "##Close##";
{rdelim}

{include file=rsa.tpl}

</script>
{if $islogged}
<div class="default2_customize">

<a href="" onclick="customizeTab(); return false;">##Customize##</a><input id="homeAppAddButton" type="button" onclick="toggleAddAppList(); return false;" value="##Add an application##" /><br />

<span id="customizeSection" style="display: none;">
<ul>
<li><a href="" onclick="resizeTab(); return false;" id="resizeLink">##Resize##</a></li>
<span id="columnsLinks">
<li><a href="" onclick="karibou.currentTab.addColumn(); return false;" id="addColumnLink">##Add a column##</a></li>
<li><a href="" onclick="karibou.currentTab.removeLastColumn(); return false;" id="removeColumnLink">##Remove the last column##</a></li>
</span>
<span id="resizeLinks">
<li><a href="" onclick="cancelResizeTab(); return false;" id="cancelResizeLink">##Cancel##</a></li>
<li><a href="" onclick="doneResizeTab(); return false;" id="doneResizeLink">##Done##</a></li>
</span>
</ul>
</span>


<div id="homeAppAdder" style="display : none">
Search : <input type="text" name="filterAppList" id="filterAppList" onkeyup="filterAppList(event);" length="150" /><br />
<ul id="homeAppList">
{foreach key=appName item=appObject from=$apps}
    <li class="homeAppChoice" kappName="{$appName}">
	<span class="default2-applist-appname">{$appObject->getName("$lang")}</span>
	<span class="default2-applist-appdescription">{$appObject->getDesc("$lang")}</span>
	<a onclick="karibou.instanciateApplication('{$appName}'); $('filterAppList').focus(); return false;">Ajouter</a>
    </li>
{/foreach}
</ul>
</div>


</div>
{/if}
<br />
<div class="default2-tabs">
{if $islogged}
<div class="default2-tabs-modify">
    
    <input type="button" class="default2-tabs-removetab" onclick="karibou.closeCurrentTab();" value="##Remove Current tab##" />

</div>
{/if}
<ul id="tabsBar">{if $islogged}<li id="default2-addtabbutton">
    <form id="default2-addtabbutton-formm" method="post" action="" onsubmit="default2_tab_submit();">
	<label onclick="setTabText();" for="default2-addtabbutton-form" id="default2-addtabbutton-label">##Add a tab##</label>
	<input type="text" id="default2-addtabbutton-form" value=""/>
    </form>
    <div id="default2-addtabbutton-hint">##Insert Tab name then submit with enter##</div>
</li>{/if}</ul>
{if $islogged}
{literal}
<script>
    <!--
	function default2_tab_blur(){
	    document.getElementById("default2-addtabbutton-label").style.visibility = "visible";
	    document.getElementById("default2-addtabbutton-hint").style.visibility = "hidden";
	    document.getElementById("default2-addtabbutton-form").style.visibility = "hidden";
	    document.getElementById("default2-addtabbutton-form").value = "";
	}

	function default2_tab_submit(e){
	    var name = document.getElementById("default2-addtabbutton-form").value;
	    if ((name) && (name.length > 0)){
		karibou.createNewTab(name);
		document.getElementById("default2-addtabbutton-form").value = "";
		document.getElementById("default2-addtabbutton-form").blur();
	    }
	    else{

	    }
	    if(!e){
		if(window.event){
		    window.event.returnValue = false;
		}
		default2_tab_blur();
	    }else{
		e.preventDefault();
	    }
	    return false;
	}

	if (document.getElementById("default2-addtabbutton-form").addEventListener){
	    document.getElementById("default2-addtabbutton-formm").addEventListener("submit",default2_tab_submit, false);
	    document.getElementById("default2-addtabbutton-form").addEventListener("blur",default2_tab_blur, false);
	} else if (document.getElementById("default2-addtabbutton-form").attachEvent) {
	    document.getElementById("default2-addtabbutton-formm").attachEvent("submit",default2_tab_submit);
	    document.getElementById("default2-addtabbutton-form").attachEvent("blur",default2_tab_blur);
	}

	function setTabText(){
	    document.getElementById("default2-addtabbutton-label").style.visibility = "hidden";
	    document.getElementById("default2-addtabbutton-hint").style.visibility = "visible";
	    document.getElementById("default2-addtabbutton-form").style.visibility = "visible";
	    document.getElementById("default2-addtabbutton-form").focus();
	};


    //-->
</script>
{/literal}
{/if}
</div>
<div id="tabsContainer"></div>
{/if}

