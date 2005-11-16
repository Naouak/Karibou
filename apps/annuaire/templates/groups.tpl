{include file="appnav.tpl"}
<h1>##TITLE##</h1>
<h3>##USER_GROUPS_TREE##</h3>
<div class="helper">
	##USER_GROUPS_TREE_DESCRIPTION##
</div>
<ul>
{include file="grouptree.tpl" grouptree=$grouptree}
</ul>