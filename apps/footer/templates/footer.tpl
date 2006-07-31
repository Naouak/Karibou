						{hook name="page_contenu_end"}
						</div>
					</div>
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
				<a href="{kurl app="credits"}">Credits</a>
			</li>
		</ul>
		<div id="account">
			{*
			<span class="user">
				<strong>Antoine Leclercq</strong>
			</span>
			<ul>
				<li class="preferences"><a href="#">Pr&eacute;f&eacute;rences</a></li>
				<li class="confidentiality"><a href="#">Confidentialit&eacute;</a></li>
				<li class="security"><a href="#">Securit&eacute;</a></li>
				<li class="logout"><a href="#">D&eacute;connexion   </a></li>
			</ul>
			*}
			<form class="login" action="{*kurl application="login" page="login"*}">
				<span class="user">
					<strong>Authentification</strong>
				</span>
				<label for="email">Email : </label>
				<input type="text" name="email" class="email" />
				<label for="password">Mot de passe : </label>
				<input type="password" name="password" class="password" />
				
				<input type="submit" class="button" value="              Login" />
				
				<a href="{*kurl application="login" page="forgotten"*}" class="forgotten"><span>Oubli?</span></a>
				
				{*<a href="#" class="inscription"><span>Inscription</span></a>*}
			</form>
		</div>
		{* FIN : Barre utilisateur *}
		{* DEBUT : Navigation : Barre avec onglets *}
		<div id="siteNavigation">
			<div id="siteNavigationCategories">
					<ul>
							<li id="linkCommunicate"><h3 onmouseover="ShowAppsLinks('menuCommunicate');"><a href="#"><span>Communiquer</span></a></h3></li>
							<li id="linkOrganize"><h3 onmouseover="ShowAppsLinks('menuOrganize');"><a href="#"><span>S'organiser</span></a></h3></li>
							<li id="linkShare"><h3 onmouseover="ShowAppsLinks('menuShare');"><a href="#"><span>Partager</span></a></h3></li>
							<li id="linkJobs"><h3 onmouseover="ShowAppsLinks('menuJobs');"><a href="#"><span>Emploi</span></a></h3></li>
							<li id="linkAdmin"><h3 onmouseover="ShowAppsLinks('menuAdmin');"><a href="#"><span>Administrer</span></a></h3></li>
					</ul>
			</div>
			
			<hr class="hidden" />
			
			<div class="siteNavigationApps" id="menuCommunicate" >
				<h3>Communiquer</h3>
				<ul>
						<li><h4><a href="#">Email</a></h4></li>
						<li><h4><a href="#">Carnet d'adresses</a></h4></li>
						<li><h4><a href="#">Flashmails</a></h4></li>
						<li><h4><a href="#">Gmail reader</a></h4></li>
						<li><h4><a href="#">Mini chat</a></h4></li>
				</ul>
			</div>
			<div class="siteNavigationApps" id="menuOrganize">
				<h3>S'organiser</h3>
				<ul>
						<li><h4><a href="#">Calendrier</a></h4></li>
						<li><h4><a href="#">Mes notes</a></h4></li>
						<li><h4><a href="#">Taches</a></h4></li>
				</ul>
			</div>
			<div class="siteNavigationApps" id="menuShare">
				<h3>Partager</h3>
				<ul>
						<li><h4><a href="#">Partage fichiers</a></h4></li>
						<li><h4><a href="#">Wiki</a></h4></li>
				</ul>
			</div>
			<div class="siteNavigationApps" id="menuJobs">
				<h3>Emploi</h3>
				<ul>
						<li><h4><a href="#">NetCV</a></h4></li>
						<li><h4><a href="#">NetJobs</a></h4></li>
				</ul>
			</div>
			<div class="siteNavigationApps" id="menuAdmin">
				<h3>Admin</h3>
				<ul>
						<li><h4><a href="#">Permissions</a></h4></li>
						<li><h4><a href="#">Admin</a></h4></li>
				</ul>
			</div>
		</div>
		{* FIN : Navigation : Barre avec onglets *}
	</div>
	</body>
</html>
