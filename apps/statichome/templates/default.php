<div id="statichome">
	<div class="maxi">
		<div class="lastevents">
			<h2><?=_('LASTEVENTS');?></h2>
		</div>
	</div>
	<div class="mini">
		<div class="box communicate news">
			<h2><?=_('NEWS');?></h2>
			<ul>
				<?
					$linecolor = '';
					foreach ($this->vars['articles'] as $article)
					{
						if ($linecolor == 'one')
							$linecolor = 'two';
						else
							$linecolor = 'one';
				?>
					<li class="<?=$linecolor;?>"><?/*=kurl(array('app'=>'news', 'id'=>$article->getId()));*/?>
						<a href="<?=kurl(array('app'=>"news", 'page'=>"view", 'id'=>$article->getId()))?>"><?=$article->getTitle();?></a>
						<br />
						<?=_('BY');?> <?=userlink(array('user'=>$article->getAuthorObject(), 'showpicture'=>true));?>
						<br />
						<?
							echo substr(strip_tags($article->getContentXHTML()), 0, 200);
							if (strlen(strip_tags($article->getContentXHTML())) > 200) {
								echo "...";
							}
						?>
					</li>
				<?
					}
				?>
			</ul>
		</div>

		<div class="box share fileshare">
			<h2><?=_('FILESHARE');?> : <?=_('LASTADDEDFILES');?></h2>
			<? if (count($this->vars['lastAddedFiles']) > 0) { ?>
			<ul>
				<?
				$linecolor = '';
				foreach ($this->vars['lastAddedFiles'] as $file)
				{
				?>
					<?
					if ($linecolor == 'one')
						$linecolor = 'two';
					else
						$linecolor = 'one';
					?>
					<li class="<?=$linecolor;?>">
						<span class="name">
							<a href="<?=kurl(array('app' => 'fileshare', 'page'=>"details", 'elementpath'=>$file->getPathBase64()));?>" title="<?=$file->getName();?>"class="<? if ($file->getExtension() != "") { echo $file->getExtension(); }?>">
							<?=$file->getSysInfos("name");?> <? if ($file->getLastVersionInfo("versionid")>1) { echo "v".$file->getLastVersionInfo("versionid"); }?></a>
						</span>
						(<a href="<?=kurl(array('app' => 'fileshare', 'page'=>"download", 'filename'=>$file->getPathBase64()));?>" title="<?=_('DOWNLOAD');?> <?=$file->getName();	?>"><span class="downloadlink"><span><?=_('DOWNLOAD');?></span></span></a>)
						<br />
						<? if ($file->getLastVersionInfo("description") != "") { ?>
						<span class="description">
							<label for="description"><?=_('DESCRIPTION');?> :</label>
							<span name="description"><?=$file->getLastVersionInfo("description");?></span>
						</span>
						<? } ?>
						<span class="ago">
						<?=_('AGO1');?>
						<? $since = $file->getSecondsSinceLastUpdate(); ?>
						<? if ($since > 86400) { ?>
							<?=number_format($since/86400, 0);?> <?=_('DAY');?><? if ($since/86400 >= 2) { ?><?=_('S');?><? } ?>
						<? } elseif ($since > 3600) { ?>
							<?=number_format($since/3600, 0);?> <?=_('HOUR');?><? if ($since/3600 >= 2) { ?><?=_('S');?><? } ?>
						<? } elseif ($since > 60) { ?>
							<?=number_format($since/60, 0);?> <?=_('MINUTE');?><? if ($since/60 >= 2) { ?><?=_('S');?><? } ?>
						<? } else { ?>
							<?=$since;?> <?=_('SECOND');?><? if ($since >= 2) { ?> <?=_('S');?> <? } ?>
						<? } ?>
						<?=_('AGO2');?>
						</span>
						<span class="uploader">
							<label for="uploader"><?=_('UPLOADED_BY');?> :</label>
							<span name="uploader">
								<a href="<?=kurl(array('app'=>"annuaire", 'username'=>$file->getLastVersionInfo("user")->getLogin()));?>"><?=userlink(array('user'=>$file->getLastVersionInfo("user"), 'showpicture'=>true));?></a>
							</span>
						</span>
					</li>
				<?
				}
				?>
			</ul>
			<? } ?>
	</div>
	<div class="box share fileshare">
			<h2><?=_('FILESHARE');?> : <?=_('MOSTDOWNLOADEDFILES');?></h2>
			<? if (count($this->vars['mostDownloadedFiles']) > 0) { ?>
			<ul>
				<?
				foreach ($this->vars['mostDownloadedFiles'] as $file) 
				{
					if ($linecolor == 'one')
						$linecolor = 'two';
					else
						$linecolor = 'one';
				?>
					<li class="<?=$linecolor;?>">
						<span class="name">
							<a href="<?=kurl(array('app'=>'fileshare', 'page'=>"details", 'elementpath'=>$file->getPathBase64()));?>"
							title="<?=$file->getName();?>" class="<? if (($file->getExtension() != "")) { ?><?=$file->getExtension();?><? } ?>"><?=$file->getSysInfos("name");?>
							<? if ($file->getLastVersionInfo("versionid")>1) { ?>(v<?=$file->getLastVersionInfo("versionid");?>)<? } ?></a>
						</span>
						(<a href="<?=kurl(array('app' => 'fileshare', 'page'=>"download", 'filename'=>$file->getPathBase64()));?>" title="<?=_('DOWNLOAD');?> <?=$file->getName();?>">
							<span class="downloadlink"><span><?=_('DOWNLOAD');?></span></span>
						</a>)
						<br />
						<? if ($file->getLastVersionInfo("description") != "") { ?>
						<span class="description">
							<label for="description"><?=_('DESCRIPTION');?> :</label>
							<span name="description"><?
								echo substr(strip_tags($file->getLastVersionInfo("description")), 0, 200);
								if (strlen(strip_tags($file->getLastVersionInfo("description"))) > 200) {
									echo "...";
								}
								?></span>
						</span>
						<br />
						<? } ?>
						<span class="hits">
							<label for="hits"><?=_('DOWNLOAD_COUNT');?> :</label>
							<span name="hits"><?=$file->getHitsByVersion();?></span>
						</span>
						<br />
						<span class="uploader">
							<label for="uploader"><?=_('UPLOADED_BY');?> :</label>
							<span name="uploader">
								<a href="<?=kurl(array('app'=>"annuaire", 'username'=>$file->getLastVersionInfo("user")->getLogin()));?>"><?=userlink(array('user'=>$file->getLastVersionInfo("user"), 'showpicture'=>true));?></a>
							</span>
						</span>
					</li>
				<? } ?>
			</ul>
			<? } ?>
		</div>

		<div class="box organize calendar">
			<h2><?=_('CALENDAR');?></h2>
			<ul>
			<?
			foreach($this->vars['today_events'] as $event)
			{
			?>
				<li>
				<? 
				if $event->parent
				{
				?>
								<span class="event_hours"><?=date("%x %Hh%M", $event->startdate);?> -
								<?=date("%x %Hh%M", $event->stopdate);?></span>
				<?
				}
				else
				{
				?>
								<span class="event_hours"><?=date("%Hh%M", $event->startdate);?> -
								<?=date("%Hh%M", $event->stopdate);?></span>
				<?
				}
				?>
				<?=$event->summary;?>
				</li>
			<?
			}
			?>
			</ul>
		</div>
		
		<div class="box communicate email">
			<h2><?=_('EMAIL');?></h2>
		</div>
	</div>
</div>

<style type="text/css">
#statichome .maxi {
	float: left;
	width: 49%;
}
html>body #statichome .maxi {

}
#statichome .mini {
	float: right;
	width: 50%;
}
#statichome .mini div.box {
	float: left;
	width: 46%;
	margin-right: 2%;
	font-size: 0.9em;
	
}
#container #statichome h2 {
	color: #fff;
	text-align: center;
	background-color: #000;
}
#container #statichome .box.communicate h2{
	background-color: #000079;
}
#container #statichome .box.organize h2{
	background-color: #ff9900;
}
#container #statichome .box.share h2 {
	background-color: #00a200;
}
#container #statichome .box.jobs h2{
	background-color: #a20095;
}

#container #statichome ul {
	padding: 0;
	margin: 0;
	border: 0;
	list-style: none;
}
#container #statichome ul li {
	padding: 0;
	margin: 0;
	border: 0;
}
#container #statichome .box.communicate ul li.one {
	background-color: #e0e0ff;
}
#container #statichome .box.communicate ul li.two {
	background-color: #f0f0ff;
}
#container #statichome .box.share ul li.one {
	background-color: #e0f9e0;
}
#container #statichome .box.share ul li.two {
	background-color: #f0fff0;
}


</style>