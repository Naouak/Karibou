<h1>{t}Photos{/t} {t}Ajout de conteneur{/t}</h1>
<div class="photos-content">
    <form action='{kurl action="container"}' method="POST">
        <input type="hidden" name="parent" value={$parent} />

        <fieldset>
            <legend>{t}Conteneur{/t}</legend>
            <label for="name">{t}Nom du conteneur{/t}</label>
            <input type="text" name="name" id="name" />

            <label for="type">{t}Type de conteneur{/t}</label>
            <select name="type" id="type">
                <option value="album" selected>album</option>
                <option value="carton">carton</option>
            </select>


        </fieldset>

        <fieldset>
            <legend>{t}Tags{/t}</legend>
            <label for="tag">{t}Liste des tags{/t}</label>
            <input type="text" id="tags" name="tags"/>
            <div class="photos-hint photos-addcontainer-taghint">
                {t}Bla bla bla bla et c'est très bien.{/t}
            </div>
        </fieldset>

        <div class="photos-addcontainer-submit">
            <input type="submit" value="ajouter un conteneur" />
        </div>
    </form>
</div>
