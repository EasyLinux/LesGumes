<?php
include_once("define.php"); 
//3 param�tres : amap (quelle amap), id (quel amapien)  et etat (1 pour verrouiller, 0 pour d�verrouiller)
mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
mysql_select_db(base_de_donnees); // S�lection de la base

if ( isset($_GET['etat'] )) {
    $etat =  $_GET['etat'];
}
else {
    $etat =  1;// on verrouille par d�faut
}
$question="UPDATE ".$_GET['amap']." SET Contrat_verrouille=".$etat." WHERE id=".$_GET['id'];
$reponse=mysql_query( $question);

mysql_close();
$page="Location: webmaster_infos.php?nom_amap=";
$page.=$_GET['amap'];
header($page);
?>