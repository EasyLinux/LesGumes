<?php
include_once("define.php");
$question=stripslashes($_POST['commande']);
echo $question;
mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
mysql_select_db(base_de_donnees); // S�lection de la base 
$reponse = mysql_query($question);// or die(mysql_error());
echo "<br />r�ponse : ";
echo $reponse;
echo "<br />Erreur : ";
echo mysql_error();
mysql_close();

?>
<p>
<a href="../index.php">Accueil</a>------<a href="javascript:history.back();">Page pr�c�dente</a>
</p>
