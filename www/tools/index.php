<?php
// TODO ces fichiers seront retirés pour la mise en production
if( !isset($_POST["Action"]) ) {
    echo file_get_contents("tmpl/main.tmpl");
}

require_once('../config/config.php');
require_once('../class/autoload.php');

switch( $_POST["Action"])
{
    case 'SQL':
      // Construire la requete d'ajout
      $sSQL  = "INSERT INTO sys_db_update (id,description,version,sql_text) VALUES ";
      $sSQL .= "(NULL,'".$_POST["Desc"]."','".$_POST["Version"]."','".base64_encode($_POST["SQL"])."');";
      //error_log($sSQL);
      $db = new MariaDb($Cfg);
      $db->Query($sSQL);
      break;

    case 'getSQL';
      $sReturn = "";
      $sSQL = "SELECT id,sql_text FROM sys_db_update WHERE version='".$_POST["Version"]."';";
      $db = new MariaDb($Cfg);
      $aResult = $db->getAllFetch($sSQL);

      foreach($aResult as $aSQL)
      {
        //error_log(print_r($aSQL,true));
        $sReturn .= base64_decode($aSQL["sql_text"])."\n";
      }
      echo $sReturn;
      break;

    default:
      echo json_encode(__DIR__);
      break;
}