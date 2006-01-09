<?php
session_start();

include ("header.php");

include ("inc0/functions.php");

$num = 0;
?>
<h2>Configuration du transfert d'email</h2>
<?php
if ($_SESSION["authenticated"] == TRUE) {
echo "(".$_SESSION["email"].")<br /><br />";

if (isset($_POST["new_email"])) {
  if ( checkEmail($_POST["new_email"])) {

      include("./inc0/ldapconnect.php");

      $new["maildrop"] = $_POST["new_email"];
      
      $mydn = dnFromEmail ($ldapconn, $_SESSION["email"], "jvd=mastergeb.net, dc=inkateo, dc=com");
      
      ldap_modify($ldapconn, $mydn, $new);
      echo "<span class=\"success\">Ton email de transfert (forward) a été correctement configuré.</span><br />Tous les emails envoyés à <strong>".$_SESSION["email"]."</strong> seront transférés sur <strong>".$_POST["new_email"]."</strong><br/>Les messages ne seront plus stockés sur ton compte MasterGEB.Net.<br /><br /><a href=\"config.php\">Retour au Menu</a>";
  } else if ($_POST["new_email"] == "") {
      include("./inc0/ldapconnect.php");

      $del_maildrop["maildrop"] = array();
      
      $mydn = dnFromEmail ($ldapconn, $_SESSION["email"], "jvd=mastergeb.net, dc=inkateo, dc=com");
      
      @ldap_mod_del($ldapconn,$mydn,$del_maildrop);
      echo "<span class=\"success\">Ton email de transfert (forward) a été supprimé.</span><br />Les emails envoyés à <strong>".$_SESSION["email"]."</strong> ne seront plus transférés.<br /><br /><a href=\"config.php\">Retour au Menu</a></span>";
  } else {
      $errors[$num++] = "Ton email doit être valide.";
  }
} else {
  $errors[$num++] = "Entre une adresse email valide.";
}

if(count($errors) > 0) {

  echo "  <p>Entre l'adresse email sur laquelle tu souhaites que tes emails soient transférés (laisse ce champ vide si tu souhaites désactiver le transfert d'email).</p>";

  foreach($errors as $error) {
    echo "<span class=\"error\">$error</span><br />";
  }

/* */
   include("./inc0/ldapconnect.php");
   $sr = ldap_search($ldapconn,"jvd=mastergeb.net, dc=inkateo, dc=com", "mail=".$_SESSION["email"] ); 
   $info_maildrop = ldap_get_entries($ldapconn, $sr);
/* */

?>

  <form action="" method="post">
    Email de transfert (forward): <input type="text" name="new_email" value="<?=$info_maildrop[0]["maildrop"][0];?>"><br /><br />
    <input type="submit" value="Mettre à jour">
  </form>
<?
}

}

include ("footer.php");
?>
