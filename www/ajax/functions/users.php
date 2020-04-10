<?php


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

    default:
      header('content-type:application/json');
      echo json_encode(["Errno" => "-1", "ErrMsg" => "Action non connue dans users.php"]);
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
  $_SESSION["User"] = $aUser;
  $_SESSION["Access"] = $sys->getUserRights($aUser["id"]);

  header('content-type:application/json');
  echo json_encode($aData);
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
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");

  $db = new cMariaDb($Cfg);

  // Vérifier que le login out l'Email est connu
  $sSQL = "SELECT * FROM sys_user WHERE Login='$login';";
  $aTmp = $db->getAllFetch($sSQL);
  error_log(print_r($aTmp,true));
  if( count($aTmp) == 0 )
  {
    $aRet = ["Errno" => -1, "ErrMsg" => "ERREUR, cet Email n'est pas dans notre base !", "Email" => $login];
    header('content-type:application/json');
    echo json_encode($aRet);
    return;
  }
  $id = $aTmp[0]["id"];
  $newPass = randomPassword();
  $sSQL = "UPDATE sys_user SET Mot_passe=SHA1('$newPass') WHERE id=$id;";
  error_log("UPDATE : $sSQL");
  $db->Query($sSQL);

    // TODO a completer - envoi du mail
  // Envoyer le nouveau mot de passe à l'utilisateur
  $to = $login;
  $subject = "Site AMAP - Reinitialisation du mot de passe";
  $message  = "<html><head></head><body>Bonjour";
  $message .= "Votre mot de passe a été ré-initialisé: $newPass<br />Vous pouvez vour rendre sur <a href='".$_SERVER['SERVER_NAME']."'>";
  $message .= $_SERVER['SERVER_NAME']."</a><br />";
  $message .= "Vous pouvez vous connecter sur le site puis changer de mot de passe.<br />";
  $message .= "Ce mail est un message automatique, merci de ne pas y répondre</body></html>";

  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

  mail($to, $subject, $message, $headers);
  $aRet = ["Errno" => 0, "Email" => $aTmp[0]["Login"]];

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
  require_once($_SERVER["DOCUMENT_ROOT"]."/class/autoload.php");

  $db = new cMariaDb($Cfg);
  $aRet = ["Errno" => 0, "ErrMsg" => "OK"];
  $sSQL = "SELECT * FROM sys_users SET Mot_passe=SHA1('$sPassword') WHERE id=".$_SESSION["User"]["id"].";";
  $db->Query($sSQL);
  $aRet["ErrMsg"] = "Votre mot de passe a été changé";
  header('content-type:application/json');
  echo json_encode($aRet);
}