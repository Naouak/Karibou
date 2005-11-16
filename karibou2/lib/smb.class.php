<?php
/**
 * Nom fichier			: smb.class.php
 * Date de creation	: juin 2002
 * Non du créateur		: JoN
 * Date derniere modif	: 04 juillet 2002
 * Modifs :
 * 
 * @package lib
 */
/*
* 
* Codes erreurs renvoyés par les fonctions : smbList() smbGet() smbTestDir()
* 		0 : valide
* 		1 : erreur générique
* 		2 : erreur de dossier
* 		3 : erreur de fichier
* 		4 : petit chenapant, t'as pas le droit de mettre des \ ou des " dans tes noms de dossier ou de fichier
* 		5 : no access
*   
*/

define("_SMB_E_OK", 0);
define("_SMB_E_GENERIQUE", 1);
define("_SMB_E_DOSSIER", 2);
define("_SMB_E_FICHIER", 3);
define("_SMB_E_INTERDIT", 4);
define("_SMB_E_NOACCESS", 5);

include ( dirname(__FILE__) . "/../inc/fonction.php");

class listElement
{
	var $type;
	var $nom;
	var $taille;
	var $date;
}

class smb
{
	var $host = "eleve3";
	var $share = "";
	var $_promoTypeDir = "";
	var $baseDir = "";
	var $user = "";
	var $pass = "";
	
	var $baseCmd = "";
	
	var $actualDir = "";
	
	var $result;
	
	// qui contiendra le résultat de la requête
	var $_execResult;

	function smb()
	{
		require_once dirname(__FILE__) . '/../../horde/lib/Secret.php';
		$temp =  unserialize(Secret::read(Secret::getKey('auth'), $GLOBALS['HTTP_SESSION_VARS']['__auth']['credentials']));
		
		$this->pass = $temp["password"];
		$this->user = $_SESSION["loginUtilisateur_ses"];
		unset($temp);
		
		// on récupère le type et l'année de la promo
		$results = select("select typePromo, AnneePromo from promos where idPromo ='".$_SESSION["idPromoUtilisateur_ses"]."'");
    	if($row = mysql_fetch_array($results))
    	{
      		$typePromo = $row["typePromo"];
			$anneePromo = $row["AnneePromo"];
    	}
		
		switch($typePromo){
			case "FI": 
				$this->_promoTypeDir = "init/";
				break;
			case "FP": 
				$this->_promoTypeDir = "promo/";
				break;
			case "TTN": 
				$this->_promoTypeDir = "ttn/";
				break;
			case "FA":
				$this->_promoTypeDir = "appr/";
				break;
		} // switch
		
		$this->share = "ing".$anneePromo;
		
		$this->baseDir = $this->user . "/";
		
		$this->baseCmd = "smbclient " ."\\\\\\\\". $this->host ."\\\\". $this->share ;
	}
	
	function smbList()
	{
		$array = array();
		
		//if($this->baseDir)
		{
			$arrayDossiers = array();
			$arrayFichiers = array();
			
			$test = $this->_exec("ls");
			
			if($test == _SMB_E_OK)
			{
				$ready = 0;
				$next = 0;
				foreach($this->_execResult as $line)
				{
					if($next)
					{
						$ready = 1;
						$next = 0;
					}
					if( ereg("Domain=",$line) )
					{
						$next = 1;
					}
					if($line == "" )
					{
						$ready = 0;
					}
					if($ready)
					{
						$objet = new listElement;
						
						$attributs = trim(substr($line, strlen($line)-42, 7 ));
						$objet->nom = trim(substr($line, 0, strlen($line)-42 ));
						$objet->date = trim(substr($line, strlen($line)-24, 24 ));
						$objet->taille = trim(substr($line, strlen($line)-35, 10 ));
						
						if( !(($objet->nom == ".") || ($objet->nom == "..")) )
						{
							if( ereg("D",$attributs) )
							{
								$objet->type = "dossier";
								$arrayDossiers[] = $objet;
							}
							else
							{
								$objet->type = "fichier";
								$arrayFichiers[] = $objet;
							}
						}
						
						unset($objet);
					}
				}
				$array = array_merge($arrayDossiers, $arrayFichiers);
				$this->result = $array;
			}
			return $test;
		}
	}
	
	function getList()
	{
		return $this->result;
	}
	
	function smbCd( $dir )
	{
		$this->actualDir = $dir;
	}
	
	function smbTestDir($dir)
	{
		if( (!ereg("\\\\", $dir)) && (!ereg("\\\"", $dir)) )
		{
			return $this->_exec("cd \\\"".$dir."\\\"");
		}
		return _SMB_E_INTERDIT;
	}
	
	function smbSetBaseDir( $partage )
	{
		if($partage == "Perso")
		{
			$this->baseDir = $this->user . "/";
		}
		elseif ($partage == "Public")
		{
			$this->baseDir = "public/";
		}
		else
		{
			// par défaut on met le répertoire perso
			$this->baseDir = $this->user . "/";
		}
	}
	
	function smbGet($file)
	{
		if(!ereg("\\\"", $file) && !ereg("\\\\", $file))
		{
			$tempFile = "/homepage/php/upload/smb_".md5(uniqid(rand(), 1));
			
			$test = $this->_exec("get \\\"".$file."\\\" \\\"".$tempFile."\\\"");
			if($test == _SMB_E_OK)
			{		
				$this->result = $tempFile;
			}
			return $test;
		}
		else
		{
			// l'utilisateur a mis des " ou des \ dans le nom de fichier
			return _SMB_E_INTERDIT;
		}
	}
	
	function getFile($fichier)
	{
		$fp = fopen($this->result,"r");
		
		// Header de téléchargement
		header("Content-Disposition: attachment; filename=".$fichier);
		header("Content-type: application/force-download; name=".$fichier);
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize($this->result));
		header("Pragma: no-cache");
		header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
		header("Expires: 0");	
		
		while($data = fread($fp,1024))
		{
			print $data;
		}
		fclose($fp);
		unlink($this->result);
	}
	
	function _exec($paramCmd)
	{
		$command = $this->baseCmd;
		
		$command .= " -D \"" . $this->_promoTypeDir . $this->baseDir . $this->actualDir . "\"";
		
		if($this->user && $this->pass)
		{
			$command .= " -U ".$this->user."%".$this->pass."";
		}
		else
		{
			$command .= " -N";
		}
		
		if($paramCmd)
		{
			//$command .= " -c \"cd ".$this->_promoTypeDir . $this->baseDir . $this->actualDir .";".$paramCmd."\"";
			$command .= " -c \"".$paramCmd."\"";
		}
		
		//echo $command."<br>\n";
		exec($command,$out);
		
		foreach($out as $line)
		{
			if(ereg("ERRDOS", $line))
			{
				if(ereg("ERRbadpath", $line))
					return _SMB_E_DOSSIER; // mauvais dossier
				if(ereg("ERRbadfile", $line))
					return _SMB_E_FICHIER; // mauvais fichier
				if(ereg("ERRnoaccess", $line))
					return _SMB_E_NOACCESS; // mauvais fichier
					
				// erreur générique
				return _SMB_E_GENERIQUE;
			}
		}
		
		$this->_execResult = $out;
		return _SMB_E_OK;
	}
}
?>
