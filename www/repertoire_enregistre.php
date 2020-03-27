<?php
include("webmaster/define.php");
mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysqli_select_db(base_de_donnees); // Sélection de la base 
$question="SELECT * FROM amap_generale WHERE id='".$_POST['CodeID']."'";
$reponse = mysqli_query($question) or die(mysqli_error());
$donnees = mysqli_fetch_array($reponse);
$nom=$donnees['Nom'];
$prenom=$donnees['Prenom'];
$question="INSERT INTO repertoire (Code, Nom, Prenom, Adresse, Commune, Tel_1, Tel_2, Mail)";
$question.=" VALUES ('".$_POST['CodeID']."', '".$nom."', '".$prenom."'";
if($_POST['AdresseID']=='') $question.=", NULL"; else $question.=", '".$_POST['AdresseID']."'";
if($_POST['CommuneID']=='') $question.=", NULL"; else $question.=", '".$_POST['CommuneID']."'";
if($_POST['Tel_1ID']=='') $question.=", NULL"; else $question.=", '".$_POST['Tel_1ID']."'";
if($_POST['Tel_2ID']=='') $question.=", NULL"; else $question.=", '".$_POST['Tel_2ID']."'";
if($_POST['MailID']=='') $question.=", NULL"; else $question.=", '".$_POST['MailID']."'";
$question.=")";
$reponse=mysqli_query($question) or die(mysqli_error());
mysqli_close();
$page="Location: repertoire.php";
header($page);
?>