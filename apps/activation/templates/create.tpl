{if $error}
{$message}
{else}
<h1>Intranet</h1>
<h2>Activation de votre email</h2>
<p>
  En arrivant sur cette page, votre adresse email
  a été automatiquement activée ({$email}).
  Félicitations, vous pouvez désormais vous connecter sur l'Intranet.
  <br />
  	Votre login est : {$email}
  <br />
  	Le mot de passe associé est : <strong>{$password}
	<br />
  (attention à ne pas l'égarer).</strong>. N'hésitez pas à le changer sur l'<a href="/">Intranet</a>.
</p>
<p>
  Vous avez à votre disposition un Intranet, un espace emploi où 
  vous pourrez mettre votre CV en ligne simplement, consulter le calendrier, les actualites, les fichiers partages...
</p>
{/if}
