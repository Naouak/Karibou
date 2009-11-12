<div class="bugs">
	<h1>Bugs</h1>
	<h2>Modifier un bug</h2>
	{if $isadmin || $currentuser == $current_module.developper_id}
		
		<div class="newBugsForm">

			<form action="{kurl action="post"}" method="post">
				<input type="hidden" name="id" value="{$bug.Id}" />

				<div class="field title">
					<label for="title">Titre :</label> <input type="text" id="title" name="title" value="{$bug.title}" />
				</div>
				<br />
				Application concernée :
				<SELECT name="module">
					{foreach item=module from=$modules}
						{if $module.id == $bug.module_id}
							<OPTION selected>{$module.appli_name}
						{else}
							<OPTION>{$module.appli_name}
						{/if}
					{/foreach}
				</SELECT>
				<br /><br />
				<div class="field browser">
					<label for="browser">Navigateur :</label> <input type="text" id="browser" name="browser" value="{$bug.Browser}"/>
				</div>
				<br /><br />
				<div class="field description">
					<label for="description">Description : </label><textarea name="bug" id="description" rows="10" cols="60" />{$bug.bug} </textarea>
				</div>

				<br /><br />
				<input type="radio" name="bug_type" value="amelioration" id="impbug" {if $bug.bug_type == "amelioration"} checked {/if} />
				<label for="impbug">
					<span>Amélioration</span>
				</label>
				<br />
				<input type="radio" name="bug_type" value="mineur" id="minbug" {if $bug.bug_type == "mineur"} checked {/if}/>
				<label for="minbug">
					<span>Bug mineur</span>
				</label>
				<br />
				<input type="radio" name="bug_type" value="normal" id="normbug" {if $bug.bug_type == "normal"} checked {/if} />
				<label for="normbug">
					<span>Bug normal</span>
				</label>
				<br />
				<input type="radio" name="bug_type" value="majeur" id="majbug" {if $bug.bug_type == "majeur"} checked {/if}/>
				<label for="majbug">
					<span>Bug majeur</span>
				</label>
				<br />
				<input type="radio" name="bug_type" value="securite" id="secbug" {if $bug.bug_type == "securite"} checked {/if}/>
				<label for="secbug">
					<span>Sécurité</span>
				</label>
				<br /> <br />
			
				<SELECT name="bug_state"
					<OPTION> En attente
					<OPTION> Resolu
					<OPTION> Besoin d'informations
					<OPTION> Pris en compte
				</SELECT>
				<br /> <br/>
					
				<input type="radio" name="doublon" value="true" id="ydoublon" {if $bug.doublon==1}checked{/if} />
				<label for="ydoublon">
					<span>Ce bug est un doublon</span>
				</label>
				<br />

				<input type="radio" name="doublon" value="false" id="ndoublon" {if $bug.doublon==0}checked{/if} />
				<label for="ndoublon">
					<span>Ce bug n'est pas un doublon</span>
				</label>
				<div class="field doublon">
					<label for="doublon">Doublon du bug n° :</label> <input type="text" id="doublon_id" name="doublon_id" value="{$bug.doublon_id}" />
				</div>
				<br /><br />
				
				<div class="button">
					<input type="submit" value="Poster le bug" />
				</div>
			</form>
		</div>
	{elseif $currentuser == $bug.user_id && $bug.doublon == 0}

		<div class="newBugsForm">

			<form action="{kurl action="post"}" method="post">
				<input type="hidden" name="id" value="{$bug.Id}" />

				<div class="field title">
					<label for="title">Titre :</label> <input type="text" id="title" name="title" value="{$bug.title}" />
				</div>
				<br />
				Application concernée :
				<SELECT name="module">
					{foreach item=module from=$modules}
						{if $module.id == $bug.module_id}
							<OPTION selected>{$module.appli_name}
						{else}
							<OPTION>{$module.appli_name}
						{/if}
					{/foreach}
				</SELECT>
				<br /><br />
				<div class="field browser">
					<label for="browser">Navigateur :</label> <input type="text" id="browser" name="browser" value="{$bug.Browser}"/>
				</div>
				<br /><br />
				<div class="field description">
					<label for="description">Description : </label><textarea name="bug" id="description" rows="10" cols="60" />{$bug.bug} </textarea>
				</div>

				<br /><br />
				<input type="radio" name="bug_type" value="amelioration" id="impbug" {if $bug.bug_type == "amelioration"} checked {/if} />
				<label for="impbug">
					<span>Amélioration</span>
				</label>
				<br />
				<input type="radio" name="bug_type" value="mineur" id="minbug" {if $bug.bug_type == "mineur"} checked {/if}/>
				<label for="minbug">
					<span>Bug mineur</span>
				</label>
				<br />
				<input type="radio" name="bug_type" value="normal" id="normbug" {if $bug.bug_type == "normal"} checked {/if} />
				<label for="normbug">
					<span>Bug normal</span>
				</label>
				<br />
				<input type="radio" name="bug_type" value="majeur" id="majbug" {if $bug.bug_type == "majeur"} checked {/if}/>
				<label for="majbug">
					<span>Bug majeur</span>
				</label>
				<br />
				<input type="radio" name="bug_type" value="securite" id="secbug" {if $bug.bug_type == "securite"} checked {/if}/>
				<label for="secbug">
					<span>Sécurité</span>
				</label>
				<br /> <br />

				<SELECT name="bug_state"
					<OPTION> En attente
					<OPTION> Resolu
				</SELECT>
				<br /> <br/>

				<div class="button">
					<input type="submit" value="Poster le bug" />
				</div>
			</form>
		</div>
	{/if}
</div>


