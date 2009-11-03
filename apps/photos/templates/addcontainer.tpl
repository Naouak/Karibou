<form name="container" method="POST">
    <label for="name">{t}Veuillez entrer le nom du conteneur que vous voulez créer{/t}<input type="text" name="name" id="name" /></label><br />
    {t}Sélectionner le type de conteneur que vous désirez {/t}
        <select name="type" >
            <option value="carton"> carton
            <option value="album"> album
        </select><br />
    
    <input type="submit" value="ajouter un conteneur" />
</form>
