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
      header('content-type:application/json');
      echo json_encode(Authenticate($_POST["login"],$_POST["passw"]));
      break;

    case 'Content':
      // Recharger un contenu
      echo loadContent($_POST["Content"]);
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

    case 'listRights':
      include_once(__DIR__."/manageRights.php");
      // renvoyer la table des droits
      header('content-type:application/json');
      echo json_encode(listRights());
      break; 
      
    case 'saveRights':
      include_once(__DIR__."/manageRights.php");
      header('content-type:application/json');
      echo json_encode(saveRights($_POST["id"],$_POST["label"],$_POST["desc"]));
      break;

    case 'delRight':
      include_once(__DIR__."/manageRights.php");
      header('content-type:application/json');
      echo json_encode(delRights($_POST["id"]));
      break;   

    case 'doBackup':
      include_once(__DIR__."/../config/config.php");
      include_once(__DIR__."/../class/backup.class.php");
      // Autoriser l'utilisation d'un temps d'exécution long et de consommation mémoire plus important
      ini_set('max_execution_time', 600);
      ini_set('memory_limit','1024M');
      $Bkp = new Backup($Cfg);
      $Bkp->backupFiles("/var/www/html");
      header('content-type:application/json');
      echo json_encode(["Errno" => 0, "File" => $Bkp->getBackupFileName()]);      
      break;

    case 'loadFile':
      $sTargetFile = __DIR__."/../". $_POST["WHERE"];
      $sTargetFile .= basename($_FILES['file']['name']	);
      if( move_uploaded_file($_FILES['file']['tmp_name'],$sTargetFile) ){
        echo "OK";
      } else {
        echo "BAD";
      }
      break;

    case 'loadBackupList':
      $sFolder = $_SERVER["DOCUMENT_ROOT"]."/_Backup";
      $aFiles = array_diff(scandir($sFolder), array('..', '.'));
      foreach($aFiles as $aFile)
      {
        if( substr($aFile,0,6) == "backup")
        {
          $sIdent = substr($aFile,7,15);
          $sLabel = "Sauvegarde du ".substr($sIdent,6,2)."/".substr($sIdent,4,2)."/".substr($sIdent,0,4);
          $sLabel .= " à ".substr($sIdent,9,2).":".substr($sIdent,11,2).":".substr($sIdent,13,2);
          $aRet[] =["id" => $sIdent, "label" => $sLabel];
        }        
      }
      //$aRet = ["1" => "Test1", "2" => "test2" ];
      header('content-type:application/json');
      echo json_encode($aRet);      
      break;

    case 'restoreNow':
      include_once(__DIR__."/backup.php");
      ini_set('max_execution_time', 600);
      ini_set('memory_limit','1024M');
      $aRet = restoreNow("backup-".$_POST["Id"].".zip",$_POST['Type']);
      header('content-type:application/json');
      echo json_encode($aRet); 
      break;

    case 'paramTables':
      include_once($_SERVER["DOCUMENT_ROOT"]."/ajax/functions/parameters.php");
      paramTables();
      break;

    case 'updateParameters':
      include_once($_SERVER["DOCUMENT_ROOT"]."/ajax/functions/parameters.php");
      updateParameters($_POST["Type"],$_POST["Id"],$_POST["Value"],$_POST["Table"]);
      break;
    
    default:
      die("Action: ".$_POST["Action"] ." non utilisable");
      break;
}

/*==================================================================================================
  =                                Gestion des utilisateurs                                        =
  ==================================================================================================*/  

/**
 * Authentificate
 * 
 * Authentifie un couple login/mot de passe si le couple est valide, positionne la
 * variable de session User avec les coordonnées de la personne connectée
 * 
 * @param  string $login   Login
 * @param  string $mdp     Mot de passe
 * @return array  Contenu de User
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
  return $aData;
    //setcookie('identification_amap',$donnees['id']);

    // validation du mot de passe
    //preg_match('#^[a-zA-Z0-9.@_-]{4,40}$#',$motdp);
    
    // $Data = [
    //     "Errno"   => -1,
    //     "ErrMsg"  => "Compte ou mot de passe incorrect !"
    // ];
    // return json_encode($Data);

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

