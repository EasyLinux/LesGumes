<?php
include_once("define.php"); 
mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysql_select_db(base_de_donnees); // Sélection de la base 

$error ="Contacter l'administrateur du site";

if ( isset($_POST['id']) &&  $_POST['id'] != "") {
	//Recherche de l'amapien dans amap_générale
	$complete = $_POST['complete'];
	mysql_error(); // lecture pour vider d'éventuels messages antérieurs
	$question='SELECT * from amap_generale WHERE id='.$id;
	$reponse = mysql_query( $question ) or die(mysql_error());
	$reponse=mysql_fetch_array($reponse);


	if ( $reponse) { // amapien existe dans amap_general
		$Etat_asso = $reponse["Etat_asso"];
		$question='DELETE FROM amap_legumes_liste_attente WHERE id = "'.$id.'"';
		mysql_query( $question) ;
		$error = mysql_error();
		if ( strlen($error) ==0 && $complete == "true") {
			// supprimer l'amapien de amap_generale
			$question='DELETE FROM amap_generale WHERE id = "'.$id.'"';
			mysql_query( $question) ;
			$error = mysql_error();
		}
	}
	else { $error= " pas d'amapien dans la base avec l'id ". $id ;}
}
else { $error= "Précisez l'id de la ligne à supprimer !";}
mysql_close();


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
	ERREUR : Suppression impossible -> <?php echo $error; ?>
	</body>
</html>
<?php } ?> 
