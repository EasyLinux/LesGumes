<?php
include_once("define.php"); 


// test valeurs obligatoires
$nom = strtoupper ($_POST['nom']);
$nom= "'".$_POST['nom']."'";
$prenom= "'".$_POST['prenom']."'";
$mail= "'".$_POST['mail']."'";
$error ="Contacter l'administrateur du site";
$password = SHA1($mail);


if ( isset($_POST['mail']) && isset($_POST['nom']) && isset($_POST['prenom']) && !empty($_POST['nom'] ) && !empty($_POST['prenom'] ) && !empty($_POST['mail'] )){
	mysql_connect(hote, login, mot_passe_sql); // Connexion Ã  MySQL
	mysql_select_db(base_de_donnees); // SÃ©lection de la base 
	mysql_error(); // lecture pour vider d'Ã©ventuels messages antÃ©rieurs
	
	$adresse = $_POST['adresse']!="NULL" ? "'".$_POST['adresse']."'" : "NULL";
	$codePostal = $_POST['codePostal']!="NULL" ? "'".$_POST['codePostal']."'" : "NULL";
	$ville = $_POST['ville']!="NULL" ? "'".$_POST['ville']."'" : "NULL";
	$telephone = $_POST['telephone']!="NULL" ? "'".$_POST['telephone']."'" : "NULL";
	$portable = $_POST['portable']!="NULL" ? "'".$_POST['portable']."'" : "NULL";
	$commentaire = $_POST['comment']!="NULL"  ? "'".$_POST['comment']."'" : "NULL";
	$date ="'".date("Y-m-d",time())."'";
	
	// ajout dans amap_generale
	$question="INSERT INTO amap_generale (id, Nom, Prenom, e_mail, Adresse, Code_postal,Ville,Telephone, Tel_portable, Login, Mot_passe, Etat_asso)  VALUES (NULL,".$nom.",".$prenom.",".$mail.",".$adresse.",".$codePostal.",".$ville.",".$telephone.",".$portable.",".$mail.",'".$password."','LISTE_ATTENTE');";
	mysql_query( $question);
	$error = mysql_error();
	
	if ( strlen($error) ==0) {
		//on rÃ©cupÃ¨re l'id affectÃ© par le systÃ¨me
		$question='SELECT id from amap_generale WHERE e_mail='.$mail;
		$reponse = mysql_query( $question ) or die(mysql_error());
		$reponse=mysql_fetch_array($reponse);
		$id = $reponse[0];

		//puis on l'ajoute dans liste d'attente
		$question="INSERT INTO amap_legumes_liste_attente (id, Nom, Prenom, Mail, Date_inscription, Commentaire)  VALUES (".$id.",".$nom.",".$prenom.",".$mail.",".$date.",".$commentaire.");";
		mysql_query( $question);
		$error = mysql_error();
	
	}
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
<!-- xml:lang : sert Ã  indiquer dans quelle langue est rÃ©digÃ©e votre page -->
	<head>
		<title>AMAP Saint-SÃ©bastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" media="all" href="styleW.css" />
		<!-- meta indique que l'on utilise des caractÃ¨res spÃ©cifiques au franÃ§ais Ã©Ã¨ÃªÃ ... -->
	</head>
	<body>
	ERREUR : Ajout impossible -> <?php echo $error; ?>
	</body>
</html>
<?php } ?>