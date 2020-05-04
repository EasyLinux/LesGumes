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

/**
 * appel de /ajax/index.php
 * 
 * @param  string  $_POST["Action"]  -> premier niveau de dispatch
 * @param  string  $_POST["Want"]    -> second niveau passé à /functions/<fonctionnalite.php>
 * @param  array   $_POST["Vars"]    -> Variables à passer 
 */
session_start();
switch($_POST["Action"])
{
    case 'Login':
      // Tentative de connexion
      include_once($_SERVER["DOCUMENT_ROOT"]."/ajax/functions/users.php");
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
      doNews($_POST["Want"],$_POST["Vars"]);
      break;
      
    case 'doEditor':
      include_once($_SERVER["DOCUMENT_ROOT"]."/ajax/functions/editor.php");
      doEditor($_POST["Sub"],$_POST["Folder"],$_POST["File"]);
      break;

    case 'doMenu':
      include_once($_SERVER["DOCUMENT_ROOT"]."/ajax/functions/menu.php");
      doMenu($_POST["Want"]);
      break;
    
    case 'gestArticle':  
      include_once($_SERVER["DOCUMENT_ROOT"]."/ajax/functions/articles.php");
      doArticle($_POST["Want"],$_POST["Vars"]);
      break;

    case 'Finder':
      include_once($_SERVER["DOCUMENT_ROOT"]."/ajax/functions/finder.php");
      Finder($_POST["Want"],$_POST["Vars"]);
      break;

    case 'contrat':  
      include_once($_SERVER["DOCUMENT_ROOT"]."/ajax/functions/contrat.php");
      contrat($_POST["Want"],$_POST["Vars"]);
      break;
  
    default:
      header('content-type:application/json');
      echo json_encode(["Errno" => -1, "ErrMsg" => "Action: ".$_POST["Action"] ." non utilisable (ajax/index.php)"]);
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

      case 'sendMail':
      // TODO need admin rights
      $tpl->template_dir = __DIR__."/../tools/templates";
      $sHtml = $tpl->display("sendmail.smarty");
      break;

      case 'Contrat':
      // TODO need admin rights
      $db = new cMariaDb($Cfg);
      $tpl->template_dir = __DIR__."/../tools/templates";
      $sSQL  = "SELECT sys_contrat.id, label AS Name, ";
      $sSQL .= "sys_parameter.value AS Type, ";
      $sSQL .= "CONCAT(prod.sNom, ' ', prod.sPrenom) AS Producteur, ";
      $sSQL .= "CONCAT(Ref.sNom,' ', Ref.sPrenom) AS Referent, ";
      $sSQL .= "IF(Verouille=1, 'OUI', 'NON') AS Verrouille ";
      $sSQL .= "FROM sys_contrat LEFT JOIN sys_user AS prod ";
      $sSQL .= "ON sys_contrat.IdProducteur = prod.id ";
      $sSQL .= "LEFT JOIN sys_user AS Ref ON sys_contrat.idReferent = Ref.id ";
      $sSQL .= "LEFT JOIN sys_parameter ON sys_contrat.idContratType = sys_parameter.id;";
      $tpl->assign("Contrats",$db->getAllFetch($sSQL)); 
      $sHtml = $tpl->display("contrat.smarty");
      //$sHtml = $sSQL;
      break;

      case 'ContratEdit':
      // TODO need admin rights
      $tpl->template_dir = __DIR__."/../tools/templates";
      $db = new cMariaDb($Cfg);
      //$sys = new cSystem($db->getDb());
      $sSQL = "SELECT id, value, link FROM sys_parameter WHERE link='tableTypeContrat';";
      $aTypes = $db->getAllFetch($sSQL);
      $tpl->assign("aTypes",$aTypes);
      $sHtml = $tpl->display("contrat-edit.smarty");
      break;

      default:
      if( strpos($Content,"_") ){
        //$sHtml = "Charger $Content";
        $id = substr($Content,strpos($Content,"_")+1);
        $db = new cMariaDb($Cfg);
        $sSQL = "SELECT tContenu FROM sys_articles WHERE id=$id;";
        $aResp = $db->getAllFetch($sSQL); 
        $sHtml  = "<article>\n";
        $sHtml .= stripslashes($aResp[0][tContenu]);
        $Html  .= "</article>";
      } else {
        $sHtml = "Ne peut charger $Content";
      }
      break;
  }
return $sHtml;
}


function loadArticle($id)
{

}
