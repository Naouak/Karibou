					{hook name="page_contenu_end"}
				</div>
			</div>
		</div>
		{* DEBUT : Barre utilisateur *}
		<ul id="footer">
			<li class="first">
				Intranet ##BY## <a href="http://www.karibou.org" title="Karibou">Karibou</a>
			</li>
			<li>
				<a href="{kurl app="contact"}">Contact</a>
			</li>
			<li>
				<a href="{kurl app="credits"}">##APP_CREDITS##</a>
			</li>
		</ul>
		
		<div id="account">
			{if ($currentUser->isLogged())}
			<span class="user">
				<strong>{$currentUser->getDisplayName()}</strong>
			</span>
			<ul>
				{hook name="footer_account_start"}
				<li class="profile"><a href="{kurl app="annuaire" username=$currentUser->getLogin() act='edit'}">##EDITPROFILE##</a></li>
				<li class="preferences"><a href="{kurl app="preferences" page=""}">##PREFERENCES##</a></li>
				<li class="logout"><a href="{kurl app="login" page="logout"}">##LOGOUT##</a></li>
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
				
				{*<a href="{kurl application="login" page="forgotten"}" class="forgotten"><span>Oubli?</span></a>*}
				
				{*<a href="#" class="inscription"><span>Inscription</span></a>*}
			</form>
			{/if}
		</div>
		{* FIN : Barre utilisateur *}


		{* DEBUT : Navigation : Barre avec onglets *}
		<div id="siteNavigation">
			<div id="siteNavigationCategories">
					<ul>
							<li id="linkCommunicate"><h3><a href="#" onclick="ShowAppsLinks('menuCommunicate'); return false;"><span>Communiquer</span></a></h3></li>
							<li id="linkOrganize"><h3><a href="#" onclick="ShowAppsLinks('menuOrganize'); return false;"><span>S'organiser</span></a></h3></li>
							<li id="linkShare"><h3><a href="#" onclick="ShowAppsLinks('menuShare'); return false;"><span>Partager</span></a></h3></li>
							<li id="linkJobs"><h3><a href="#" onclick="ShowAppsLinks('menuJobs'); return false;"><span>Emploi</span></a></h3></li>
							<li id="linkAdmin"><h3><a href="#" onclick="ShowAppsLinks('menuAdmin'); return false;"><span>Administrer</span></a></h3></li>
					</ul>
			</div>

			<div class="siteNavigationApps" id="menuCommunicate" >
				<h3>Communiquer</h3>
				<ul>
						<li class="first-child"><h4><a href="{kurl app="news"}">##APP_NEWS##</a></h4></li>
						<li><h4><a href="{kurl app="annuaire"}">##APP_DIRECTORY##</a></h4></li>
						<li><h4><a href="{kurl app="mail"}">##APP_EMAIL##</a></h4></li>
						{* <li><h4><a href="{kurl app="flashmail"}">##APP_FLASHMAILS##</a></h4></li> *}
						<li><h4><a href="{kurl app="gmailreader"}">##APP_GMAILREADER##</a></h4></li>
						<li><h4><a href="{kurl app="minichat"}">##APP_MINICHAT##</a></h4></li>
				</ul>
			</div>
			<div class="siteNavigationApps" id="menuOrganize">
				<h3>S'organiser</h3>
				<ul>
						<li class="first-child"><h4><a href="{kurl app="calendar"}">##APP_CALENDAR##</a></h4></li>
				</ul>
			</div>
			<div class="siteNavigationApps" id="menuShare">
				<h3>Partager</h3>
				<ul>
						<li class="first-child"><h4><a href="{kurl app="fileshare"}">##APP_FILESHARE##</a></h4></li>
						<li><h4><a href="{kurl app="wiki"}">Wiki</a></h4></li>
				</ul>
			</div>
			<div class="siteNavigationApps" id="menuJobs">
				<h3>Emploi</h3>
				<ul>
						<li class="first-child"><h4><a href="{kurl app="netcv"}">##APP_NETCV##</a></h4></li>
						<li><h4><a href="{kurl app="netjobs"}">##APP_NETJOBS##</a></h4></li>
				</ul>
			</div>
			<div class="siteNavigationApps" id="menuAdmin">
				<h3>Admin</h3>
				<ul>
						<li class="first-child"><h4><a href="{kurl app="permissions"}">Permissions</a></h4></li>
						<li><h4><a href="{kurl app="admin"}">Admin</a></h4></li>
						<li><h4><a href="{kurl app="gettext"}">GetText</a></h4></li>
				</ul>
			</div>
		</div>
		{* FIN : Navigation : Barre avec onglets *}
	</div>


	<div id="flashmail_headerbox_unreadlist" class="flashmail dontshow">{hook name="flashmail_unreadlist"}</div>
	<div id="flashmail_headerbox_answer" class="dontshow">##LOADING##</div>
	<div id="blackhole" class="blackhole"></div>
	
	</body>
</html>
