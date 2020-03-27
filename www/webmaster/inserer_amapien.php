<?php
include_once("define.php"); 
mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysql_select_db(base_de_donnees); // Sélection de la base 

$error="";


if( isset($_POST['nom']) && isset($_POST['mail']) && isset($_POST['prenom']) && !empty($_POST['nom'] ) && !empty($_POST['prenom'] ) && !empty($_POST['mail'] )){
	$nom = strtoupper ($_POST['nom']);
	$mdp = SHA1($_POST['mail']);
	$date =date("Y-m-d",time());

	mysql_error(); // lecture pour vider d'éventuels messages antérieurs
	$question='INSERT INTO amap_generale ( Nom, Prenom, e_mail, Adresse,Code_postal,Ville,Telephone,Tel_portable,Date_inscription, Login, Mot_passe)  VALUES ("'.$nom.'","'. $_POST['prenom'].'","'.$_POST['mail'] .'","'.$_POST['adresse'] .'","'.$_POST['codePostal'] .'","'.$_POST['ville'] .'","'	.$_POST['telephone'] .'","'.$_POST['portable'] .'","'.$date.'","'.$_POST['mail'] .'","'.$mdp.'");';
	
	$reponse = mysql_query( $question );
	$error = mysql_error();
	mysql_close();
} else {
	$error = "les champs Nom, Prenom et Mail doivent etre renseignes";
}	


if ( strlen($error) ==0) {
	header("Location: webmaster_nouveau_amapien.php");
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
	ERREUR : Ajout impossible -> <?php echo $error; ?>
	</body>
</html>
<?php }
?>