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
				<ul style="width: 700px; padding-left: 0px;">
					<? hook(array('name'=>"footer_account_start")); ?>
					<li class="profile"><a href="<?=kurl(array('app'=>"annuaire", 'username'=>$user->getLogin(), 'act'=>'edit'));?>"><?=_('EDITPROFILE');?></a></li>
					<li class="preferences"><a href="<?=kurl(array('app'=>"preferences", 'page'=>"")); ?>"><?=_('PREFERENCES');?></a></li>
					<li class="logout"><a href="<?=kurl(array('app'=>"login",'page'=>"logout"));?>"><?=_('LOGOUT');?></a></li>
<?
if (isset($this->hookManager->hooks['header_menu'])) {
?>
	<li>
	<ins class="karibouMenu">
		Menu Karibou
		<ul>
			<? hook(array('name'=>"header_menu")); ?>
		</ul>
	</ins>
	</li>
<?
}
?>
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
	</body>
</html>
