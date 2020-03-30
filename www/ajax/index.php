<?php
/**
 * Ajax
 * 
 * Ce fichier pilote les demandes Ajax réalisées par le site, il constitue le
 * véritable moteur de l'application
 */

if( !isset($_POST["Action"])) {
    die("Direct access refused");
}

switch($_POST["Action"])
{
    case 'Login':
      // Tentative de connexion
      header('content-type:application/json');
      echo Authenticate($_POST["login"],$_POST["passw"]);
      break;

    case 'Content':
      // Recharger un contenu
      echo loadContent($_POST["Content"]);
      break;

    case 'Upload':
      $sTargetFile = basename($_FILES['upload']['name']	);
      if( move_uploaded_file($_FILES['upload']['tmp_name'],__DIR__."/../media/$sTargetFile" ) )
      {
        $aResult["url"] = "/media/$sTargetFile";
      }
      else
      {
        $aResult["error"]["message"]="Pas bon";
      }
      header('content-type:application/json');
      echo json_encode($aResult);
      break;

    default:
      die("Action: ".$_POST["Action"] ." non utilisable");
      break;
}

/**
 * Authentification
 */
function Authenticate($login,$mdp)
{
  require_once(__DIR__."/../config/config.php");
  require_once(__DIR__."/../vendor/autoload.php");
  require_once(__DIR__."/../class/autoload.php");

  $db = new cMariaDb($Cfg);
  $sys = new cSystem($db->getDb());

  $aData = $sys->getUser($login,$mdp);

  $aUser = $aData["User"];
  // Sauvegarde des informations de login
  session_start();
  $_SESSION["User"] = $aUser;
  error_log("Session ID :". session_id());
  setcookie('Amap-session',session_id());
  return json_encode($aData);
    //setcookie('identification_amap',$donnees['id']);

    // validation du mot de passe
    //preg_match('#^[a-zA-Z0-9.@_-]{4,40}$#',$motdp);
    
    // $Data = [
    //     "Errno"   => -1,
    //     "ErrMsg"  => "Compte ou mot de passe incorrect !"
    // ];
    // return json_encode($Data);

}

/**
 * loadContent
 * 
 * Charge un contenu et agit en fonction de celui ci
 * 
 * @param  string  $Content  contenu à charger
 * @return string  Code HTML à renvoyer 
 */
function loadContent($Content)
{
  require_once(__DIR__."/../config/config.php");
  require_once(__DIR__."/../vendor/autoload.php");
  require_once(__DIR__."/../class/autoload.php");
  $tpl = new Smarty();
  $tpl->template_dir = __DIR__."/../templates";
  $tpl->compile_dir = __DIR__."/../templates_c";

  switch($Content)
  {
    case 'Login':
      // Boite de dialogue Login
      $sHtml = $tpl->display("login.smarty");
      break;

    case 'Main':
      // Contenu de l'accueil
      $db = new cMariaDb($Cfg);
      $sys = new cSystem($db->getDb());
      $aNews = $sys->getNews(5);
      $tpl->assign("News",$aNews);
      $sHtml = $tpl->display("content.smarty");
      break;

    case 'Admin':
      // TODO need admin rights
      $tpl->template_dir = __DIR__."/../tools/templates";
      $sHtml = $tpl->display("admin.smarty");
      break;

    default:
      $sHtml = "Ne peut charger $Content";
      break;
  }
return $sHtml;
}