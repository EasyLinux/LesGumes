<?php

if( !isset($_POST["Action"]) ){
  die("Accès direct interdit");
}

switch( $_POST["Action"] )
{
  case 'Step2':
    // Vérification des pré-requis
    $iErr=0;
    $sMessage = "<h4>Validation des pré-requis :</h4> <ul>";
    $sMessage .= "<li>Version de PHP : ";
    if (version_compare(PHP_VERSION, '7.2.0') >= 0) {
      $sMessage .= PHP_VERSION . " <span style='color: green;'>OK</span></li>";
    } else {
      $iErr=-1;
      $sMessage .= PHP_VERSION . " <span style='color: red;'>n'est pas &gt; 7.2.0</span></li>";
    }
    $aExtensions = get_loaded_extensions();
    $sMessage .= "<li>Modules Php <ul>";
    $sMessage .= "<li>session ";
    if( array_search("session",$aExtensions)){
      $sMessage .=  "<span style='color: green;'>OK</span></li> ";
    } else {
      $sMessage .=  "<span style='color: red;'>ERR</span></li>";
      $iErr=-1;
    }
    $sMessage .= "<li>gd ";
    if( array_search("gd",$aExtensions)){
      $sMessage .=  "<span style='color: green;'>OK</span></li>";
    } else {
      $sMessage .= "<span style='color: red;'>ERR</span></li> ";
      $iErr=-1;
    }
    $sMessage .= "<li>json ";
    if( array_search("json",$aExtensions)){
      $sMessage .=  "<span style='color: green;'>OK</span></li> ";
    } else {
      $sMessage .= "<span style='color: red;'>ERR</span></li> ";
      $iErr=-1;
    }
    $sMessage .= "<li>mysqli ";
    if( array_search("mysqli",$aExtensions)){
      $sMessage .=  "<span style='color: green;'>OK</span></li> ";
    } else {
      $sMessage .= "<span style='color: red;'>ERR</span></li> ";
      $iErr=-1;
    }
    $sMessage .= "</ul></li>";
    $sMessage .= "<li>répertoire config ";
    if( is_dir(__DIR__."/../config") ){
      $sMessage .=  "<span style='color: green;'>OK</span></li>";
    } else {
      $sMessage .=  "<span style='color: red;'>ERR</span></li>";
      $iErr=-1;
    }
    $sMessage .=  "<li>Vérification des droits sur /config";
    if( is_writable(__DIR__."/config")){
      $iErr=-1;
      $sMessage .= "<span style='color: red;'>ERR</span></li>";
    } else {
      $sMessage .=" <span style='color: green;'>OK</span></li>";
    }
    $sMessage .= "</ul>";
    $aRet = ["Errno" => $iErr , "Message" => $sMessage];
    header('content-type:application/json');
    echo json_encode($aRet);
    break;

  case 'Step4':
    session_start();
    // Renvoi formulaire de Cnx à la Bdd
    $Db = new mysqli($_POST["Host"],$_POST["User"],$_POST["Pass"],$_POST["Base"]);
    $_SESSION["Db"]=$Db;
    if($Db->connect_errno) {
      $aRet = ["Errno" => $Db->connect_errno, "ErrMsg" => $Db->connect_error];
    } else {
      $aRet = ["Errno" => 0, "ErrMsg" => "Connection à la base OK"];
    }
    header('content-type:application/json');
    echo json_encode($aRet);
    break;

  case 'Step5':
    // Charge les fichiers de la base
    $sSQL = file_get_contents(__DIR__."/install.sql");
    if( $sSQL === false){
      error_log("Ne peut lire ".__DIR__."/install.sql");
    }
    if( ! $_SESSION["Db"]->query($sSQL) ){
      error_log("ERREUR Injection SQL install.sql ");
      error_log($_SESSION["Db"]->error);
    }
    $aRet["Errno"] = 0;
    header('content-type:application/json');
    echo json_encode($aRet);
    break;


}

// TODO Lire informations BDD
// TODO Générer fichier config.php
// TODO Exécuter requêtes SQL mises à jour
// TODO Supprimer répertoire install
