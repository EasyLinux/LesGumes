<?php
/** Gestion des nouvelles
 * 
 * Ce fichier gère les fonctionnalités Ajax lièes aux nouvelles
 * 
 * @author    Serge NOEL <serge.noel@easylinux.fr>
 * @version   2.0
 */

/**
 * listNews
 * 
 * Récupère la liste des nouvelles (sys_news)
 * 
 * @param      void
 * @return     array  Liste
 */
function listNews()
{
  $aRet = [];
  include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
  $db = new cMariaDb($Cfg);
  $sSQL = "SELECT id, sTitre, dateCreation FROM sys_news ORDER BY dateCreation DESC;";
  error_log("listNews SQL : $sSQL");
  $aResponse = $db->getAllFetch($sSQL);
  foreach($aResponse as $aRec)
  {
    $sDate = substr($aRec["dateCreation"],8,2) . "/" . substr($aRec["dateCreation"],5,2) . "/";
    $sDate .= substr($aRec["dateCreation"],0,4) . " à " . substr($aRec["dateCreation"],11,8);
    $aRet[] = ["id" => $aRec["id"],
               "titre" => $aRec["sTitre"],
               "date" => $sDate];
  }
  header('content-type:application/json');
  echo json_encode($aRet); 
}

/**
 * doNews
 * 
 * Agit sur la table sys_news en fonction de l'action demandée
 * @param   string  $sAction    demande en cours <add|del|edit>
 * @param   string  $sTitre     Titre de la nouvelle (inutile pour suppression)
 * @param   int     $iId        Identifiant de la nouvelle (inutile en cas d'ajout)
 * @param   int     $sContenu   contenu de la nouvelle (lors de l'enregistrement)
 * @return  array   Tableau 
 */
function doNews($sAction,$sTitre,$iId,$sContenu)
{
  switch($sAction)
  {
    case 'add':
      include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
      include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
      $db = new cMariaDb($Cfg);
      $sSQL = "INSERT INTO sys_news SET sTitre=\"$sTitre\";";
      // Lancer requete
      $db->Query($sSQL);
      // récupérer id, date
      $iId = $db->getLastId();
      $sSQL = "SELECT id,sTitre,dateCreation FROM sys_news WHERE sTitre=\"$sTitre\";";
      $aRet = $db->getAllFetch($sSQL);
      $aRet[0]["display"]  = substr($aRet[0]["dateCreation"],8,2) . "/" . substr($aRet[0]["dateCreation"],5,2) . "/";
      $aRet[0]["display"] .= substr($aRet[0]["dateCreation"],0,4) . " à " . substr($aRet[0]["dateCreation"],11,8);
      $aRet[0]["display"] .= " - ".$aRet[0]["sTitre"];
      header('content-type:application/json');
      echo json_encode($aRet); 
      break;
    
    case 'del':
      include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
      include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
      $db = new cMariaDb($Cfg);
      $sSQL = "DELETE FROM sys_news WHERE id=$iId;";
      // Lancer requete
      $db->Query($sSQL);
      $aRet = ["Errno" => 0,"ErrMsg" => "OK"];
      header('content-type:application/json');
      echo json_encode($aRet); 
      break;

    case 'edit':
      include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
      include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
      $db = new cMariaDb($Cfg);
      $sSQL = "UPDATE sys_news SET sTitre=\"$sTitre\" WHERE id=$iId;";
      // Lancer requete
      $db->Query($sSQL);
      $aRet = ["Errno" => 0,"ErrMsg" => "OK"];
      header('content-type:application/json');
      echo json_encode($aRet); 
      break;

      case 'load':
        include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
        include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
        $db = new cMariaDb($Cfg);
        $sSQL = "SELECT id, tContenu FROM sys_news WHERE id=$iId;";
        // Lancer requete
        $aRet = $db->getAllFetch($sSQL);
        $aRet[0]["contenu"] = stripslashes($aRet[0]["tContenu"]);
        header('content-type:application/json');
        echo json_encode($aRet); 
        break;

      case 'save':
        include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
        include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
        $db = new cMariaDb($Cfg);
        $sSQL = "UPDATE sys_news set tContenu=\"".addslashes($sContenu).".\" WHERE id=$iId;";
        // Lancer requete
        error_log("SQL: ".$sSQL);
        $db->Query($sSQL);
        $aRet = ["Errno" => 0, "ErrMsg" => "OK"];
        header('content-type:application/json');
        echo json_encode($aRet); 
        break;

    case 'listNews':
      listNews();
      break;

  }
}