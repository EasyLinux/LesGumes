<?php
include_once("define.php"); 
mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysql_select_db(base_de_donnees); // Sélection de la base 
$question="DELETE FROM ".$_GET['amap']." WHERE id=".$_GET['id'];
$reponse=mysql_query( $question) or die(mysql_error());
if ( $_GET['amap']=="amap_chevres" || $_GET['amap']=="amap_produits_laitiers")    {
      $question="DELETE FROM ".$_GET['amap']."_cde WHERE id=".$_GET['id'];
      $reponse=mysql_query( $question) or die(mysql_error());
}
mysql_close();
$page="Location: webmaster_infos.php?nom_amap=";
$page.=$_GET['amap'];
header($page);
?>