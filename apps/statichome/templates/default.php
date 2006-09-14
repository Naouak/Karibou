<div id="statichome">
<?
$linecolor = '';
if($this->vars['permission'] <= _READ_ONLY_)
{
	//L'utilisateur n'est pas connecté
	echo _('STATICHOME_WELCOME_MSG');
}
else
{
?>
	<div class="maxi">
		<div class="lastevents">
			<h2><?=_('LASTEVENTS');?></h2>
			<ul>
			<?
			//foreach ($this->vars['iEvents'] as $iEvent)
			reset($this->vars['iEvents']);
			$iEvent = current($this->vars['iEvents'] );
			$nb = 0;
			do
			{
					if ( ($linecolor == 'one') )
					{
						$linecolor = 'two';
					}
					else
					{
						$linecolor = 'one';
					}

					if ($iEvent['type'] == 'article')
					{
						$liclass = 'communicate';
					}
					elseif ($iEvent['type'] == 'file')
					{
						$liclass = 'share';
					}
					elseif ($iEvent['type'] == 'job')
					{
						$liclass = 'jobs';
					}
			?>
					<li class="<?=$liclass;?> <?=$linecolor;?>">
					
			<?
				$since = $iEvent['secondsago'];
				echo '<span class="ago">';
				echo _('AGO1').' ';
					if ($since > 86400)
					{
						echo number_format($since/86400,0).' '._('DAY');
						echo (number_format($since/86400,0) >= 2)?_('S'):'';
					}
					elseif ($since > 3600)
					{
						echo number_format($since/3600,0).' '._('HOUR');
						echo (number_format($since/3600,0) >= 2)?_('S'):'';
					}
					elseif ($since > 60)
					{
						echo number_format($since/60,0).' '._('MINUTE');
						echo (number_format($since/60,0) >= 2)?_('S'):'';
					}
					else
					{
						echo $since.' '._('SECOND');
						echo ($since >= 2)?_('S'):'';
					}
				echo _('AGO2');
				echo '</span>';
				
				if ($iEvent['type'] == 'article')
				{
					echo "<span class=\"title\">"._('NEWS')." : <a href=\"".kurl(array('app'=>"news", 'page'=>"view", 'id'=>$iEvent['object']->getId()))."\">".$iEvent['object']->getTitle()."</a></span>";
				}
				elseif ($iEvent['type'] == 'file')
				{
					echo "<span class=\"title\">"._('FILESHARE')." : <a href=\"".kurl(array('app' => 'fileshare', 'page'=>"details", 'elementpath'=>$iEvent['object']->getPathBase64()))."\">".$iEvent['object']->getSysInfos('name')."</a></span>";
				}
				elseif ($iEvent['type'] == 'job')
				{
					echo "<span class=\"title\">"._('NETJOBS')." : <a href=\"".kurl(array('app' => 'netjobs', 'page'=>"jobdetails", 'jobid'=>$iEvent['object']->getInfo("id")))."\">";
					if ($iEvent['object']->getInfo("title") != "")
					{
						echo $iEvent['object']->getInfo("title");
					}
					else
					{
						echo _('JOB_EMPTYTITLE');
					}
					echo "</a></span>";
				}
				
				//echo '['.$iEvent['type'].'] ';

				echo "</li>";
				$nb++;
			} while ( ($iEvent = next($this->vars['iEvents'])) && ($nb < 20) );
			?>
			</ul>
		</div>
	</div>
	<div class="mini">
		<div class="box communicate news">
			<h2><a href="<?=kurl(array('app'=>'news'));?>"><?=_('NEWS');?></a></h2>
			<ul>
				<?
					$linecolor = '';
					//foreach ($this->vars['articles'] as $article)
					reset($this->vars['articles']);
					$article = current($this->vars['articles']);
					$nb = 0;
					do
					{
						if ($linecolor == 'one')
							$linecolor = 'two';
						else
							$linecolor = 'one';
				?>
					<li class="<?=$linecolor;?>"><?/*=kurl(array('app'=>'news', 'id'=>$article->getId()));*/?>
						<a href="<?=kurl(array('app'=>"news", 'page'=>"view", 'id'=>$article->getId()))?>"><?=$article->getTitle();?></a>
						<br />
						<?/*=_('BY');?> <?=userlink(array('user'=>$article->getAuthorObject(), 'showpicture'=>true));?>
						<br />
						<?*/?>
						<?
							echo substr(strip_tags($article->getContentXHTML()), 0, 100);
							if (strlen(strip_tags($article->getContentXHTML())) > 100) {
								echo "...";
							}
						?>
					</li>
				<?
					$nb++;
					} while ( ($article = next($this->vars['articles'])) && ($nb < 5) );
				?>
			</ul>
		</div>

		<div class="box organize calendar">
			<h2><a href="<?=kurl(array('app'=>'calendar'));?>"><?=_('CALENDAR');?></a></h2>
		<?
		if (count($this->vars['cals']) == 0)
		{
			echo _('NOCALENDAR_SHORT');
			echo "<br /><a href=\"".kurl(array('app' => 'calendar',  'page'=>'manage'))."\">"._('MANAGE')."</a>";
		}
		else
		{
		?>
			<h3><?=_('TODAY');?></h3>
			<ul>
			<?
			if (count($this->vars['today_events']) != 0)
			foreach($this->vars['today_events'] as $event)
			{

			?>
				<li>
				<? 
				if ($event->parent)
				{
				?>
								<span class="event_hours"><?=date("H:i", $event->o_start->getTimestamp());?> -
								<?=date("H:i", $event->o_stop->getTimestamp());?></span>
				<?
				}
				else
				{
				?>
								<span class="event_hours"><?=date("H:i", $event->o_start->getTimestamp());?> -
								<?=date("H:i", $event->o_stop->getTimestamp());?></span>
				<?
				}
				?>
				: <a href="<?=kurl(array('app' => 'calendar', 'page' => 'view', 'cal_id' => $event->calendarid, 'year' => $event->o_start->getYear(), 'month' => $event->o_start->getMonth(), 'day' => $event->o_start->getDay()));?>"><?=$event->summary;?></a>
				</li>
			<?
			}
			else
			{
				echo _('CALENDAR_NOEVENT');
			}
			?>
			</ul>
			<h3><?=_('TOMORROW');?></h3>
			<ul>
			<?
			if (count($this->vars['nextday_events']) != 0)
			foreach($this->vars['nextday_events'] as $event)
			{

			?>
				<li>
				<? 
				if ($event->parent)
				{
				?>
								<span class="event_hours"><?=date("H:i", $event->o_start->getTimestamp());?> -
								<?=date("H:i", $event->o_stop->getTimestamp());?></span>
				<?
				}
				else
				{
				?>
								<span class="event_hours"><?=date("H:i", $event->o_start->getTimestamp());?> -
								<?=date("H:i", $event->o_stop->getTimestamp());?></span>
				<?
				}
				?>
				: <a href="<?=kurl(array('app' => 'calendar', 'page' => 'view', 'cal_id' => $event->calendarid, 'year' => $event->o_start->getYear(), 'month' => $event->o_start->getMonth(), 'day' => $event->o_start->getDay()));?>"><?=$event->summary;?></a>
				</li>
			<?
			}
			else
			{
				echo _('CALENDAR_NOEVENT');
			}
			?>
			</ul>
		<?
		}
		?>
		</div>

		<div class="box share fileshare">
			<h2><a href="<?=kurl(array('app'=>'fileshare'));?>"><?=_('FILESHARE');?> : <?=_('LASTADDEDFILES');?></a></h2>
			<? if (count($this->vars['lastAddedFiles']) > 0) { ?>
			<ul>
				<?
				$linecolor = '';
				//foreach ($this->vars['lastAddedFiles'] as $file)
				$nb = 0;
				reset ($this->vars['lastAddedFiles']);
				$file = current ($this->vars['lastAddedFiles']);
				do
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
						(<a href="<?=kurl(array('app' => 'fileshare', 'page'=>"download", 'filename'=>$file->getPathBase64()));?>" title="<?=_('DOWNLOAD');?> <?=$file->getName();	?>"><span><?=_('DOWNLOAD');?></span></a>)
						<br />
						<?
						/*
						<span class="ago">
						<?=_('AGO1');?>
						<? $since = $file->getSecondsSinceLastUpdate(); ?>
						<? if ($since > 86400) { ?>
							<?=number_format($since/86400, 0);?> <?=_('DAY');?><? if (number_format($since/86400,0) >= 2) { echo _('S'); } ?>
						<? } elseif ($since > 3600) { ?>
							<?=number_format($since/3600, 0);?> <?=_('HOUR');?><? if (number_format($since/3600,0) >= 2) { echo _('S');  } ?>
						<? } elseif ($since > 60) { ?>
							<?=number_format($since/60, 0);?> <?=_('MINUTE');?><? if (number_format($since/60,0) >= 2) { echo _('S'); } ?>
						<? } else { ?>
							<?=$since;?> <?=_('SECOND');?><? if ($since >= 2) { ?> <?=_('S');?> <? } ?>
						<? } ?>
						<?=_('AGO2');?>
						</span>
						<br />
						*/
						?>

						<? if ($file->getLastVersionInfo("description") != "") { ?>
						<span>
							<?=$file->getLastVersionInfo("description");?>
						</span>
						<? } ?>
						<?/*
						<span class="uploader">
							<label for="uploader"><?=_('UPLOADED_BY');?> :</label>
							<span name="uploader">
								<?=userlink(array('user'=>$file->getLastVersionInfo("user"), 'showpicture'=>true));?><?/*</a>?>
							</span>
						</span>
						*/?>
					</li>
				<?
				$nb++;
				} while ( ($file = next($this->vars['lastAddedFiles'])) && ($nb < 5) );
				?>
			</ul>
			<? } ?>
		</div>
		<div class="box share fileshare">
			<h2><a href="<?=kurl(array('app'=>'fileshare'));?>"><?=_('FILESHARE');?> : <?=_('MOSTDOWNLOADEDFILES');?></a></h2>
			<? if (count($this->vars['mostDownloadedFiles']) > 0) { ?>
			<ul>
				<?
				//foreach ($this->vars['mostDownloadedFiles'] as $file) 
				$nb = 0;
				reset ($this->vars['mostDownloadedFiles']);
				$file = current ($this->vars['mostDownloadedFiles']);
				do
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
							<span><?=_('DOWNLOAD');?></span>
						</a>)
						<br />
						<? if ($file->getLastVersionInfo("description") != "") { ?>
							<span><?
								echo substr(strip_tags($file->getLastVersionInfo("description")), 0, 200);
								if (strlen(strip_tags($file->getLastVersionInfo("description"))) > 200) {
									echo "...";
								}
								?></span>
						<br />
						<? } ?>
						<span>
							<label for="hits"><?=_('DOWNLOAD_COUNT');?> :</label>
							<span name="hits"><?=$file->getHitsByVersion();?></span>
						</span>
						<?/*
						<br />
						<span class="uploader">
							<label for="uploader"><?=_('UPLOADED_BY');?> :</label>
							<span name="uploader">
								<?=userlink(array('user'=>$file->getLastVersionInfo("user"), 'showpicture'=>true));?>
							</span>
						</span>
						*/
						?>
					</li>
				<?
				$nb++;
				} while ( ($file = next($this->vars['mostDownloadedFiles'])) && ($nb < 5) );
				?>
			</ul>
			<? } ?>
		</div>
		<?
		/*
		<div class="box communicate email">
			<h2><?=_('EMAIL');?></h2>
		</div>
		*/
		?>
		<div class="box jobs">
			<h2><a href="<?=kurl(array('app'=>'netjobs'));?>">NetJobs : <?=_('LASTJOBS');?></a></h2>
			<ul> <? /* class="joblist"> */?>
		<?
			//foreach ($this->vars['jobs'] as $myJob)
			$nb = 0;
			reset ($this->vars['jobs']);
			$myJob = current ($this->vars['jobs']);
			do
			{
					if ($linecolor == 'one')
						$linecolor = 'two';
					else
						$linecolor = 'one';
		?>
					<li class="<?=$linecolor;?>">
					<span class="title"><a href="<?=kurl(array('app' => 'netjobs', 'page'=>"jobdetails", 'jobid'=>$myJob->getInfo("id")));?>"><?
					if ($myJob->getInfo("title") != "")
					{
						echo $myJob->getInfo("title");
					}
					else
					{
						echo _('JOB_EMPTYTITLE');
					}?></a></span>
					<?
					/*
					<span class="ago">
					<?
						$since = $myJob->getSecondsSinceLastUpdate();
					?>
					<?
					echo _('AGO1').' ';
						if ($since > 86400)
						{
							echo number_format($since/86400,0).' '._('DAY');
							echo (number_format($since/86400,0) >= 2)?_('S'):'';
						}
						elseif ($since > 3600)
						{
							echo number_format($since/3600,0).' '._('HOUR');
							echo (number_format($since/3600,0) >= 2)?_('S'):'';
						}
						elseif ($since > 60)
						{
							echo number_format($since/60,0).' '._('MINUTE');
							echo (number_format($since/60,0) >= 2)?_('S'):'';
						}
						else
						{
							echo $since.' '._('SECOND');
							echo ($since >= 2)?_('S'):'';
						}
					echo _('AGO2');
					?>
					</span>
					*/
					?>
					<span class="company">
					<?
					if ($myJob->getCompanyInfo("id") !== FALSE)
					{
						echo _('JOB_IN').' ';
						echo '<a href="'.kurl(array('app' => 'netjobs', 'page'=>"companydetails", 'companyid'=>$myJob->getCompanyInfo("id"))).'">'.$myJob->getCompanyInfo("name").'</a>';
					}
					else
					{
						echo _('JOB_IN').' ';
						echo '<em>'._('COMPANYUNKNOWN').'</em>';
					}
					?>
					</span>
				</li>
		<?
			$nb++;
			} while ( ($myJob = next($this->vars['jobs'])) && ($nb < 5) );
		?>
			</ul>
		</div>
		
	</div>
</div>
<?
}
?>
<style type="text/css">
#statichome .maxi {
	float: left;
	width: 49%;
	overflow: hidden;
}
html>body #statichome .maxi {

}
#statichome .mini {
	float: right;
	width: 50%;
	overflow: hidden;
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
#container #statichome h2 a {
	color: #fff;
	text-align: center;
	font-weight: bold;
}
#container #statichome .maxi ul li {
	border-bottom: 1px solid #ddd;
	padding: 3px
}
#container #statichome .maxi ul li.one {
	background-color: #f9f9f9;
}
#container #statichome .maxi ul li.two {
	background-color: #eee;
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

#container #statichome .box.communicate ul li.one, #container #statichome .maxi ul li.one.communicate {
	background-color: #f0f0ff;
}
#container #statichome .box.communicate ul li.two, #container #statichome .maxi ul li.two.communicate {
	background-color: #e0e0ff;
}
#container #statichome .box.share ul li.one, #container #statichome .maxi ul li.one.share {
	background-color: #e0f9e0;
}
#container #statichome .box.share ul li.two, #container #statichome .maxi ul li.two.share {
	background-color: #f0fff0;
}
#container #statichome .box.jobs ul li.one, #container #statichome .maxi ul li.one.jobs {
	background-color: #f9e0f9;
}
#container #statichome .box.jobs ul li.two, #container #statichome .maxi ul li.two.jobs {
	background-color: #fff0ff;
}
#container #statichome {
}
#container #statichome .maxi ul li .ago {
	display: block;
	float: right;
	width: 20%;
	color: #999;
	font-style: italic;
	text-align: right;
	white-space: nowrap;
}
#container #statichome .mini ul li {
	border: 0px;
	margin: 0px;
	padding: 0px;
	background-image: none;
}
#container #statichome .mini ul li a {
	display: inline;
	background-image: none;
	padding: 0px;
}
#container #statichome .mini ul li span {
	display: inline;
}
</style>