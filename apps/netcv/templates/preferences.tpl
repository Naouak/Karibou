<form action="{kurl page="preferencessave"}" method="POST">
  <div class="formAddText">
    <h3>Design de CV</h3>
      Je veux que mon CV soit pr&eacute;sent&eacute; avec le design
<?php
$myUser->refresh();
$mySkinsList = new SkinsList($myUser->infos->skin);
$mySkinsList->display();
?>
  </div>



<?/* *** *** *** Envoi des statistiques *** *** *** */?>
<div class="formAddText">
<h3>Envoi des statistiques des visites par email</h3>
<? if (isset($_SESSION["netcv_errors"]["stats"])) { print_errors($_SESSION["netcv_errors"]["stats"]); } ?>
Je veux recevoir les statistiques dans ma boite aux lettres (<?=$myUser->infos->email?>) 
<?
// to find the selected radio
$sql2 = mysql_fetch_array(mysql_query("SELECT sendStatsDelay FROM preferences WHERE resume_id = '".$myUser->infos->resumes."' LIMIT 1"));
?>
<select name="sendStatsBox">
  <option value="7"<? if ($sql2['sendStatsDelay']==7) echo " selected"; ?>>toutes les semaines</option>
  <option value="30"<? if ($sql2['sendStatsDelay']==30) echo " selected"; ?>>tous les mois</option>
  <option value="90"<? if ($sql2['sendStatsDelay']==90) echo " selected"; ?>>tous les 3 mois</option>
  <option value="0"<? if ($sql2['sendStatsDelay']==0) echo " selected"; ?>>jamais</option>
</select>
</div>

<?/* *** *** *** Adresse du CV *** *** *** */?>
<div class="formAddText">
  <h3>Adresse du CV</h3>
  <? if (isset($_SESSION["netcv_errors"]["cvHost"])) { print_errors($_SESSION["netcv_errors"]["cvHost"]); } ?>
  Je veux que mon CV soit mis en ligne &agrave; l'adresse internet suivante: 
    <input type="text" name="cvHost" value="<?=$myUser->prefs->cvHost?>"><strong>.<?=URL_HOSTNAME?></strong>
</div>


<?/* *** *** *** Email Cache *** *** *** */?>
<div class="formAddText">
  <h3>Cacher l'adresse email pour &eacute;viter les spams</h3>
  <? if (isset($_SESSION["netcv_errors"]["hideEmail"])) { print_errors($_SESSION["netcv_errors"]["hideEmail"]); } ?>
  Je souhaite que mon adresse email n'apparaisse pas sur mon CV publique: 
    <select name="hideEmail">
      <option value="1"<? if($myUser->prefs->hideEmail==1) {echo " selected";}?>>Cacher mon email</option>
      <option value="0"<? if($myUser->prefs->hideEmail==0) {echo " selected";}?>>Montrer mon email</option>
    </select>
</div>

<?/* *** *** *** CV Public *** *** *** */?>
<div class="formAddText">
  <h3>Mettre mon CV en ligne publiquement</h3>
  <? if (isset($_SESSION["netcv_errors"]["public"])) { print_errors($_SESSION["netcv_errors"]["public"]); } ?>
  Je souhaite que mon CV soit 
    <select name="public">
      <option value="1"<? if($myUser->prefs->public==1) {echo " selected";}?>>Publique</option>
      <option value="0"<? if($myUser->prefs->public==0) {echo " selected";}?>>Priv&eacute;</option>
    </select>
   <br />
   Si votre CV est publique, il pourra &ecirc;tre r&eacute;f&eacute;renc&eacute; sur les moteurs de recherches (i.e. <a href="http://www.google.fr" title="Google" alt="Google">Google</a>).
   <br />
   <a href="?lien=netcv&page=help#public">Vous &ecirc;tes encourag&eacute; &agrave; lire l'aide &agrave; ce sujet.</a>
</div>

  <div class="formAddTextButton">
    <em>N'oubliez pas de cliquer sur le bouton "Mettre &agrave; jour" pour valider vos choix!</em>
    <br />
    <input type="submit" value="Mettre &agrave; jour" name="Update">
  </div>

</form>
<br />


<?/* *** *** *** Photo sur le CV *** *** *** */?>
<div class="formAddText">
  <h3>Photo sur le CV</h3>
  <? if (isset($_SESSION["netcv_errors"]["photo"])) { print_errors($_SESSION["netcv_errors"]["photo"]); } ?>
<?
$serverdir = $siteDir."/public/photos";
$filename = $myUser->username.".jpg";

if (is_file($serverdir."/".$filename)) {
	//File exists
?>

  <span style="margin-left: 70px;"><img src="/applis/netcv/public/photos/<?=$filename?>?<?=filemtime($serverdir.$filename)?>" alt="<?=$myTranslation->translate("yourpicture_yourpicture");?>" label="<?=$myTranslation->translate("yourpicture_yourpicture");?>"></span>
  <form action="/applis/netcv/public/picture/delete.php" method="post" name="deletePicture">
   <input type="submit" class="deleteButton" name="<?=$myTranslation->translate("yourpicture_deletepicture");?>" value="<?=$myTranslation->translate("yourpicture_deletepicture");?>">
  </form>

<?
} else {
	//File does not exist
?>
<p>Je souhaite mettre la photo suivante sur mon CV:</p>
 <form method="post" action="/applis/netcv/public/picture/upload.php" enctype="multipart/form-data">
    <input type="file" name="userfile">
    <input <?/*class="addButton"*/?> type="submit" value="<?=$myTranslation->translate("yourpicture_button");?>">
 </form>
 <br />
 <em><?=$myTranslation->translate("yourpicture_message");?></em>
<?
   }
?>
</div>
<?
  if (isset($_SESSION["netcv_errors"])) {
    unset($_SESSION["netcv_errors"]);
  }
?>
