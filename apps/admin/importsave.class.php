<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/
ClassLoader::add('PHPMailer', KARIBOU_LIB_DIR.'/phpmailer/class.phpmailer.php');
/**
 * Save import
 * 
 * @package applications
 */
class ImportSave extends FormModel
{
protected $text;

	function build()
	{
		if (isset($_POST))
		{
		$this->text = new KText();
		
			if (isset($_POST["GeneratePasswords"]))
			{
				Debug::display("Generating Passwords");
				$qry = "SELECT * FROM admin_import WHERE password = ''";
				try
				{
					$stmt = $this->db->prepare($qry);
					$stmt->execute();
				}
				catch( PDOException $e )
				{
					Debug::display($qry);
					Debug::kill($e->getMessage());
				}
				$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				if (count($rows) > 0)
				{
					foreach ($rows as $row)
					{
						$pqry = "	UPDATE admin_import 
										SET password = '".$this->generatePassword()."'
										WHERE id = '".$row["id"]."';";
						try
						{
							$stmt = $this->db->prepare($pqry);
							$stmt->execute();
						}
						catch( PDOException $e )
						{
							Debug::display($pqry);
							Debug::kill($e->getMessage());
						}
					}
				}			

			}
			elseif (isset($_POST["GenerateLogins"]))
			{
				$qry = "SELECT * FROM admin_import";
				try
				{
					$stmt = $this->db->prepare($qry);
					$stmt->execute();
				}
				catch( PDOException $e )
				{
					Debug::display($qry);
					Debug::kill($e->getMessage());
				}
				$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				if (count($rows) > 0)
				{
					foreach ($rows as $row)
					{
						$pqry = "	UPDATE admin_import 
										SET login = '".str_replace(' ', '', strtolower($this->text->epureString($row["firstname"]))).".".str_replace(' ', '', strtolower($this->text->epureString($row["lastname"])))."'
										WHERE id = '".$row["id"]."';";
						try
						{
							$stmt = $this->db->prepare($pqry);
							$stmt->execute();
						}
						catch( PDOException $e )
						{
							Debug::display($pqry);
							Debug::kill($e->getMessage());
						}
					}
				}	
			}
			elseif (isset($_POST["ImportCSV"]))
			{
				if ($_POST["csv"] != "")
				{
					$csv = $this->csv2array($_POST["csv"]);
					
					$keys = array_shift($csv);

					//$posKey contient la position des clé
					$posKey = array();
					foreach ($keys as $id => $key)
					{
						$posKey[$key] = $id;
					}

					$nTotal = 0;
					$nAdded = 0;
					$nDuplicate = 0;
					$nIncomplete = 0;
					$keyError = FALSE;
					if (isset($posKey["firstname"],$posKey["lastname"],$posKey["group"],$posKey["email"],$posKey["phone"]))
					{
						foreach ($csv as $line)
						{
							$nTotal++;
							if (isset($line[$posKey["firstname"]], $line[$posKey["lastname"]], $line[$posKey["firstname"]], $line[$posKey["group"]], $line[$posKey["email"]], $line[$posKey["phone"]]) && $line[$posKey["firstname"]] != "" && $line[$posKey["lastname"]] != "")
							{
								//Checking if record exists
								if (!$this->recordExists($line[$posKey["firstname"]], $line[$posKey["lastname"]]))
								{
									//Insert record
									$this->insertRecord ($line[$posKey["firstname"]], $line[$posKey["lastname"]], $line[$posKey["group"]], $line[$posKey["email"]], $line[$posKey["phone"]]);
									$nAdded++;
								}
								else
								{
									//Record exists
									$nDuplicate++;
								}
							}
							else
							{
								$nIncomplete++;
							}
						}
					}
					else
					{
						$keyError = TRUE;
					}
					
					
					$_SESSION["adminCheck"] = array("keyerror" => $keyError, "total" => $nTotal, "added" => $nAdded, "duplicate" => $nDuplicate, "incomplete" => $nIncomplete);
				}
			}
			elseif (isset($_POST["ApplyModifications"]))
			{
				$this->updateRecord($_POST["id"],$_POST["firstname"],$_POST["lastname"],$_POST["group"],$_POST["email"],$_POST["phone"],$_POST["login"],$_POST["password"]);
			}
			elseif (isset($_POST["ImportInKaribou"]))
			{
				$this->importInKaribou($_POST["ImportInKaribou"]);
			}
			elseif (isset($_POST["FullImportKaribou"]))
			{
				$this->importInKaribou();
			}
			elseif (isset($_POST["ImportInLDAP"]))
			{
				$this->importInLDAP($_POST["ImportInLDAP"]);
			}
			elseif (isset($_POST["FullImportLDAP"]))
			{
				$this->importInLDAP();
			}
			elseif (isset($_POST["SendEmail"]))
			{
				$this->sendEmail($_POST["SendEmail"]);
			}
			elseif (isset($_POST["FullSendEmail"]))
			{
				$this->sendEmail();
			}
		}
	}

	function sendEmail ($id = FALSE)
	{

		if ($id === FALSE)
		{
			//Full Import
			$qry = "
					SELECT *
					FROM admin_import
					WHERE `emailsent_date` = '0000-00-00'";			
		}
		else
		{
			//Single import 
			$qry = "
					SELECT *
					FROM admin_import
					WHERE `id` = '".$id."'";
		}
			
		try
		{
			$stmt = $this->db->prepare($qry);
			$stmt->execute();
		}
		catch( PDOException $e )
		{
			Debug::display($qry);
			Debug::kill($e->getMessage());
		}
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$_SESSION['adminCheck']['ldap'] = array();

		$app = $this->appList->getApp($this->appname);
		$config = $app->getConfig();

		$_SESSION["adminCheck"]["email"] = array();
		foreach($rows as $row)
		{
			$mail = new PHPMailer();
			$mail->Host     = "localhost";
			$mail->Mailer   = "smtp";
			$mail->CharSet = "UTF-8";
			$mail->WordWrap = 80;
			$mail->From = $GLOBALS['config']['contactemail'];
			$mail->FromName = $GLOBALS['config']['contactname'];
			$mail->AddAddress($row["email"], $row["firstname"]." ".$row["lastname"]);
			$mail->AddCC($row["login"].$GLOBALS['config']['login']['post_username'], $row["firstname"]." ".$row["lastname"]);

		$bodyTMP = $GLOBALS['config']['admin']['email_accountcreation']['message'];
		$keys         = array('##INTRANET_URL##', 				'##LOGIN##', 	'##PASSWORD##');
		$replacements = array($GLOBALS['config']['site']['base_url'], 	$row['login'].$GLOBALS['config']['login']['post_username'], 	$row['password']);
		$mail->Subject = str_replace($keys, $replacements, $GLOBALS['config']['admin']['email_accountcreation']['subject']);
		$bodyTMP = str_replace($keys, $replacements, $bodyTMP);

		
			$mail->Body = $bodyTMP;
		
			if( $row['login'] != "" && $row['password'] != "" && !$mail->Send() )
			{
				$_SESSION["adminCheck"]["email"][$row["id"].$row["email"]] = FALSE;
				Debug::kill($mail);
			}
			else
			{
				$_SESSION["adminCheck"]["email"][$row["id"].$row["email"]] = TRUE;
				Debug::display($mail);

			
			//Defini comme importé
			$qry = "UPDATE admin_import
			SET emailsent_date = '".date("Y-m-d H:i:s")."'
			WHERE id = '".$row["id"]."'";
			try
			{
				$stmt = $this->db->prepare($qry);
				$stmt->execute();
			}
			catch(PDOException $e)
			{
				Debug::kill($qry." : ".$e->getMessage());
			}

			}
		}
	}
	
	function importInLDAP ($id = FALSE)
	{
		if ($id === FALSE)
		{
			//Full Import
			$qry = "
					SELECT *
					FROM admin_import";			
		}
		else
		{
			//Single import 
			$qry = "
					SELECT *
					FROM admin_import
					WHERE `id` = '".$id."'";
		}
			
		try
		{
			$stmt = $this->db->prepare($qry);
			$stmt->execute();
		}
		catch( PDOException $e )
		{
			Debug::display($qry);
			Debug::kill($e->getMessage());
		}
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$_SESSION['adminCheck']['ldap'] = array();

		$ildap = new LDAPInterface();
		$ildap->connect();
		foreach($rows as $row)
		{
			$groups = $this->userFactory->getGroups();
			$ou1 = $groups->getGroupById($row['group'])->getName();
			$ou2 = $groups->getParent($row['group'])->getName();

			if ($ou1 != FALSE && $ou2 != FALSE)
			{
				$_SESSION['adminCheck']['ldap'][$row['login']] = $ildap->addAccount($row['login'],str_replace('@','',$GLOBALS['config']['login']['post_username']),$row['password'],array($ou1, $ou2));
			}
			else
			{
				Debug::kill("Organisational Units problem");
			}
		}
		$ildap->disconnect();
	}
	
	function importInKaribou ($id = FALSE)
	{
		if ($id === FALSE)
		{
			//Full Import
			$qry = "
					SELECT *
					FROM admin_import
					WHERE `imported_date` = '0000-00-00'";			
		}
		else
		{
			//Single import 
			$qry = "
					SELECT *
					FROM admin_import
					WHERE `id` = '".$id."'";
		}

		try
		{
			$stmt = $this->db->prepare($qry);
			$stmt->execute();
		}
		catch( PDOException $e )
		{
			Debug::display($qry);
			Debug::kill($e->getMessage());
		}
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

		foreach($rows as $row)
		{
			if (!$this->loginExistsInKaribou($row["login"]))
			{
				$this->importRecordInKaribou($row);
				$_SESSION["adminCheck"] = array("addedink2" => TRUE);
			}
			else
			{
				//Login exists
				$_SESSION["adminCheck"] = array("loginexists" => TRUE);
			}
		}

	}
	
	function importRecordInKaribou ($row)
	{			
			//Import dans users
			$qry = "INSERT INTO ".$GLOBALS['config']['bdd']['annuairedb'].".profile 
			(firstname, lastname, surname) 
			VALUES ('".$row["firstname"]."','".$row["lastname"]."','".$row["firstname"]."')";
			try
			{
				$stmt = $this->db->prepare($qry);
				$stmt->execute();
			}
			catch(PDOException $e)
			{
				Debug::kill($qry." : ".$e->getMessage());
			}
			$maxProfileId = $this->db->lastInsertId();

			//Import dans users
			$qry = "INSERT INTO ".$GLOBALS['config']['bdd']['annuairedb'].".users 
			(login, profile_id) 
			VALUES ('".$row["login"]."','".$maxProfileId."')";
			try
			{
				$stmt = $this->db->prepare($qry);
				$stmt->execute();
			}
			catch(PDOException $e)
			{
				Debug::kill($qry." : ".$e->getMessage());
			}
			$userid = $this->db->lastInsertId();
			
			//Import du profile
			$qry1 = "
			INSERT INTO ".$GLOBALS['config']['bdd']['annuairedb'].".profile_email
			(profile_id, type, email)
			VALUES ('".$maxProfileId."', 'INTERNET', '".$row["email"]."');";
			$qry2 = "
			INSERT INTO ".$GLOBALS['config']['bdd']['annuairedb'].".profile_email
			(profile_id, type, email)
			VALUES (".$maxProfileId.", 'INTERNET', '".$row["login"].$GLOBALS['config']['login']['post_username']."');";
			$qry3 = "
			INSERT INTO ".$GLOBALS['config']['bdd']['annuairedb'].".profile_phone
			(profile_id, type, number)
			VALUES (".$maxProfileId.", 'CELL', '".$row["phone"]."');";
			try
			{
				$stmt = $this->db->prepare($qry1);
				$stmt->execute();
				$stmt = $this->db->prepare($qry2);
				$stmt->execute();
			}
			catch(PDOException $e)
			{
				Debug::kill($qry." : ".$e->getMessage());
			}
			
			//Assignation au groupe
			$qry = "INSERT INTO ".$GLOBALS['config']['bdd']['annuairedb'].".group_user 
			(user_id, group_id, role, visibility) 
			VALUES ('".$userid."','".$row["group"]."', 'member', 'visible')";
			try
			{
				$stmt = $this->db->prepare($qry);
				$stmt->execute();
			}
			catch(PDOException $e)
			{
				Debug::kill($qry." : ".$e->getMessage());
			}
			
			//Defini comme importé
			$qry = "UPDATE admin_import
			SET imported_date = '".date("Y-m-d H:i:s")."'
			WHERE id = '".$row["id"]."'";
			try
			{
				$stmt = $this->db->prepare($qry);
				$stmt->execute();
			}
			catch(PDOException $e)
			{
				Debug::kill($qry." : ".$e->getMessage());
			}
		
	}
	
	function loginExistsInKaribou($login)
	{
		
		$qry = "
					SELECT count(*) as nb
					FROM ".$GLOBALS['config']['bdd']['annuairedb'].".users
					WHERE `login` = '".$login."'";

		try
		{
			$stmt = $this->db->prepare($qry);
			$stmt->execute();
		}
		catch( PDOException $e )
		{
			Debug::display($qry);
			Debug::kill($e->getMessage());
		}
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if ($rows[0]["nb"] > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function updateRecord ($id, $firstname, $lastname, $group, $email, $phone, $login, $password)
	{
						$qry = "
								UPDATE admin_import
								SET
									`firstname` = '".ucwords(strtolower($firstname))."',
									`lastname` = '".ucwords(strtolower($lastname))."',
									`group` = '".$group."',
									`email` = '".$email."',
									`phone` = '".$phone."',
									`login` = '".$login."',
									`password` = '".$password."'
								WHERE	`id` = '".$id."'";
						try
						{
							$stmt = $this->db->prepare($qry);
							if ($stmt->execute())
							{
								return TRUE;
							}
							else
							{
								return FALSE;
							}
						}
						catch( PDOException $e )
						{
							Debug::display($qry);
							Debug::kill($e->getMessage());
						}
	}


	function insertRecord ($firstname, $lastname, $group, $email, $phone)
	{
						$qry = "
								INSERT INTO admin_import
								(`firstname`,`lastname`,`group`,`email`,`phone`)
								VALUES ('".ucwords(strtolower($firstname))."','".ucwords(strtolower($lastname))."','".$group."','".$email."','".$phone."')";
						try
						{
							$stmt = $this->db->prepare($qry);
							$stmt->execute();
						}
						catch( PDOException $e )
						{
							Debug::display($qry);
							Debug::kill($e->getMessage());
						}
	}

	function recordExists($firstname, $lastname)
	{
						$qry = "
								SELECT count(*) as nb 
								FROM admin_import
								WHERE `firstname` = '".$firstname."' AND `lastname` = '".$lastname."'";
						try
						{
							$stmt = $this->db->prepare($qry);
							$stmt->execute();
						}
						catch( PDOException $e )
						{
							Debug::display($qry);
							Debug::kill($e->getMessage());
						}
						$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

						if ($rows[0]["nb"] > 0)
						{
							return TRUE;
						}
						else
						{
							return FALSE;
						}
	}	
	
	function csv2array ($csv)
	{
		$csv = str_replace ("\\", "", $csv);
		$csv = str_replace ("\"", "", $csv);
		$csv = str_replace ("'", "", $csv);
		$csv = str_replace ("\r", "", $csv);
		$csv = str_replace ("\t", "", $csv);
		
		$full = explode ("\n", $csv);
		foreach ($full as &$line)
		{
			$line = explode (";", $line);
		}

		return $full;
	}
	
	function generatePassword ()
	{
		
		return rand (1000, 9999);
	}
/*
	function checkEmail ($email)
	{
		$regex =
		  '^'.
		  '[_a-z0-9-]+'.
		  '(\.[_a-z0-9-]+)*'.
		  '@'.	  '[a-z0-9-]+'.
		  '(\.[a-z0-9-]{2,})+'.
		  '$';
	
		if (eregi($regex, $email))
		{
		  return TRUE;
		}
		else
		{
		  return FALSE;
		}
	}
	
	function epureString ($string)
	{
		$string = $this->removeAccents($string);
		$string = preg_replace("/[^A-Za-z0-9_-]/", '',$string);
		$string = strtolower($string);
		return $string;
	}
	
	//Thanks SSI for the following methods
	function removeAccents ($string)
	{
		if ($this->seemsUTF8($string)) {
				$chars = array(
					chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
					chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
					chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
					chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
					chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
					chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
					chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
					chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
					chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
					chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
					chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
					chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
					chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
					chr(195).chr(160) => 'a', chr(195).chr(161) => 'a',
					chr(195).chr(162) => 'a', chr(195).chr(163) => 'a',
					chr(195).chr(164) => 'a', chr(195).chr(165) => 'a',
					chr(195).chr(167) => 'c', chr(195).chr(168) => 'e',
					chr(195).chr(169) => 'e', chr(195).chr(170) => 'e',
					chr(195).chr(171) => 'e', chr(195).chr(172) => 'i',
					chr(195).chr(173) => 'i', chr(195).chr(174) => 'i',
					chr(195).chr(175) => 'i', chr(195).chr(177) => 'n',
					chr(195).chr(178) => 'o', chr(195).chr(179) => 'o',
					chr(195).chr(180) => 'o', chr(195).chr(181) => 'o',
					chr(195).chr(182) => 'o', chr(195).chr(182) => 'o',
					chr(195).chr(185) => 'u', chr(195).chr(186) => 'u',
					chr(195).chr(187) => 'u', chr(195).chr(188) => 'u',
					chr(195).chr(189) => 'y', chr(195).chr(191) => 'y',
					chr(197).chr(146) => 'OE', chr(197).chr(147) => 'oe',
					chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
					chr(197).chr(189) => 'Z', chr(197).chr(190) => 'z',
					chr(226).chr(130).chr(172) => 'E');

				$string = strtr($string, $chars);
		} else {
				// Assume ISO-8859-1 if not UTF-8
				$chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
						.chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
						.chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
						.chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
						.chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
						.chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
						.chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
						.chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
						.chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
						.chr(252).chr(253).chr(255);

				$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

				$string = strtr($string, $chars['in'], $chars['out']);
				$double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
				$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
				$string = str_replace($double_chars['in'], $double_chars['out'], $string);
		}

		return $string;

	}
	
	function seemsUTF8($Str)
	{
			for ($i=0; $i<strlen($Str); $i++) {
					if (ord($Str[$i]) < 0x80) continue;
					elseif ((ord($Str[$i]) & 0xE0) == 0xC0) $n=1; # 110bbbbb
					elseif ((ord($Str[$i]) & 0xF0) == 0xE0) $n=2; # 1110bbbb
					elseif ((ord($Str[$i]) & 0xF8) == 0xF0) $n=3; # 11110bbb
					elseif ((ord($Str[$i]) & 0xFC) == 0xF8) $n=4; # 111110bb
					elseif ((ord($Str[$i]) & 0xFE) == 0xFC) $n=5; # 1111110b
					else return false;
					for ($j=0; $j<$n; $j++) {
							if ((++$i == strlen($Str)) || ((ord($Str[$i]) & 0xC0) != 0x80))
							return false;
					}
			}
			return true;
	}
*/
}
?>
