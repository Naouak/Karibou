
{foreach item=rumours from=$rumours}
    <p><em>{t}On m'a dit dans l'oreillette que : {/t}</em></p>
    <p style="text-align: center;"><strong>&#8220;</strong>&nbsp;{$rumours.rumours}&nbsp;<strong>&#8221;</strong></p>
    <hr />
{/foreach}
