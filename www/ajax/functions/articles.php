<?php

function doArticle($sWant,$sVars)
{
  switch($sWant)
  {
    case 'loadHeader':
      $aRet = loadHeader($sVars);
      break;

    case 'listArticles':
      $aRet = listArticles($sVar);
      break;

    case 'add':
      $aRet = addArticle($sVars);
      break;

    case 'load':
      $aRet = loadArticleId($sVars);
      break;  

    case 'del':
      $aRet = delArticle($sVars);
      break;
    
    case 'edit':
      $aRet = editArticle($sVars);
      break;

    case 'save':
      $aRet = saveArticle($sVars);
      break;
  
    default:
      $aRet=["Errno" => -1,"ErrMsg" => "Dans articles.php Err: $sWant non défini !"];
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
 * listArticles
 * 
 * Récupère la liste des articles (sys_article)
 * 
 * @param      void
 * @return     array  Liste
 */
function listArticles()
{
  $Ok = false;
  $aRet=["Errno" => -1, "ErrMsg" => "ERR dans listArticles"];
  include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
  $db = new cMariaDb($Cfg);
  $sSQL = "SELECT id, sTitre, dateCreation FROM sys_articles ORDER BY dateCreation DESC;";
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

/**
 * addArticle
 */
function addArticle($sVars)
{
  include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
  $db = new cMariaDb($Cfg);
  $sSQL = "INSERT INTO sys_articles SET sTitre=\"$sVars\";";
  // Lancer requete
  $db->Query($sSQL);
  // récupérer id, date
  $iId = $db->getLastId();
  $sSQL = "SELECT id,sTitre,dateCreation FROM sys_articles WHERE sTitre=\"$sVars\";";
  $aTmp = $db->getAllFetch($sSQL);
  $Display  = substr($aTmp[0]["dateCreation"],8,2) . "/" . substr($aTmp[0]["dateCreation"],5,2) . "/";
  $Display .= substr($aTmp[0]["dateCreation"],0,4) . " à " . substr($aTmp[0]["dateCreation"],11,8);
  $Display .= " - ".$aTmp[0]["sTitre"];
  $aRet = [
    "Errno" => 0, 
    "ErrMsg" => "OK", 
    "Display" => $Display, 
    "id" => $aTmp[0]["id"]];
  return $aRet;
}

/**
 * loadArticle
 * 
 * charge un article depuis la base de données
 */
function loadArticleId($sVars)
{
  include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
  $db = new cMariaDb($Cfg);
  $sSQL = "SELECT id, tContenu FROM sys_articles WHERE id=$sVars;";
  // Lancer requete
  $aNew = $db->getAllFetch($sSQL);
  $sHtml = stripslashes($aNew[0]["tContenu"]);
  $aRet=["Errno" => 0, "ErrMsg" => "OK", "html" => $sHtml]; 
  return $aRet;
}

/**
 * delArticle
 * 
 */
function delArticle($sVars)
{
  include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
  $db = new cMariaDb($Cfg);
  $sSQL = "DELETE FROM sys_articles WHERE id=$sVars;";
  // Lancer requete
  $db->Query($sSQL);
  $aRet = ["Errno" => 0,"ErrMsg" => "OK", "SQL" => $sSQL];
  return $aRet;
}

function editArticle($sVars)
{
  include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
  $aVars = json_decode($sVars,true);
  $db = new cMariaDb($Cfg);
  $sSQL = "UPDATE sys_articles SET sTitre=\"".$aVars["Titre"]."\" WHERE id=".$aVars["id"].";";
  // Lancer requete
  $db->Query($sSQL);
  $aRet = ["Errno" => 0,"ErrMsg" => "OK", "SQL" => $sSQL];
  return $aRet;
}

function saveArticle($sVars)
{
  $aVars = json_decode($sVars,true);
  include_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  include_once($_SERVER["DOCUMENT_ROOT"]."/class/mariadb.class.php");
  $db = new cMariaDb($Cfg);
  $Contenu = addslashes($aVars["Contenu"]);
  $sSQL = "UPDATE sys_articles set tContenu=\"$Contenu\" WHERE id=".$aVars["id"].";";
  // Lancer requete
  $db->Query($sSQL);
  $aRet = ["Errno" => 0, "ErrMsg" => "OK", "SQL" => $sSQL];
  return $aRet;

}