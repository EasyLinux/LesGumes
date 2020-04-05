<?php
/**
 * Backup
 *
 * Cette classe réalise une sauvegarde des données d'un site, (base et fichiers)
 * 
 * @package	EasyCMS
 * @author	Serge NOEL <serge.noel@easylinux.fr>
 * @version	1.0
 * @link	http://www.easylinux.fr
 */
class Backup extends mysqli {

	private $sBackupFolder="/_Backup";  // @var   string   Répertoire des sauvegardes
	private $sFileName="";              // @var   string   nom du fichier backup (backup-<date>-<heure>.zip)
	private $aRet;											// @var		array    code erreur
	private $oDb;											  // @var   object   objet mysqli
	private $sTmpFolder="/tmp";
	private $sSqlTemp="base.sql";
	private $iFileHandle;
	private $sTmpJson='/tmp/backup.json';
	
	protected $dossier;
	protected $nom_fichier;
	protected $gz_fichier;
	
	
	/**
	 * __construct
	 * 
	 * Constructeur de la classe
	 * @param   array		tableau associatif avec coordonnées de la bdd
	 * @return  void
	 */
	public function __construct($Cfg) {
		$this->aRet = ["Errno" =>0, "ErrMsg" => "OK"];
		$this->oDb = @parent::__construct(
			$Cfg["MariaDb"]["Host"], 
			$Cfg["MariaDb"]["User"], 
			$Cfg["MariaDb"]["Pass"], 
			$Cfg["MariaDb"]["Base"]);
		if($this->connect_errno) {
				$this->aRet["Errno"] = $this->connect_errno;
				$this->aRet["ErrMsg"] = $this->connect_error;
		}
		$this->setMessage("Initialisation de la classe", "Backup", 0);
		return;
	}

	/**
	 * setTargetFolder
	 * 
	 * Désigne le répertoire cible 
	 * @param   string   $sFolder Dossier où sera stocké la sauvegarde - il n'est pas sauvegardé
	 * @return	array    Code d'erreur et message
	 */
	public function setTargetFolder($sFolder)
	{
		$this->aRet = ["Errno" =>0, "ErrMsg" => "OK"];
		$this->sBackupFolder = $sFolder;
		if(!is_dir($this->$this->sBackupFolder) && !is_writable($this->$this->sBackupFolder)) {
			$aRet = ["Errno" => -1, "ErrMsg" => "Dossier cible absent ou sans droits d'écriture"];
			return $aRet;
		}
		return $aRet;
	}

	/**
	 * setFileName
	 * 
	 * Construit le nom du fichier ou utilise celui demandé 
	 * @param   string   optionnel, si passé remplace le nom par défaut (backup-<date>-<heure>.zip)
	 * @return	array    Code d'erreur et message
	 */
	public function setTargetFile($sFileName="backup")
	{
		$this->aRet = ["Errno" =>0, "ErrMsg" => "OK"];
		if($sFileName == "backup"){
			// Construit
			$this->sFileName = "backup-" . date('Ymd-His') . '.zip';
		} else {
			$this->sFileName = $sFileName;
		}
		return $this->aRet;
	}

	/**
	 * setTmpFolder
	 * 
	 * Positionne le répertoire temporaire 
	 * @param   string   optionnel, si passé remplace le nom par défaut (/tmp)
	 * @return	array    Code d'erreur et message
	 */
	public function setTmpFolder($sTmpFolder="/tmp")
	{
		$this->aRet = ["Errno" =>0, "ErrMsg" => "OK"];
		if($sTmpFolder != "/tmp"){
			if( is_writable($sTmpFolder) ){
				$this->$sTmpFolder = $sTmpFolder;
			} else {
				$this->aRet = ["Errno" => -1, "ErrMsg" => "Target tmp folder not writable !"];
			}
		}
		return $aRet;
	}

  /**
	 * setMessage
	 * 
	 * Cette fonction permet de transmettre via ajax les actions en cours
	 * une petite astuce est utilisée, on utilise un fichier temporaire /tmp/status.json
	 * qui reçoit les informations. Ces informations peuvent être lues par un autre process
	 * 
	 * @param   string    $sMsg   	Message à stocker
	 * @param   string    $sSubMsg  Sous message
	 * @param   int       $iErrno   Code erreur :
	 *                                            - 0 -> OK
	 *                                            - 1 -> WARNING
	 *                                            - 2 -> ERREUR
	 * @return  void
	 */
	function setMessage($sMsg, $sSubMsg, $iErrno)
	{
		// $aMsg = ["Errno" => $iErrno, "ErrMsg" => $sMsg, "SubMsg" => $sSubMsg];
		// file_put_contents($_SERVER['DOCUMENT_ROOT'].$this->sTmpJson,json_encode($aMsg));
		return;
	}

	/**
	 * insertClean
	 * 
	 * Protection des quot SQL
	 * @param   string   $string  Chaine à protéger
	 * @return  string
	 */
	protected function insertClean($string) {
		// Ne pas changer l'ordre du tableau !!!
		$s1 = array( "\\"	, "'"	, "\r", "\n", );
		$s2 = array( "\\\\"	, "''"	, '\r', '\n', );
		return str_replace($s1, $s2, $string);
	}

	/**
	 * BackupDb
	 * 
	 * Lance la sauvegarde de la base de données
	 * 
	 * @param   void
	 * @return  void
	 */
	function BackupDb() {	
		$oResult;				// @var  object   liste des tables
		$iMaxTables;    // @var  int      Nombre de tables dans la base
		$iTableIdx=0;   // @var  int      Table en cours de sauvegarde - index
		$iFileHandle;		// @var  int      Fichier .SQL en cours d'écriture
		$oTable;        // @var  object   Table en cours de sauvegarde - objet
		$sSql;          // @var  string   Contenu du fichier .SQL
		$resultCreate;  // @var  object   Objet de création de table

		$sTmpBaseSql = $this->sTmpFolder."/base.sql";
		$sSql  = "--\n";
		$sSql .= "-- Sauvegarde du ".date('d/m/Y')." à ". date('H:i:s'). "\n";
		$sSql .= "--\n";
		$this->setMessage("Sauvegarde de la base","",0);
		// Open tmpfile
		$iFileHandle = fopen($sTmpBaseSql,'w');		
		if ( $iFileHandle === false){
			$this->setMessage("Ne peut ouvrir le fichier temporaire","",-2);
			return $this->aRet;
		}		
		fwrite($iFileHandle,$sSql);
		// Liste les tables
		$oResult = $this->query('SHOW TABLE STATUS');
		if($oResult && $oResult->num_rows) {
			$iMaxTables = $oResult->num_rows;
			while($oTable = $oResult->fetch_object()) 
			{
				$this->setMessage("Sauvegarde de la base","Table : ".$oTable->{'Name'}. " ($iTableIdx/$iMaxTables)" ,0);
				$iTableIdx++;
				
				// DROP ...
				$sSql  = "\n\n";
				$sSql .= 'DROP TABLE IF EXISTS `'. $oTable->{'Name'} .'`' .";\n";

				// CREATE ...
				$resultCreate = $this->query('SHOW CREATE TABLE `'. $oTable->{'Name'} .'`');
				if($resultCreate && $resultCreate->num_rows) {
					$objCreate = $resultCreate->fetch_object();
					$sSql .= $objCreate->{'Create Table'} .";\n";
					$resultCreate->free_result();
				}

				// INSERT ...
				$resultInsert = $this->query('SELECT * FROM `'. $oTable->{'Name'} .'`');
				if($resultInsert && $resultInsert->num_rows) {
					$sSql .= "\n";
					while($obj_insert = $resultInsert->fetch_object()) {
						$virgule = false;
						
						$sSql .= 'INSERT INTO `'. $oTable->{'Name'} .'` VALUES (';
						foreach($obj_insert as $val) {
							$sSql .= ($virgule ? ',' : '');
							if(is_null($val)) {
								$sSql .= 'NULL';
							} else {
								$sSql .= '\''. $this->insertClean($val) . '\'';
							}
							$virgule = true;
						} // for
						
						$sSql .= ')' .";\n";
						
					} // while
					$resultInsert->free_result();
				}
				
				fwrite($iFileHandle, $sSql);
			} // while
			$oResult->free_result();
		}
		fclose($iFileHandle);
		$this->setMessage("Sauvegarde de la base","Terminée" ,0);		
	}

	/**
	 * getBackupFileName
	 * 
	 * retourne le nom du fichier généré
	 * @param   void   
	 * @return  string   Nom du fichier
	 */
	function getBackupFileName()
	{
		return $this->sBackupFolder."/".$this->sFileName;
	}


  /**
	 * backupFiles
	 * 
	 * Sauvegarde les fichiers présents
	 * @param    string     $sFolder     Répertoire à sauvegarder
	 * @param    bool       $bWithBase   Si vrai, effectue une sauvegarde de la base
	 */
	function backupFiles($sFolder,$bWithBase=true)
	{
		$oZip;          // @var object    pointeur sur l'archive zip

		if (extension_loaded('zip')) 
		{
			$oZip = new ZipArchive();
			$this->setMessage("Création de l'archive","",0);

			if( $this->sFileName == ""){
				// Si aucun fichier de backup par défaut, utilise backup-<date>-<heure>.zip
				$this->setTargetFile($sFileName="backup");
			}

			// Ouvrir le fichier dans /tmp
			if ($oZip->open($this->sTmpFolder."/backup.zip", ZIPARCHIVE::CREATE)) 
			{
				// Do stuff - Sauvegarde des répertoires
				$sFolder = realpath($sFolder);
				if (is_dir($sFolder)) {
					// on pointe sur un répertoire
					$oIterator = new RecursiveDirectoryIterator($sFolder);
					// On ne prend pas en compte . et .. 
					$oIterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
					$oFiles = new RecursiveIteratorIterator($oIterator, RecursiveIteratorIterator::SELF_FIRST);
					foreach ($oFiles as $sFile) {
						$sFile = realpath($sFile);
						if (is_dir($sFile)) {
							$oZip->addEmptyDir(str_replace($sFolder."/", '', $sFile . '/'));
						} else if (is_file($sFile)) {
							$this->setMessage("Création de l'archive","Compression de $sFile",0);
							if( !(strstr($sFile,"templates_c") || strstr($sFile,"_Backup")) ){
								$oZip->addFromString(str_replace($sFolder."/", '', $sFile), file_get_contents($sFile) );
							}
							
						}
					}
				} else {
					// On pointe un fichier
					setMessage("ERREUR: pointe sur un fichier",$sFolder,-5);
					if (is_file($sFolder)) {
						$oZip->addFromString(basename($sFolder), file_get_contents($sFolder));
					}
				}
				// Ajout du fichier .sql 

				$this->setMessage("Création de l'archive","Ajout de la base",0);
				$this->BackupDb();

				$sSqlFileTarget = "tools/_Backup/base.sql";
				$oZip->addFromString($sSqlFileTarget, file_get_contents($this->sTmpFolder."/base.sql"));
				
				// Effacer fichier /tmp/base.sql
				unlink($this->sTmpFolder."/base.sql");
			} else {
				$this->setMessage(-4,"ERREUR: ne peut ouvrir le fichier .zip",$sTmpFolder."/".$this->sFileName);
			}
			$oZip->close();
			// Déplacer le zip dans _Backup 
			if (rename($this->sTmpFolder."/backup.zip",$_SERVER['DOCUMENT_ROOT'].$this->sBackupFolder."/".$this->sFileName) == false ){
				echo "Ne peut déplacer le fichier";
			}
		} else {
			$this->setMessage(-6,"ERREUR: ne peut ouvrir le fichier .zip","Extension zip introuvable !");
		}
		$this->setMessage("Sauvegarde OK","",100);
	}

}


