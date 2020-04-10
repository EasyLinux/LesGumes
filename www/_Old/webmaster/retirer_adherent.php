<?php
include_once("define.php"); 
mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysqli_select_db(base_de_donnees); // Sélection de la base 
$question="DELETE FROM ".$_GET['amap']." WHERE id=".$_GET['id'];
$reponse=mysqli_query( $question) or die(mysqli_error());
if ( $_GET['amap']=="amap_chevres" || $_GET['amap']=="amap_produits_laitiers")    {
      $question="DELETE FROM ".$_GET['amap']."_cde WHERE id=".$_GET['id'];
      $reponse=mysqli_query( $question) or die(mysqli_error());
}
mysqli_close();
$page="Location: webmaster_infos.php?nom_amap=";
$page.=$_GET['amap'];
header($page);
?>