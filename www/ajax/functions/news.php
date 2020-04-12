<?php
/** Gestion des nouvelles
 * 
 * Ce fichier gère les fonctionnalités Ajax lièes aux nouvelles
 * 
 * @author    Serge NOEL <serge.noel@easylinux.fr>
 * @version   2.0
 */


/**
 * doNews
 * 
 * Agit sur la table sys_news en fonction de l'action demandée
 * $sTitre,$iId,$sContenu
 * 
 * @param   string  $sAction    demande en cours <add|del|edit>
 * @param   array   $aVars      Passage des paramètres en fonction de la demande


 * @param   string  $sTitre     Titre de la nouvelle (inutile pour suppression)
 * @param   int     $iId        Identifiant de la nouvelle (inutile en cas d'ajout)
 * @param   int     $sContenu   contenu de la nouvelle (lors de l'enregistrement)
 * @return  array   Tableau 
 */
function doNews($sAction,$aVars)
{
  switch($sAction)
  {
  case 'loadHeader':
    // Charge la partie qui vient avant l'éditeur
    $aRet = loadHeader($aVars);
    break;

  case 'listNews':
    $aRet = listNews();
    break;

  case 'load':
    include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
    $db = new cMariaDb($Cfg);
    $sSQL = "SELECT id, tContenu FROM sys_news WHERE id=$aVars;";
    // Lancer requete
    $aNew = $db->getAllFetch($sSQL);
    $sHtml = stripslashes($aNew[0]["tContenu"]);
    $aRet=["Errno" => 0, "ErrMsg" => "OK", "html" => $sHtml]; 
    break;

  case 'add':
    include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
    $db = new cMariaDb($Cfg);
    $sSQL = "INSERT INTO sys_news SET sTitre=\"$aVars\";";
    // Lancer requete
    $db->Query($sSQL);
    // récupérer id, date
    $iId = $db->getLastId();
    $sSQL = "SELECT id,sTitre,dateCreation FROM sys_news WHERE sTitre=\"$aVars\";";
    $aTmp = $db->getAllFetch($sSQL);
    $Display  = substr($aTmp[0]["dateCreation"],8,2) . "/" . substr($aTmp[0]["dateCreation"],5,2) . "/";
    $Display .= substr($aTmp[0]["dateCreation"],0,4) . " à " . substr($aTmp[0]["dateCreation"],11,8);
    $Display .= " - ".$aTmp[0]["sTitre"];
    $aRet = [
      "Errno" => 0, 
      "ErrMsg" => "OK", 
      "Display" => $Display, 
      "id" => $aTmp[0]["id"]];
    break;
    
  case 'del':
    include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
    $db = new cMariaDb($Cfg);
    $sSQL = "DELETE FROM sys_news WHERE id=$aVars;";
    // Lancer requete
    $db->Query($sSQL);
    $aRet = ["Errno" => 0,"ErrMsg" => "OK"];
    break;

  case 'edit':
    include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
    $aVars = json_decode($aVars,true);
    $db = new cMariaDb($Cfg);
    $sSQL = "UPDATE sys_news SET sTitre=\"".$aVars["Titre"]."\" WHERE id=".$aVars["id"].";";
    // Lancer requete
    $db->Query($sSQL);
    $aRet = ["Errno" => 0,"ErrMsg" => "OK", "SQL" => $sSQL];
    break;

  case 'save':
    $aVars = json_decode($aVars,true);
    include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
    include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
    $db = new cMariaDb($Cfg);
    $Contenu = addslashes($aVars["Contenu"]);
    $sSQL = "UPDATE sys_news set tContenu=\"$Contenu\" WHERE id=".$aVars["id"].";";
    // Lancer requete
    $db->Query($sSQL);
    $aRet = ["Errno" => 0, "ErrMsg" => "OK", "SQL" => $sSQL];
    break;

  default:
    $aRet = ["Errno" => -1, "ErrMsg" => "Action $sAction non implémentée dans news.php"];    
    break;
  }
  header('content-type:application/json');
  echo json_encode($aRet); 

}

/**
 * loadHeader
 * 
 * Charge 
 */
function loadHeader($sVars)
{
  $aVars = json_decode($sVars,true);
  include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  include_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  include_once($_SERVER["DOCUMENT_ROOT"]."/vendor/autoload.php");
  // $db = new cMariaDb($Cfg);

  // // Generateur de templates
  $tpl = new Smarty();
  $tpl->template_dir = $_SERVER["DOCUMENT_ROOT"]."/tools/templates";
  $tpl->compile_dir =  $_SERVER["DOCUMENT_ROOT"]."/templates_c";

  $tpl->assign("var",$aVars);
  $sHtml = $tpl->fetch("editor.smarty");

  $aRet = ["Errno" => 0, "ErrMsg" => "OK", "html" => $sHtml];
  return $aRet;
}

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
  $Ok = false;
  $aRet=["Errno" => -1, "ErrMsg" => "ERR dans listNews"];
  include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
  $db = new cMariaDb($Cfg);
  $sSQL = "SELECT id, sTitre, dateCreation FROM sys_news ORDER BY dateCreation DESC;";
  $aResponse = $db->getAllFetch($sSQL);
  foreach($aResponse as $aRec)
  {
    $Ok = true;
    $sDate = substr($aRec["dateCreation"],8,2) . "/" . substr($aRec["dateCreation"],5,2) . "/";
    $sDate .= substr($aRec["dateCreation"],0,4) . " à " . substr($aRec["dateCreation"],11,8);
    $aTmp[] = [
      "id" => $aRec["id"],
      "titre" => $aRec["sTitre"],
      "date" => $sDate];
  }
  if ($Ok){
    $aRet = ["Errno"=>0, "ErrMsg"=> "OK", "news" => $aTmp];
  }
  return $aRet;
}
