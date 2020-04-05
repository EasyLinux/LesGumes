<?php
/*==================================================================================================
  =                                Gestion des sauvegardes                                         =
  ==================================================================================================*/  
/**
 * restoreNow
 * 
 * Récupère les fichiers contenus dans la sauvegarde en fonction du type demandé. 
 * NB: si on veut récupérer tout le contenu, il faut que l'utilisateur apache puisse
 * écrire dans l'arborescence (ce qui n'est pas conseillé !)
 * 
 * @param  string  $sFile   Nom du fichier (/Backup/backup-<date Ymd-His>.zip)
 * @param  string  $sType   Type de restauration :
 *                                  dbonly - uniquement la base de données
 *                                  dbuser - la base et les données de /media
 *                                  all    - tout le contenu
 * @return array   code d'erreur
 */
function restoreNow($sFile, $sType)
{
  require_once(__DIR__."/../config/config.php");
  require_once(__DIR__."/../class/autoload.php");
  $sSearch="";     // @var string  $sSearch    Répertoire à extraire
  $oZip = new ZipArchive;
  $oRes = $oZip->open($_SERVER["DOCUMENT_ROOT"]."/_Backup/".$sFile);
  if ($oRes === true) {
    switch( $sType )
    {
      case "dbuser":
        // on ne veut que les fichiers dans media/
        $sSearch  = "media/";
      case "all":
        // Lister les fichiers du zip
        for($i = 0; $i < $oZip->numFiles; $i++) 
        {
          $sFileName = $oZip->getNameIndex($i);
          // si fichier commence par (media/ dans le cas de 'dbuser', rien pour 'all')
          if( strstr($sFileName,$sSearch)){
            // Extraire ce fichier   TODO virer tmp
            $oZip->extractTo($_SERVER["DOCUMENT_ROOT"],$sFileName);
            }
        }            

      case "dbonly":        
        $sBaseSql = $_SERVER["DOCUMENT_ROOT"]."/tmp/base.sql";  // @var string Chemin du fichier .sql
        $sTmp = $_SERVER["DOCUMENT_ROOT"]."/tmp";
        $oZip->extractTo($sTmp,"tools/_Backup/base.sql");
        $oZip->close();
        // base.sql est dans <root>/tmp/tools/_Backup/base.sql, on le déplace
        // puis on fait du ménage
        rename($sTmp."/tools/_Backup/base.sql",$sBaseSql);
        // Récupérer le contenu du fichier SQL
        $sSqlLines = file($sBaseSql);
        if( $sSqlLines == false ){
          $aRet = ["Errno" => -3, "ErrMsg" => "Ne peut ouvrir : $sBaseSql"];
          return $aRet;
        }
        $db = new cMariaDb($Cfg);
        $sSql = '';
        // parcourir le fichier sql et exécuter requete par requete
        foreach ($sSqlLines as $sLine)	
        {
          
          $sStartWith = substr(trim($sLine), 0 ,2);
          $sEndWith = substr(trim($sLine), -1 ,1);
          
          if (empty($sLine) || $sStartWith == '--' || $sStartWith == '/*' || $sStartWith == '//') {
            // ligne(s) inutile(s) pour SQL
            continue;
          }
            
          $sSql .= $sLine;
          if ($sEndWith == ';') {
            // fin de requete ....; on exécute
            $db->Query($sSql);
            if( $db->getErrno() != 0 ){
              $aRet = ["Errno" =>$db->getErrno(), 
                       "ErrMsg" => $db->getErrorMsg(). "($sSql)"
                      ];
              return $aRet;
            }
            
            $sSql= '';		
          }
        }
        
        $aRet = ["Errno" => 0, "ErrMsg" => "OK"];
        // nettoyer /tmp
        $sDir = $_SERVER["DOCUMENT_ROOT"]."/tmp";
        $di = new RecursiveDirectoryIterator($sDir, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ( $ri as $file ) {
          $file->isDir() ?  rmdir($file) : unlink($file);
        }
        $oZip->close();
        break;
      
      default:
        $aRet=["Errno" => -2, "ErrMsg" => "Backup type undefined !"];
    }
    $aRet = ["Errno" => 0, "ErrMsg" => "OK", "To" => $_SERVER["DOCUMENT_ROOT"]."/tmp"];
    
    return $aRet;
  } else {
    $aRet = ["Errno" => $oRes, "ErrMsg" => "Erreur à l'ouverture de l'archive"];
    return $aRet;    
  }

}

