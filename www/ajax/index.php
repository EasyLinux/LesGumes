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
// TODO must be connected with right

session_start();
switch($_POST["Action"])
{
    case 'Login':
      // Tentative de connexion
      include_once($_SERVER["DOCUMENT_ROOT"]."/ajax/functions/users.php");
      error_log(print_r($_POST,true));
      doUser('Login',$_POST["Login"],$_POST["Passwd"]);
      break;

    case 'doUser':
      // Tentative de connexion
      include_once($_SERVER["DOCUMENT_ROOT"]."/ajax/functions/users.php");
      doUser($_POST["Want"],$_POST["Login"],$_POST["Passwd"]);
      break;

    case 'Content':
      // Recharger un contenu
      echo loadContent($_POST["Content"]);
      break;

    case 'doRights':
      include_once($_SERVER["DOCUMENT_ROOT"]."/ajax/functions/manageRights.php");
      doRights($_POST['Sub'],$_POST["id"],$_POST["label"],$_POST["desc"]);
      break;

    case 'doBackup':
      include_once($_SERVER["DOCUMENT_ROOT"]."/ajax/functions/backup.php");
      doBackup($_POST["Want"],$_POST['Type'],$_POST['id']);     
      break;

    case 'loadFile':
      $sTargetFile = $_SERVER["DOCUMENT_ROOT"].$_POST["Where"];
      $sTargetFile .= basename($_FILES['file']['name']	);
      error_log("Fichier cible : ".$sTargetFile);
      if( move_uploaded_file($_FILES['file']['tmp_name'],$sTargetFile) ){
        echo "OK";
      } else {
        echo "BAD";
      }
      break;

    case 'paramTables':
      include_once($_SERVER["DOCUMENT_ROOT"]."/ajax/functions/parameters.php");
      paramTables();
      break;

    case 'updateParameters':
      include_once($_SERVER["DOCUMENT_ROOT"]."/ajax/functions/parameters.php");
      updateParameters($_POST["Type"],$_POST["Id"],$_POST["Value"],$_POST["Table"]);
      break;

    case 'doNews':
      include_once($_SERVER["DOCUMENT_ROOT"]."/ajax/functions/news.php");
      doNews($_POST["Want"],$_POST["Titre"], $_POST["Id"],$_POST["Contenu"]);
      break;
      
    case 'doEditor':
      include_once($_SERVER["DOCUMENT_ROOT"]."/ajax/functions/editor.php");
      doEditor($_POST["Sub"],$_POST["Folder"],$_POST["File"]);
      break;

    case 'doMenu':
      include_once($_SERVER["DOCUMENT_ROOT"]."/ajax/functions/menu.php");
      doMenu($_POST["Want"]);
      break;
    
      


    // case 'Upload':
    //   $sTargetFile = basename($_FILES['upload']['name']	);
    //   if( move_uploaded_file($_FILES['upload']['tmp_name'],__DIR__."/../media/$sTargetFile" ) )
    //   {
    //     $aResult["url"] = "/media/$sTargetFile";
    //   }
    //   else
    //   {
    //     $aResult["error"]["message"]="Pas bon";
    //   }
    //   header('content-type:application/json');
    //   echo json_encode($aResult);
    //   break;

    default:
      header('content-type:application/json');
      echo json_encode(["Errno" => -1, "ErrMsg" => "Action: ".$_POST["Action"] ." non utilisable"]);
      break;
}


/*==================================================================================================
  =                                Gestion du contenu                                              =
  ==================================================================================================*/  
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

    case 'Logout':  
      $_SESSION = array();
      
      // If it's desired to kill the session, also delete the session cookie.
      // Note: This will destroy the session, and not just the session data!
      if (ini_get("session.use_cookies")) {
          $params = session_get_cookie_params();
          setcookie(session_name(), '', time() - 42000,
              $params["path"], $params["domain"],
              $params["secure"], $params["httponly"]
          );
      }
      
      // Finally, destroy the session.
      session_destroy();   
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

    case 'Backup':
      // TODO need admin rights
      $tpl->template_dir = __DIR__."/../tools/templates";
      $sHtml = $tpl->display("backup.smarty");
      break;

    default:
      $sHtml = "Ne peut charger $Content";
      break;
  }
return $sHtml;
}



