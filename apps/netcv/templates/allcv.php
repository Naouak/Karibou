<h1><?=_('ALLCV');?></h1>
<div class="netcv">
	<div class="allcv">
<?
if (count($this->vars['allcv'])>0)
{
	echo '<ul>';
	foreach ($this->vars['allcv'] as $cv)
	{
		echo '<li>';
?>
			<span class="name"><a href="http://<?=$cv['hostname'].'.'.$GLOBALS['config']['netcv']['host']?>/<?=$cv['lang'];?>" title="<?=$cv['jobtitle'];?>"><?=$cv['firstname'].' '.$cv['lastname'];?></a></span>
			<span class="jobtitle"><?=$cv['jobtitle'];?></span>
			<?
			/*<span class="jobtitle"><?=$cv['last_modification'];?></span>
			
					<?
					echo _('AGO1');

					$since = $cv['last_modification'];
					if ($since > 86400)
					{
						echo number_format($since/86400,0).' '._('DAY');
						if (number_format($since/86400,0) >= 2)
							echo _('S');
					}
					elseif ($since > 3600)
					{
						echo number_format($since/3600,0).' '._('HOUR');
						if (number_format($since/3600,0) >= 2)
							echo _('S');
					}
					elseif ($since > 60)
					{
						echo number_format($since/60,0).' '._('MINUTE');
						if (number_format($since/60,0) >= 2)
							echo _('S');
					}
					else
					{
						echo $since.' '._('SECOND');
						if ($since >= 2)
							echo _('S');
					}
					echo _('AGO2');
				*/
		echo '</li>';
	}
	?>
	</ul>
<?
}
else
{
?>
	##NOCVYET##
<?
}
?>
	</div>
</div>