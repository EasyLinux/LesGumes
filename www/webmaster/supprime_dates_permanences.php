<?php include_once("define.php"); ?>
<?php 
/*	
	echo "amap=".$_POST['amap'];
	echo "date=".$_POST['first']; 
*/
	if ( isset($_POST['amap']) && isset($_POST['first']) ) {
		$tablePermanences= $_POST['amap']."_permanences";
		$first= date("Y-m-d",strtotime($_POST['first']));
		
		// on supprime toutes les dates avant $first
		$requete = "DELETE from ".$tablePermanences." WHERE Date<= '".$first."'";
		mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
		mysqli_select_db(base_de_donnees); // Sélection de la base 
		$ok = mysqli_query($requete);
		mysqli_close();
		$page="Location:webmaster_maj_permanence.php?amap=".$_POST['amap'];
		header($page);
	}
	else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" media="all" href="styleW.css" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
	</head>
	<body>
	ERREUR : la dernière demande n'a pas été exécuter. Contactez l'administarteur du site.
	<ul>
		<li><a href="webmaster.php?mode=<?php echo $_POST['amap']; ?>">Retour au menu webmaster</a>
		<li><a href="../index.php">Retour à l'Accueil</a>	
	</ul>
	</body>
</html>
<?php } ?>


