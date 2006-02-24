{popup_init src="/javascripts/overlib.js"}

{capture name="formulaire_wiki"}
	<form action="{kurl docu=$titre_wiki mode="add"}" method="post">
        	<input type="hidden" name="page_wiki" value="{$titre_wiki}" />
			{* p_format = valeur utilisée par la barre dotclear
	 		 *  possibilité de mettre html à la place de wiki
			 *}
			<input type="hidden" name="p_format" id="p_format" value="wiki" />
			<div id="dctoolbar">
			<img title="Forte emphase" src="images/bt_strong.png" />
			<img title="Emphase" src="images/bt_em.png" />
			<img title="Inséré" src="images/bt_ins.png" />
			<img title="Supprimé" src="images/bt_del.png" />
			<img title="Citation en ligne" src="images/bt_quote.png" />
			<img title="Code" src="images/bt_code.png" />
			<img title="Saut de ligne" src="images/bt_br.png" />
			<img title="Bloc de citation" src="images/bt_bquote.png" />
			<img title="Texte préformaté" src="images/bt_pre.png" />
			<img title="Liste non ordonnée" src="images/bt_ul.png" />
			<img title="Liste ordonnée" src="images/bt_ol.png" />
			<img title="Lien" src="images/bt_link.png" />
			<img title="Image externe" src="images/bt_img_link.png" />
			</div>
        	<textarea id="p_content" name="contenu_wiki" cols="60" rows="20">{$contenu_wiki}</textarea>
			<script type="text/javascript" src="/themes/js/toolbar.js"></script>
			{literal}
			<script type="text/javascript">
			// <![CDATA[
				if (document.getElementById) {
				var tb = new dcToolBar(
						document.getElementById('p_content'),
						document.getElementById('p_format'),
						'/themes/karibou/images/wiki/');
				
				tb.btStrong('Forte emphase');
				tb.btEm('Emphase');
				tb.btIns('Inséré');
				tb.btDel('Supprimé');
				tb.btQ('Citation en ligne');
				tb.btCode('Code');
				tb.addSpace(10);
				tb.btBr('Saut de ligne');
				tb.addSpace(10);
				tb.btBquote('Bloc de citation');
				tb.btPre('Texte préformaté');
				tb.btList('Liste non ordonnée','ul');
				tb.btList('Liste ordonnée','ol');
				tb.addSpace(10);
				tb.btLink('Lien',
					'URL ?',
					'Langue ?',
					'fr');
				tb.btImgLink('Image externe',
					'URL ?');
				tb.addSpace(10);
				tb.btImg('Image interne','images-popup.php');
				tb.draw('##TXT_TOOLBAR##');
			}
			// ]]>
			</script>
			{/literal}
		<br />
        <input type="submit" name="submit" value="##ACTION_MODIFY##" />
        <!--<input type="submit" name="preview" value="##ACTION_PREVIEW##" />-->
        <input type="submit" name="reset" value="##ACTION_RESET##" />
        </form>
{/capture}

<div id="wiki">
<h1>page : {$titre_wiki}</h1>
<p class="msg">{$msg}</p>
{if $mode eq "edit"}
        {if $permission >= _SELF_WRITE_}
        	{$smarty.capture.formulaire_wiki}
        {else}
            <p>##NO_PERMISSION##</p>
        {/if}
    
{elseif $mode eq "history"}
    <h2>##HISTORY##</h2>
    {section name=i loop=$history step=-1}
        {if $smarty.section.i.first}
        <table>
        {/if}
        <tr>
        <td>{$history[i].date}</td>
        <td>##BY## {$history[i].login}</td>
        </tr>
        {if $smarty.section.i.last}
        </table>
        {/if}
    {/section}
    <p><a href="{kurl docu=$titre_wiki mode="view"}">##RETOUR##</a></p>
        
{elseif $mode eq "preview"}
Preview Mode still to do
    
{else}
    <div id="zone_wiki">
        {$contenu_wiki}
    </div>
{/if}

{$debug}


</div>
