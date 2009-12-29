<div class="bugs-content bugs-big-content">
    <h1>{t}Bugs{/t}</h1>
    <h2>{t}Introduction{/t}</h2>
    <div>
        <p>{t}Introduction description{/t}</p>
    </div>

    <h2>{t}Bug List{/t}</h2>
    <div>
        <p>{t}Intro bug list description{/t}<p>
        <p><a href="{kurl page="sort" sort="state" ascdescsort="1" numberpage="1"}">{t}Bug List{/t}</a></p>
    </div>

    <h2>{t}Signal a bug{/t}</h2>
    <div>
        <p>{t}Intro Bug signal{/t}</p>
        <p><a href="{kurl page="add"}">{t}Signal a bug{/t}</a></p>
    </div>

	{if $isadmin}
		<h2>{t}Admin{/t} :</h2>
		<div>
			<p>{t}Module admin{/t} : </p>
			<p><a href="{kurl page="modules"}">{t}Modules{/t}</p>
			<p><a href="{kurl page="addmodule"}">{t}Add module{/t}</p>
		</div>
	{/if}
		
</div>