<?php
$user = $this->vars['currentUser'];
hook(array('name'=>"page_contenu_end"));
?>
					</div>
				</div>
			</div>
			<?php /* DEBUT : Barre utilisateur */?>
			<div id="account">
				<?php
				if ($user->isLogged())
				{
				?>
				<span class="user">
					<strong><?php echo $user->getDisplayName();?></strong>
				</span>
				<ul style="width: 700px; padding-left: 0px;">
					<?php hook(array('name'=>"footer_account_start")); ?>
					<li class="profile"><a href="<?php echo kurl(array('app'=>"annuaire", 'username'=>$user->getLogin(), 'act'=>'edit'));?>"><?php echo _('EDITPROFILE');?></a></li>
					<li class="preferences"><a href="<?php echo kurl(array('app'=>"preferences", 'page'=>"")); ?>"><?php echo _('PREFERENCES');?></a></li>
					<li class="logout"><a href="<?php echo kurl(array('app'=>"login",'page'=>"logout"));?>"><?php echo _('LOGOUT');?></a></li>
<?php
if (isset($this->hookManager->hooks['header_menu'])) {
?>
	<li>
	<ins class="karibouMenu">
		Menu Karibou
		<ul>
			<?php hook(array('name'=>"header_menu")); ?>
		</ul>
	</ins>
	</li>
<?php
}
?>
				</ul>
				<?php
				}
				else
				{
				?>
				<form class="login" action="<?php echo kurl(array('app'=>"login"));?>" method="post">
					<span class="user">
						<strong><?php echo _('TITLE_AUTH');?></strong>
					</span>
					<label for="_user"><?php echo _('USERNAME');?></label>
					<input type="text" name="_user" class="email" />
					<label for="_pass"><?php echo _('PASSWORD');?></label>
					<input type="password" name="_pass" class="password" />
					
					<input type="submit" class="button" value="              Login" />
					</form>
				<?php
				}
				?>
			</div>
			<?php
				if ($user->isLogged())
				{
			?>
			<form action="<?php echo kurl(array('app'=>"search"));?>" method="post" id="search">
				<input type="text" class="keywords" name="keywords">
				<input type="submit" class="button" name="go" value="<?php echo _('SEARCH');?>">
			</form>
			<?php
				}
			?>
		</div>
		<div id="footer">
			<ul><?php if (isset($GLOBALS['config']['footer']['message']) && $GLOBALS['config']['footer']['message'] !== '')
					{?>
				<li class="first">
					<?php echo _('INTRANET_PROVIDED_BY')." ".$GLOBALS['config']['footer']['message'];?>
				</li><?php }?>
				<li<?php if (!(isset($GLOBALS['config']['footer']['message']) && $GLOBALS['config']['footer']['message'] !== '')) {?> class="first"<?php }?>>
					Intranet <?php echo _('BY')?> <a href="http://www.karibou.org" title="Karibou">Karibou</a></li>
				<li><a href="<?php echo kurl(array('app'=>"contact"));?>">Contact</a></li>
				<li><a href="<?php echo kurl(array('app'=>"credits"));?>"><?php echo _('APP_CREDITS');?></a></li>
			</ul>
		</div>
	</body>
</html>
