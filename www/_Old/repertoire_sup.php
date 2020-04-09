<?php
include("webmaster/define.php");
mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysqli_select_db(base_de_donnees); // Sélection de la base 
$question="DELETE FROM repertoire WHERE Code='".$_GET['id']."'";
$reponse = mysqli_query($question) or die(mysqli_error());
mysqli_close();
$page="Location: repertoire.php?orderby=";
$page.=$_GET['orderby'];
header($page);
?>