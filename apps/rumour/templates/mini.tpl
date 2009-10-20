
{foreach item=rumours from=$rumours}
    <p><em>{t}On m'a dit dans l'oreillette que : {/t}</em></p>
    <p style="text-align: center;">&#8220;&nbsp;{$rumours.rumours}&nbsp;&#8221;</p>
{/foreach}
