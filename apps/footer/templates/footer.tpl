{hook name="page_contenu_end"}
					</div><!-- content -->
				</div><!-- content1 -->
			</div> <!-- main -->
			{* DEBUT : Barre utilisateur *}
			<div id="account">
				{if ($islogged)}
				<span class="user">
					<strong>{$user->getDisplayName()}</strong>
				</span>
				<ul style="width: 700px; padding-left: 0px;">
					{hook name="footer_account_start"}
					<li class="profile"><a href="{kurl app="annuaire" username="$login" act="edit"}">##EDITPROFILE##</a></li>
					<li class="preferences"><a href="{kurl app="preferences"}">##PREFERENCES##</a></li>
					<li class="logout"><a href="{kurl app="login" page="logout"}">##LOGOUT##</a></li>
{if ($hasMenu)} 
	<li>
	<ins class="karibouMenu">
		Menu Karibou
		<ul>
			{hook name="header_menu"}
		</ul>
	</ins>
	</li>
{/if}
				</ul>
				{else}
				<form class="login" action="{kurl app="login"}" method="post">
					<span class="user">
						<strong>##TITLE_AUTH##</strong>
					</span>
					<label for="_user">##USERNAME##</label>
					<input type="text" name="_user" class="email" />
					<label for="_pass">##PASSWORD##</label>
					<input type="password" name="_pass" class="password" />
					
					<input type="submit" class="button" value="              Login" />
					</form>
				{/if}
			</div><!-- account -->
			{if ($islogged)}
			<form action="{kurl app="search"}" method="post" id="search">
				<input type="text" class="keywords" name="keywords">
				<input type="submit" class="button" name="go" value="##SEARCH##">
			</form>
			{/if}
		</div><!-- container -->
		<div id="footer">
			<ul>
				{if ($message != "")}
				<li class="first">
					##INTRANET_PROVIDED_BY## {$message}
				</li>
				{/if}
				<li{if ($message == "")} class="first"{/if}>
					Intranet ##BY## <a href="http://www.karibou.org" title="Karibou">Karibou</a></li>
				<li><a href="{kurl app="contact"}">Contact</a></li>
				<li><a href="{kurl app="credits"}">##APP_CREDITS##</a></li>
			</ul>
		</div><!-- footer -->
	</body>
</html>

