<div class="news">
	<h1>##NEWS##</h1>
	<h2>{if isset($theNewsToModify)}##MODIFY_ARTICLE##{else}##ADD_ARTICLE##{/if}</h2>
	<div class="newNewsForm">
		{*<a href="{kurl app="wiki" page="help"}" >##TITLE_WIKI_SYNTAX##</a>*}
		<a href="{kurl app="wiki" page="help"}" onclick="javascript:popup(this.href, 'wiki_help', '800', '900', '200', '200');return false;" >##TITLE_WIKI_SYNTAX##</a>
	{include file="newsmessage.tpl"}
				<form action="{kurl action="post"}" method="post">
					<input type="hidden" name="postType" value="newNews" >
					{if isset($theNewsToModify)}
						<input type="hidden" name="id" value="{if isset($theNewsToModify)}{$theNewsToModify->getId()}{/if}">
					{/if}
					<div class="field title">
						<label for="title">##NEWS_TITLE## :</label> <input type="text" id="title" name="title" value="{if isset($theNewsToModify)}{$theNewsToModify->getTitle()|escape:"html"}{/if}"/>
					</div>
					<div class="field description">
						<label for="description">##NEWS_DESCRIPTION## : </label><textarea name="content" id="description" rows="10" cols="60" />{if isset($theNewsToModify)}{$theNewsToModify->getContent()|escape:"html"}{/if}</textarea>
					</div>
					<input type="hidden" name="group" value="">

                    {if isset($Admin.0.Id)}
					<div class="field group">
						<label for="group">##NEWS_GROUPS_DESTINATION## :</label>
						<select name="group" id="group" >
                        <option {if isset($theNewsToModify)}{if (!$theNewsToModify->getGroup())}selected{/if}{/if} value="">Pas de groupe</option>
                    { foreach item=Admin from=$Admin }
                        <option {if isset($theNewsToModify)}{if ($theNewsToModify->getGroup()==$Admin.Id)}selected{/if}{/if} value="{$Admin.Id}" > {$Admin.name} </option>
                    {/foreach}
						</select>
					</div>
                    
                    {/if}
                    {if !isset($theNewsToModify)}
                    <div id="checkbox" >
                        <label for="title">Voulez-vous ajouter en même temps un JourJ ? </label> <input type="checkbox" name="DDay" id="DDay" onchange="new Effect.toggle(document.getElementById('DDayNews')); return false;" >
                    </div>
                    <div id="DDayNews" class="field title" style="display:none;">
                        <label for="title">Événement : </label><input  type="text" name="event" id="event" ><br />
                        <label for="title">Date (YYYY-MM-DD) : </label><input type=text name="date" id="date"><br />
                        <label for="title">Description : </label><input type=text name="description" id="description"> <br />
                        <label for="title">Lien : </label><input type=text name="URL" id="URL">

                    </div>
                    {/if}
					<div class="button">
						<input type="submit" value="{if isset($theNewsToModify)}##POST_MODIFIED_ARTICLE##{else}##POST_NEW_ARTICLE##{/if}" />
					</div>
				</form>
	</div>
</div>
