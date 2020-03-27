<?php
include("webmaster/define.php");
mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysql_select_db(base_de_donnees); // Sélection de la base 
$question="DELETE FROM repertoire WHERE Code='".$_GET['id']."'";
$reponse = mysql_query($question) or die(mysql_error());
mysql_close();
$page="Location: repertoire.php?orderby=";
$page.=$_GET['orderby'];
header($page);
?>