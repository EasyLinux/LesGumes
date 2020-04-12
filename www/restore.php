<?php
$sFile = "/tmp/backup-20200411-175357.zip";

echo "Restauration de : $sFile <br />";
echo "Restauration des fichiers...";
$oZip = new ZipArchive;
$oRes = $oZip->open($sFile);

for($i = 0; $i < $oZip->numFiles; $i++) 
{
  $sFileName = $oZip->getNameIndex($i);
  
  // si fichier commence par (media/ dans le cas de 'dbuser', rien pour 'all')
  if($sFileName != "restore.php"){
    $oZip->extractTo($_SERVER["DOCUMENT_ROOT"],$sFileName);
  }
  
}            
$oZip->close();

echo "OK<br />";
echo "Restauration de la base de données";
include_once("config/config.php");
$Db = new mysqli(
  $Cfg["MariaDb"]["Host"], 
  $Cfg["MariaDb"]["User"], 
  $Cfg["MariaDb"]["Pass"], 
  $Cfg["MariaDb"]["Base"]);

// Récupérer le contenu du fichier SQL
$sSqlLines = file("/tools/_Backup/base.sql");

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
    $db->query($sSql);

    if( $db->getErrno() != 0 ){
      echo "ErrMsg: ".$db->getErrorMsg(). "($sSql)";
    }
    
    $sSql= '';		
  }
}
        


