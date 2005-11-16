<div class="home">
{if (isset($messages) && $messages !== FALSE)}
<h1>##ACTION_REQUIRED##</h1>
	{foreach from=$messages item=message}
		{if ($message == "NOEMAIL")}
			{*L'utilisateur n'a pas d'email référencé dans son profil*}
			{assign var="nodisplay" value="TRUE"}
			<h2>##ADDEMAIL_TITLE##</h2>
			<div class="warning">
			##ADDEMAIL##
			</div>
			{if (isset($username))}
			<strong><a href="{kurl app="annuaire" username=$username act="edit"}">##MODIFYPROFILE_LINK##</a></strong>
			{/if}
        {elseif ($message == "CHANGEPASSWORD")}
			{*Chnge password*}
			{assign var="nodisplay" value="TRUE"}
			<h2>##CHANGEPASSWORD_TITLE##</h2>
			<div class="warning">
			##CHANGEPASSWORD_DESCRIPTION##
			</div>
			<strong><a href="{kurl app="changepassword"}">##CHANGEPASSWORD_LINK##</a></strong>
		{/if}
	{/foreach}
{/if}

{if (isset($nodisplay) && $nodisplay == TRUE)}
	{*Don't display the home page*}
{else}
	<a  id="personalise_button" href="{kurl act='edit'}">##EDIT##</a>
	<h1>##HEADER_PAGE_TITLE##</h1>
	<br class="spacer" />
	{hook name="html_head"}
	{foreach key=key item=size from=$containers}
	<div id="{$key}" class="cont_{$size}col left" >
	{hook name=$key}
	</div>
	{/foreach}
{/if}
	<br class="spacer" />
</div>
