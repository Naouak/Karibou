						<? $user = $this->vars['currentUser']; ?>
						<? hook(array('name'=>"page_contenu_end")); ?>
					</div>
				</div>
			</div>
			<?/* DEBUT : Barre utilisateur */?>
			<div id="account">
				<?
				if ($user->isLogged())
				{
				?>
				<span class="user">
					<strong><?=$user->getDisplayName();?></strong>
				</span>
				<ul>
					<? hook(array('name'=>"footer_account_start")); ?>
					<li class="profile"><a href="<?=kurl(array('app'=>"annuaire", 'username'=>$user->getLogin(), 'act'=>'edit'));?>"><?=_('EDITPROFILE');?></a></li>
					<li class="preferences"><a href="<?=kurl(array('app'=>"preferences", 'page'=>"")); ?>"><?=_('PREFERENCES');?></a></li>
					<li class="logout"><a href="<?=kurl(array('app'=>"login",'page'=>"logout"));?>"><?=_('LOGOUT');?></a></li>
				</ul>
				<?
				}
				else
				{
				?>
				<form class="login" action="<?=kurl(array('app'=>"login"));?>" method="post">
					<span class="user">
						<strong><?=_('TITLE_AUTH');?></strong>
					</span>
					<label for="_user"><?=_('USERNAME');?></label>
					<input type="text" name="_user" class="email" />
					<label for="_pass"><?=_('PASSWORD');?></label>
					<input type="password" name="_pass" class="password" />
					
					<input type="submit" class="button" value="              Login" />
					
					<?/*<a href="{kurl application="login" page="forgotten"}" class="forgotten"><span>Oubli?</span></a>*/?>
					
					<?/*<a href="#" class="inscription"><span>Inscription</span></a>*/?>
				</form>
				<?
				}
				?>
			</div>
			<? /*FIN : Barre utilisateur */ ?>

			<? /* DEBUT : Module de recherche */ ?>
			<?
				//Problème avec les permissions ? On va juste vérifier que l'utilisateur est connecté
				//if ($this->vars['permission']->get('search') > _NO_ACCESS_)
				if ($user->isLogged())
				{
			?>
			<form action="<?=kurl(array('app'=>"search"));?>" method="post" id="search">
				<input type="text" class="keywords" name="keywords">
				<input type="submit" class="button" name="go" value="<?=_('SEARCH');?>">
			</form>
			<?
				}
			?>
			<? /* FIN : Module de recherche */ ?>


			<? /* DEBUT : Navigation : Barre avec onglets */ ?>
			<div id="siteNavigation">
				<div id="siteNavigationCategories">
						<ul>
								<li id="linkCommunicate"><h3><a href="#menuCommunicate" onclick="ShowAppsLinks('menuCommunicate'); return false;"><span><?=_('NAV_COMMUNICATE');?></span></a></h3></li>
								<li id="linkOrganize"><h3><a href="#menuOrganize" onclick="ShowAppsLinks('menuOrganize'); return false;"><span><?=_('NAV_ORGANISE');?></span></a></h3></li>
								<li id="linkShare"><h3><a href="#menuShare" onclick="ShowAppsLinks('menuShare'); return false;"><span><?=_('NAV_SHARE');?></span></a></h3></li>
								<li id="linkJobs"><h3><a href="#menuJobs" onclick="ShowAppsLinks('menuJobs'); return false;"><span><?=_('NAV_JOB');?></span></a></h3></li>
								<?
								/*
									if ($this->vars['permission']->get('gettext') > _NO_ACCESS_)
									{
								?>
								<li id="linkAdmin"><h3><a href="#" onclick="ShowAppsLinks('menuAdmin'); return false;"><span>Administrer</span></a></h3></li>
								<?
									}
								*/
								?>
						</ul>
				</div>

				<div class="siteNavigationApps" id="menuCommunicate" >
					<h3>Communiquer</h3>
					<ul>
							<li class="first-child"><h4><a href="<?=kurl(array('app'=>"news"));?>"><?=_('APP_NEWS');?></a></h4></li>
							<li><h4><a href="<?=kurl(array('app'=>"annuaire"));?>"><?=_('APP_DIRECTORY');?></a></h4></li>
							<li><h4><a href="<?=kurl(array('app'=>"mail"));?>"><?=_('APP_EMAIL');?></a></h4></li>
							<? /* <li><h4><a href="<?=kurl(array('app'=>"flashmail"));?>"><?=_('APP_FLASHMAILS');?></a></h4></li> */?>
							<? /* <li><h4><a href="<?=kurl(array('app'=>"gmailreader"));?>"><?=_('APP_GMAILREADER');?></a></h4></li> */ ?>
							<? /* <li><h4><a href="<?=kurl(array('app'=>"minichat"));?>"><?=_('APP_MINICHAT');?></a></h4></li> */ ?>
					</ul>
				</div>
				<div class="siteNavigationApps" id="menuOrganize">
					<h3>S'organiser</h3>
					<ul>
							<li class="first-child"><h4><a href="<?=kurl(array('app'=>"calendar"));?>"><?=_('APP_CALENDAR');?></a></h4></li>
					</ul>
				</div>
				<div class="siteNavigationApps" id="menuShare">
					<h3>Partager</h3>
					<ul>
							<li class="first-child"><h4><a href="<?=kurl(array('app'=>"fileshare"));?>"><?=_('APP_FILESHARE');?></a></h4></li>
							<? /* <li><h4><a href="<?=kurl(array('app'=>"wiki"));?>">Wiki</a></h4></li> */?>
					</ul>
				</div>
				<div class="siteNavigationApps" id="menuJobs">
					<h3>Emploi</h3>
					<ul>
							<li class="first-child"><h4><a href="<?=kurl(array('app'=>"netcv"));?>"><?=_('APP_NETCV');?></a></h4></li>
							<li><h4><a href="<?=kurl(array('app'=>"netjobs"));?>"><?=_('APP_NETJOBS');?></a></h4></li>
					</ul>
				</div>
				<div class="siteNavigationApps" id="menuAdmin">
				<? 
				/*
					<h3>Admin</h3>
					<ul>
							<li class="first-child"><h4><a href="<?=kurl(array('app'=>"permissions"));?>">Permissions</a></h4></li>
							<li><h4><a href="<?=kurl(array('app'=>"admin"));?>">Admin</a></h4></li>
							<li><h4><a href="<?=kurl(array('app'=>"gettext"));?>">GetText</a></h4></li>
					</ul>
				*/
				?>
				</div>
			</div>
			<? /* FIN : Navigation : Barre avec onglets */ ?>
			
			<div id="miniapps"></div>
			
		</div>
		<div id="footer">
			<ul><?if (isset($GLOBALS['config']['footer']['message']) && $GLOBALS['config']['footer']['message'] !== '')
					{?>
				<li class="first">
					<?=_('INTRANET_PROVIDED_BY')?> <?=$GLOBALS['config']['footer']['message'];?>
				</li><?}?>
				<li<? if (!(isset($GLOBALS['config']['footer']['message']) && $GLOBALS['config']['footer']['message'] !== '')) {?> class="first"<?}?>>
					Intranet <?=_('BY')?> <a href="http://www.karibou.org" title="Karibou">Karibou</a></li>
				<li><a href="<?=kurl(array('app'=>"contact"));?>">Contact</a></li>
				<li><a href="<?=kurl(array('app'=>"credits"));?>"><?=_('APP_CREDITS');?></a></li>
			</ul>
		</div>

		<div id="dynamics"></div>

		<div id="flashmail_headerbox_unreadlist" class="flashmail dontshow">
			<? hook(array('name'=>"flashmail_unreadlist")); ?>
		</div>
		<div id="flashmail_headerbox_answer" class="dontshow"><?=_('LOADING');?></div>
		<div id="blackhole" class="blackhole"></div>
	
	

	</body>
</html>