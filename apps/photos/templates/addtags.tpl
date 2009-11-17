<h1>{t}Photos{/t} - {$type} - {t}Ajouter un tag{/t}</h1>
<div class="photos-content">
    <div class="photos-addtags-form">
        <form action='{kurl action="tags"}' method="POST">
            <input type="hidden" name="type" value="{$type}" />
            <input type="hidden" name="id" value="{$parent}" />

            <fieldset>
                <legend>{t}Tags{/t}</legend>
                <label for="tag">{t}Liste des tags{/t}</label><input type="text" name="tag" id="tag" />
            </fieldset>

            <div class="photos_addtags_submit">
                <input type="submit" value="ajouter des tags"/>
            </div>
        </form>
    </div>

    <div class="photos-addtags-existingtags photos-main-container">
        <h2>{t}Tag déjà soumis{/t}</h2>
        <ul>
        {foreach from=$tags item=tag}
            <li>{$tag.name}</li>
        {/foreach}
        </ul>
    </div>
    <div class="photos-addtags-alltags photos-main-container">
        <h2>{t}Tags existant dans la base de données{/t}</h2>
        <ul>
            {foreach from=$alltags item=tag name=tags}
                <li>{$tag.name}</li>
            {/foreach}
        </ul>
    </div>

</div>