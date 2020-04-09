<?php
include_once("define.php"); 
//3 paramètres : amap (quelle amap), id (quel amapien)  et etat (1 pour verrouiller, 0 pour déverrouiller)
mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysqli_select_db(base_de_donnees); // Sélection de la base

if ( isset($_GET['etat'] )) {
    $etat =  $_GET['etat'];
}
else {
    $etat =  1;// on verrouille par défaut
}
$question="UPDATE ".$_GET['amap']." SET Contrat_verrouille=".$etat." WHERE id=".$_GET['id'];
$reponse=mysqli_query( $question);

mysqli_close();
$page="Location: webmaster_infos.php?nom_amap=";
$page.=$_GET['amap'];
header($page);
?>