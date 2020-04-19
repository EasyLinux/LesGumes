<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function doUser($sAction,$sLogin,$sPassword)
{
  switch( $sAction )
  {
    case 'Login':
      Authenticate($sLogin,$sPassword);
      break;
      
    case 'resetPassword':
      resetPwd($sLogin);
      break;

    case 'changePass':
      changePass($sPassword);
      break;

    case 'changePassId':
      changePassId($sPassword,$sLogin);
      break;

    case 'listUsers':
      listUsers();
      break;

    case 'testEmail':
      testEmail($sLogin);
      break;

    case 'loadUserId':
      loadUserId($sLogin);
      break;

    case 'save':
      saveUser($sLogin);
      break;

    case 'delUser':
      delUser($sLogin);
      break;

    default:
      header('content-type:application/json');
      echo json_encode([
        "Errno" => "-1", 
        "ErrMsg" => "Action ($sAction) non connue dans users.php", 
        "html" => "Action non connue dans users.php"]);
      break;
  }
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
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/vendor/autoload.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");

  $db = new cMariaDb($Cfg);
  $sys = new cSystem($db->getDb());

  $aUser = $sys->getUser($login,$mdp);
  if( $aUser["Errno"] != 0){
    header('content-type:application/json');
    echo json_encode($aUser);
    return;
    }

  // Sauvegarde des informations de login
  $_SESSION["User"] = $aUser["User"];
  $_SESSION["Access"] = $sys->getUserRights($aUser["User"]["id"]);

  header('content-type:application/json');
  echo json_encode([
    "Errno"   => 0,
    "ErrMsg"  => "OK",
    "User"    => [
      "Nom"    => $aUser["User"]["sNom"],
      "Prenom" => $aUser["User"]["sPrenom"] 
    ]]);
}

/**
 * resetPwd
 * 
 * Change le mot de passe de la personne désignée par son mot de passe
 * et envoi le nouveaux mot de ppasse à l'utilisateur
 * 
 * @param  string $login   Login
 * @return array  Résultat
 */
function resetPwd($login)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/vendor/autoload.php");

  $db = new cMariaDb($Cfg);

  // Vérifier que le login out l'Email est connu
  $sSQL = "SELECT * FROM sys_user WHERE sLogin='$login';";
  $aTmp = $db->getAllFetch($sSQL);
  if( count($aTmp) == 0 )
  {
    $aRet = ["Errno" => -1, "ErrMsg" => "ERREUR, cet Email n'est pas dans notre base !", "Email" => $login];
    header('content-type:application/json');
    echo json_encode($aRet);
    return;
  }
  $id = $aTmp[0]["id"];
  $newPass = randomPassword();
  $sSQL = "UPDATE sys_user SET pMotPasse=SHA1('$newPass') WHERE id=$id;";
  $db->Query($sSQL);

  // TODO a completer - envoi du mail
  // Envoyer le nouveau mot de passe à l'utilisateur

  error_log("Mail: ".$Cfg["Smtp"]["Host"].":".$Cfg["Smtp"]["Port"]);


  $mail = new PHPMailer(true);
  //Server settings
  $mail->SMTPDebug = SMTP::DEBUG_SERVER;                   // Enable verbose debug output
  $mail->isSMTP();                                            // Send using SMTP
  $mail->Host       = $Cfg["Smtp"]["Host"];                   // Set the SMTP server to send through
  $mail->SMTPAuth   = $Cfg["Smtp"]["SmtpAuth"];               // Enable SMTP authentication
  if( $mail->SMTPAuth ){
    $mail->Username   = $Cfg["Smtp"]["User"];                 // SMTP username
    $mail->Password   = $Cfg["Smtp"]["Pass"];                 // SMTP password
  }
  $mail->Port       = $Cfg["Smtp"]["Port"];                   // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
  if($mail->Port == 25){
    $mail->SMTPAutoTLS = false;
    $mail->SMTPSecure = false;
  } else {
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
  }
  

  //Recipients
  $mail->setFrom($Cfg["Smtp"]["FromEmail"], $Cfg["Smtp"]["FromName"]);
  $mail->addAddress($login, 'Joe User');     // Add a recipient

  // Content
  $mail->isHTML(true);                                  // Set email format to HTML
  $mail->Subject = 'Site AMAP - Reinitialisation du mot de passe';
  $message  = "<html><head></head><body>Bonjour<br />";
  $message .= "<p>Votre mot de passe a &eacute;t&eacute; r&eacute;-initialis&eacute;: $newPass</p><p>Vous pouvez vour rendre sur <a href='".$_SERVER['SERVER_NAME']."'>";
  $message .= $_SERVER['SERVER_NAME']."</a></p>";
  $message .= "<p>Vous pouvez vous connecter sur le site puis changer de mot de passe.<br />";
  $message .= "Ce mail est un message automatique, merci de ne pas y r&eacute;pondre</p></body></html>";
  $mail->Body    = $message;
  $message  = "Bonjour\n";
  $message .= "Votre mot de passe a et re-initialise: $newPass</p><p>Vous pouvez vour rendre sur ".$_SERVER['SERVER_NAME']."\n";
  $message .= "Vous pouvez vous connecter sur le site puis changer de mot de passe.\n";
  $message .= "Ce mail est un message automatique, merci de ne pas y r&eacute;pondre\n";
  $mail->AltBody = $message;

  $mail->send();

  $aRet = ["Errno" => 0, "ErrMsg" => "OK"];

  header('content-type:application/json');
  echo json_encode($aRet);
}


function randomPassword() {
  $pass = "";
  $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
  for ($i = 0; $i < 8; $i++) {
      $n = rand(0, strlen($alphabet));
      $pass .= substr($alphabet,$n,1);
  }
  return $pass;
}

function changePass($sPassword)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");

  $db = new cMariaDb($Cfg);
  $aRet = ["Errno" => 0, "ErrMsg" => "OK"];
  $sSQL = "UPDATE sys_user SET pMotPasse=SHA1('$sPassword') WHERE id=".$_SESSION["User"]["id"].";";
  $db->Query($sSQL);
  //$aRet["ErrMsg"] = $sSQL;
  $aRet["ErrMsg"] = "Votre mot de passe a été changé";
  header('content-type:application/json');
  echo json_encode($aRet);
}

function changePassId($sPassword,$id)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");

  $db = new cMariaDb($Cfg);
  $aRet = ["Errno" => 0, "ErrMsg" => "OK"];
  $sSQL = "UPDATE sys_user SET pMotPasse=SHA1('$sPassword') WHERE id=$id;";
  $db->Query($sSQL);
  $aRet = ["Errno" => 0, "ErrMsg" => "OK"];
  header('content-type:application/json');
  echo json_encode($aRet);
}

/**
 * listUsers
 * 
 * renvoi un tableau HTML avec la liste des utilisateurs
 */
function listUsers()
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/vendor/autoload.php");
  $db = new cMariaDb($Cfg);

  // Generateur de templates
  $tpl = new Smarty();
  $tpl->template_dir = $_SERVER["DOCUMENT_ROOT"]."/tools/templates";
  $tpl->compile_dir =  $_SERVER["DOCUMENT_ROOT"]."/templates_c";

  $sSQL = "SELECT id,sNom, sPrenom, sEmail, sTelMobile, sLogin FROM sys_user WHERE bActive=1 ORDER BY sNom, sPrenom;";

  // Récupérer les éléments 
  $tpl->assign("Users",$db->getAllFetch($sSQL));
  $sHtml = $tpl->fetch("editUsers.smarty");
  error_log("HTML : ".$sHtml);
  $aRet = ["Errno" => 0, "html" => $sHtml ];
  header('content-type:application/json');
  echo json_encode($aRet); 

}

/**
 * testEmail
 * 
 * l'email doit être unique, renvoi une erreur si ce n'est pas le 
 * cas
 * @param   string  $sEmail   email à valider
 * @return  array   code d'erreur
 */
function testEmail($sEmail)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  $db = new cMariaDb($Cfg);

  $sSQL = "SELECT id, sEmail, bActive FROM sys_user WHERE sEmail='$sEmail';";
  $aTmp = $db->getAllFetch($sSQL);
  error_log("testEmail: ".print_r($aTmp,true));
  if( count($aTmp) > 0){
    if( $aTmp[0]["bActive"] == 1 ){
      $aRet = [
        "Errno"  => -1, 
        "ErrMsg" => "Email déjà utilisé !\n\nBasculer sur la fiche ?",
        "id"     => $aTmp[0]["id"],
        "active" => true 
      ];  
    } else {
      $aRet = [
        "Errno"  => -1, 
        "ErrMsg" => "Email déjà utilisé dans une fiche archivée, la réactiver ?",
        "id"     => $aTmp[0]["id"],
        "active" => false 
      ];
    } 
  } else {
    $aRet = ["Errno" => 0, "ErrMsg" => "OK"];
  }
  header('content-type:application/json');
  echo json_encode($aRet); 

}


/**
 * loadUserId
 * 
 * charge la fiche utilisateur identifiée par id
 * 
 * @param   int  $id   id utilisateur
 * @return  array   code d'erreur
 */
function loadUserId($id)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  $db = new cMariaDb($Cfg);

  $sSQL = "SELECT * FROM sys_user WHERE id=$id;";
  $aTmp = $db->getAllFetch($sSQL);
  if( count($aTmp) != 1){
    $aRet = [
      "Errno"  => -1, 
      "ErrMsg" => "Impossible de charger la fiche !"
    ];  
  } else {
    if( $aTmp[0]["bActive" == 0] ){
      // Fiche désactivée, on la ré-active
      $sSQL = "UPDATE sys_user SET bActive=1 WHERE id=$id;";
      $db->Query($sSQL);
    }
    // On ne transmet pas le mot de passe !
    unset($aTmp[0]["pMotPasse"]);

    
  }

  // Rechercher la liste des droits 
  $sSQL = "SELECT id, sLabel FROM sys_right;";
  $Rights = $db->getAllFetch($sSQL);
  // recherche des droits de l'utilisateur
  $sSQL = "SELECT idRights FROM sys_user_rights WHERE idUser=$id;";
  $userRights = $db->getAllFetch($sSQL);
  // Mettre en forme le résultat
  $usrListRights=[];
  foreach($userRights as $uRight)
  {
    $usrListRights[] = $uRight["idRights"];
  }
  
  $aUsrRights=[];
  foreach($Rights as $Right)
  {
    $aUsrRights[] = [
      "idRight"  => $Right["id"],
      "sLabel"   => $Right["sLabel"],
      "gotRight" => in_array($Right["id"],$usrListRights) ];
  }
  // $aUsrRights contient la liste des droits possibles et gotRight à true quand le droit 
  // est donné à la personne
//  $aRet = ["Errno" => 2, "ErrMsg" => "Test ".print_r($aUsrRights,true) ];
  $aRet = ["Errno" => 0, "ErrMsg" => "OK", "User" => $aTmp[0], "Rights" => $aUsrRights ];
  header('content-type:application/json');
  echo json_encode($aRet); 
}

/**
 * saveUser
 * 
 * enregistre la fiche utilisateur 
 * 
 * @param   array   data
 * @return  array   code d'erreur
 */
function saveUser($sData)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");

  // Récupérer les données
  $aData = json_decode($sData,true);
  $db = new cMariaDb($Cfg);
  if( $aData["User"]["id"] == 0 ){
    // id=0 > Ajoute
    $Columns = "";
    $Values = "";
    foreach( $aData["User"] as $key => $value )
    {
      if( $key != "id"){
        $Columns .= $key.",";
        $Values  .= "'".addslashes($value)."',";  
      }
      if( $key == "sEmail"){
        $Columns .= "pMotPasse,";
        $Values  .= "SHA1('".$value."'),";
      }
    }
    $Columns = substr($Columns,0,-1);
    $Values = substr($Values,0,-1);
    $sSQL = "INSERT INTO sys_user ($Columns,dDateInscription) VALUES ($Values,'".date("Y:m:d H:i:s")."');";
    $aTmp = $db->Query($sSQL);
    $id = $db->getLastId();
  } else {
    $Set = "";
    foreach( $aData["User"] as $key => $value )
    {
      if( $key != "id"){
        $Set .= $key."='".addslashes($value)."',";
      }
    }
    $Set = substr($Set,0,-1);
    $id = $aData["User"]["id"];
    $sSQL = "UPDATE sys_user SET $Set WHERE id=$id;";
    $aTmp = $db->Query($sSQL);
  }
  // Méthode pas très élégante, mais il faudrait faire un delta entre tableaux 
  // et agir en conséquence... 
  // On supprime tout et on insert
  // Sauvegarde des droits ($aData["Rights"])
  $sSQL = "DELETE FROM sys_user_rights WHERE idUser=$id";
  $db->Query($sSQL);
  foreach($aData["Rights"] as $Right) {
    $sSQL = "INSERT INTO sys_user_rights SET idRights=$Right, idUser=$id;";
    $db->Query($sSQL);
  }

  // $aRet = ["Errno" => 2, 
  //          "ErrMsg" => $sTmp, 
  //          "SQL" => $sSQL ];

  $aRet = ["Errno" => 0, "ErrMsg" => "OK", "SQL" => $sSQL ];

  header('content-type:application/json');
  echo json_encode($aRet); 
}

/**
 * delUser
 * 
 * Désactive la fiche utilisateur identifiée par id
 * 
 * @param   int  $id   id utilisateur
 * @return  array   code d'erreur
 */
function delUser($id)
{
  require_once($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");
  $db = new cMariaDb($Cfg);

  // Fiche active, on la désactive
  $sSQL = "UPDATE sys_user SET bActive=0 WHERE id=$id;";
  $db->Query($sSQL);
  $aRet = ["Errno" => 0, "ErrMsg" => "OK"];
  header('content-type:application/json');
  echo json_encode($aRet); 
}

