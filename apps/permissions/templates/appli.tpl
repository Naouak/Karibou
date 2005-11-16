<div class="stage_contenu">
	<h1>##PERM_TITLE## {$appli}</h1>

	<div class="stage_element">
		<div class="stage_element_title">
			##GROUP_PERM##
		</div>
		<div class="stage_element_data">
		
			<form class="stage_form" method="post" action="{kurl action="post"}">
{foreach item=group from=$groups}
				<label for="group_{$group->getId()}">{$group->getName()} :</label>
				<select class="perm{$group->perm}" id="group_{$group->getId()}" name="group_{$group->getId()}">
{	foreach item=d from=$droits}
{		if $d eq $group->perm}
					<option class="perm{$d}" value="{$d}" selected>
{		else}
					<option class="perm{$d}" value="{$d}">
{		/if}
					{translate key=$d}
					</option>
{	/foreach}
				</select>
				<br />
{/foreach}
	
				<input type="hidden" name="appli" value="{$appli}" />
				<label for="validerClubs">##OK##</label>
				<input type="submit" id="validerClubs" name="validerClubs" value="Ok" />
			</form>
			
		</div>		
	</div>		
	
</div>
