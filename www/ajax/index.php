<?php

if( !isset($_POST["Action"])) {
    die("Direct access refused");
}

switch($_POST["Action"])
{
    case 'Login';
      header('content-type:application/json');
      echo Authenticate($_POST["login"],$_POST["passw"]);
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
    // Le mot de passe est stocké encrypté en sha1
    $cryptPwd = sha1($mdp);
    // Construire la requête
    $sSQL = "SELECT * FROM amap_generale WHERE Mot_passe='".$cryptPwd."' AND Login='".$login."'";
    // Le résultat doit retourner une seule ligne

    //setcookie('identification_amap',$donnees['id']);

    // validation du mot de passe
    //preg_match('#^[a-zA-Z0-9.@_-]{4,40}$#',$motdp);
    
    $Data = [
        "Errno"   => -1,
        "ErrMsg"  => "Compte ou mot de passe incorrect !"
    ];
    return json_encode($Data);

}