<?php
include_once("define.php"); 
mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysql_select_db(base_de_donnees); // Sélection de la base 

$commentaire= $_POST['comment'];
$error ="Contacter l'administrateur du site";

if ( isset($_POST['id']) &&  $_POST['id'] != "") {

	//Recherche de l'amapien dans amap_générale
	$id = $_POST['id'];
	mysql_error(); // lecture pour vider d'éventuels messages antérieurs
	$question='SELECT * from amap_generale WHERE id='.$id;
	$reponse = mysql_query( $question ) or die(mysql_error());
	$reponse=mysql_fetch_array($reponse);

	if ( $reponse) { // ajout dans liste d'attente
		$nom = $reponse["Nom"];
		$prenom = $reponse["Prenom"];
		$mail = $reponse["e_mail"];
		$date =date("Y-m-d",time());

		$question='INSERT INTO amap_legumes_liste_attente (id, Nom, Prenom, Mail, Date_inscription, Commentaire)  VALUES ('.$id.',"'.$nom.'","'.$prenom.'","'.$mail.'","'.$date.'","'.$commentaire.'");';
		mysql_query( $question) ;
		$error = mysql_error();
	}
	else { $error= " pas d'amapien dans la base avec l' id ". $id ;}
	mysql_close();
}

if ( strlen($error) ==0) {
	$page="Location: webmaster_contrat_amap.php?amap=amap_legumes_liste_attente&amp;ordre=rien&amp;classement=id";
	header($page);
}
else  {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="stylesheet" type="text/css" media="all" href="styleW.css" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
	</head>
	<body>
	ERREUR : Ajout impossible -> <?php echo $error; echo $question?>
	</body>
</html>
<?php }
?>