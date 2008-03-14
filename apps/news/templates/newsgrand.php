<div class="news">
	<h1><?=_('NEWS')?></h1>
<?
	include("newsmessage.php");
?>

	<a href="<?=kurl(array('app'=>"news", 'page'=>"archives"));?>" onclick="new Effect.toggle(document.getElementById('news_archives')); return 
false;"><?=_('NEWS_VIEW_ARCHIVES');?></a>
<?
	echo '<div id="news_archives" style="margin-left: 20px; margin-bottom: 10px; padding: 5px; padding-left: 20px; border: 1px solid #acacac; background-color: #fafafa;" class="dontshow">';
	$year = "";
	foreach($this->vars['newsbymonth'] as $month)
	{
		if ($year != $month['year'])
		{
			echo "<div style=\"margin-left: -10px;\"><strong>".$month['year']."</strong></div>";
			$year = $month['year'];
		}
		else
		{
			echo ', &nbsp;';
		}
		echo '<a href="'.kurl(array("app"=>"news", "year"=>$month['year'], "month"=>$month['month'])).'">'.ucfirst(strftime ('%B', $month['timestamp'])).'</a> <span style="color: #acacac;">('.$month['count'].')</span>';
	}
	
	echo "</div>";

	if ($this->vars['permission'] >= _READ_ONLY_)
	{
		foreach ($this->vars['theNews'] as $key => $theArticle)
		{
			include("newsview.php");
		}
	}
	?>
</div>
