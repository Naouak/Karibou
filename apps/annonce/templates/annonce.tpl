<!-- LIST ANNOUNCES -->
<p>
{foreach item=annonce from=$annonces}
<div id="annoncecss">
<span id="annoncecsstext">##SURNAMEANN_AUTHOR## </span>: {userlink user=$annonce.author showpicture=$islogged }
<br />
{$annonce.text|wordwrap:34:" ":true}
{if isset($annonce.price) && $annonce.price!=0}
<br />
<span id="annoncecssprix">##prix## </span>{$annonce.price|string_format:"%.2f"}€
{/if}
<br />
{if isset($annonce.expirationdate) && $annonce.expirationdate!='0000-00-00'}
<span id="annoncecssdate">##to## </span>{$annonce.expirationdate}
{/if}
<br />

{if $annonce.iduser == $currentuser || $isadmin}
<p> <input type="submit" name="bouton" value="enlever" onclick="$app(this).update('{$annonce.id}');" /> 
{/if}
</div>

<a href="{kurl app="commentaires"  id=$annonce.idcombox}" > test </a>
{*kurl app="commentaires"  id=$annonce.idcombox*}
{commentbox id=$annonce.idcombox} <br />
{if !$annonce.voted }
<a href="{kurl app="votes"  id=$annonce.idcombox votes="1"}" > + </a> / <a href="{kurl app="votes"  id=$annonce.idcombox votes='-1'}" > - </a>
{/if}
score total {$annonce.score.0} nombre de votants {$annonce.score.1}
{/foreach}

</p>
</p>
