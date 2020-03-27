<?php
include_once("define.php");
$question=stripslashes($_POST['commande']);
echo $question;
mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysqli_select_db(base_de_donnees); // Sélection de la base 
$reponse = mysqli_query($question);// or die(mysqli_error());
echo "<br />réponse : ";
echo $reponse;
echo "<br />Erreur : ";
echo mysqli_error();
mysqli_close();

?>
<p>
<a href="../index.php">Accueil</a>------<a href="javascript:history.back();">Page précédente</a>
</p>
