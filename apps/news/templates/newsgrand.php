<div class="news">
	<h1><?=_('NEWS')?></h1>

	<? if ($this->vars['permission'] > _READ_ONLY_) { ?>
		<strong><a href="<?=kurl(array('page' => "add"))?>"><?=_('ADD_ARTICLE')?></a></strong>
	<? } ?>
	
	<? include("newsmessage.php") ?>
	
	<?
	if ($this->vars['permission'] > _DEFAULT_)
	{
		//{section name=i loop=$theNews step=1}
		foreach ($this->vars['theNews'] as $key => $theArticle)
		{
			//{assign var="theArticle" value=$theNews[i]}
			//{assign var="idNews" value=$theNews[i]->getID()}
			//{assign var="theArticleComments" value=$theNewsComments[$idNews]}
			include("newsview.php");
		}
		//{/section}
		/*
		if (count($this->vars['pages'])>1)
		{
			echo '<p>';
				//{section name=p loop=$pages}
				foreach($this->vars['pages'] as $key => $page)
				{
					if($key > 1)
						echo '|';
					else
						echo _('PAGES').' :';
					echo '<a href="'.kurl(array('pagenum'=>$page)).'">'.$page.'</a>';
				}
				//{/section}
			echo '</p>';
		}
		*/
	}
	?>
</div>