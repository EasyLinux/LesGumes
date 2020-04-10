<?php
include_once("define.php"); 
mysqli_connect(hote, login, mot_passe_sql); // Connexion Ã  MySQL
mysqli_select_db(base_de_donnees); // SÃ©lection de la base 

$error ="Contacter l'administrateur du site";

if ( isset($_POST['id']) &&  $_POST['id'] != "") {
	//Recherche de l'amapien dans amap_gÃ©nÃ©rale
	$complete = $_POST['complete'];
	mysqli_error(); // lecture pour vider d'Ã©ventuels messages antÃ©rieurs
	$question='SELECT * from amap_generale WHERE id='.$id;
	$reponse = mysqli_query( $question ) or die(mysqli_error());
	$reponse=mysqli_fetch_array($reponse);


	if ( $reponse) { // amapien existe dans amap_general
		$Etat_asso = $reponse["Etat_asso"];
		$question='DELETE FROM amap_legumes_liste_attente WHERE id = "'.$id.'"';
		mysqli_query( $question) ;
		$error = mysqli_error();
		if ( strlen($error) ==0 && $complete == "true") {
			// supprimer l'amapien de amap_generale
			$question='DELETE FROM amap_generale WHERE id = "'.$id.'"';
			mysqli_query( $question) ;
			$error = mysqli_error();
		}
	}
	else { $error= " pas d'amapien dans la base avec l'id ". $id ;}
}
else { $error= "PrÃ©cisez l'id de la ligne Ã  supprimer !";}
mysqli_close();


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
	ERREUR : Suppression impossible -> <?php echo $error; ?>
	</body>
</html>
<?php } ?> 
