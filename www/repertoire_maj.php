<?php
include("webmaster/define.php");
mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysql_select_db(base_de_donnees); // Sélection de la base 
$question="SELECT * FROM amap_generale WHERE id='".$_POST['CodeID']."'";
$reponse = mysql_query($question) or die(mysql_error());
$donnees = mysql_fetch_array($reponse);
$nom=$donnees['Nom'];
$prenom=$donnees['Prenom'];
$question="UPDATE repertoire SET";
if($_POST['AdresseID']=='') $question.=" Adresse=NULL"; else $question.=" Adresse='".$_POST['AdresseID']."'";
if($_POST['CommuneID']=='') $question.=", Commune=NULL"; else $question.=", Commune='".$_POST['CommuneID']."'";
if($_POST['Tel_1ID']=='') $question.=", Tel_1=NULL"; else $question.=", Tel_1='".$_POST['Tel_1ID']."'";
if($_POST['Tel_2ID']=='') $question.=", Tel_2=NULL"; else $question.=", Tel_2='".$_POST['Tel_2ID']."'";
if($_POST['MailID']=='') $question.=", Mail=NULL"; else $question.=", Mail='".$_POST['MailID']."'";
$question.=" WHERE Code='".$_POST['CodeID']."'";
$reponse=mysql_query($question) or die(mysql_error());
mysql_close();
$page="Location: repertoire.php";
header($page);
?>