<?php include_once("define.php"); ?>
<?php 
/*	
	echo "amap=".$_POST['amap'];
	echo "periode=".$_POST['periode'];
	echo "first=".$_POST['first'];
	echo "last=".$_POST['last']; 
*/
	if ( isset($_POST['amap']) && isset($_POST['periode']) && isset($_POST['first']) && isset($_POST['last']) ) {
		$ok = true;
	}
	
	if ( $ok) {	
		$tablePermanences= $_POST['amap']."_permanences";
		$periode = $_POST['periode'];
		$first= strtotime($_POST['first']);
		$first=$first+12*60*60;
		$last= strtotime($_POST['last']); 
		$last=$last+12*60*60;
		
		// avec la période, le min et le max : on insère les dates dans la table permanence
		if ($last < $first) $last = $first;
		$requete = "INSERT into ".$tablePermanences." (Date) VALUES ";
		$prem=1;
		while ( $first <= $last) {
			if ($prem) { $prem =0;} else { $requete = $requete.",";}
			// inserer la date $first dans la base
			$requete = $requete."('".date("Y-m-d H:i:s",$first)."')";
			// calcul de la date suivante
			$first = $first + $periode*24*60*60;
		}
		$requete = $requete.";";

		mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
		mysql_select_db(base_de_donnees); // Sélection de la base 
		$req = mysql_query($requete);
		if ($req == FALSE) $ok=false;	
		mysql_close();
//echo $requete; $ok=false;
	}
	if ( $ok ) { 
		$page="Location:webmaster_maj_permanence.php?amap=".$_POST['amap'];
		header($page);
	} else { 
	// on affiche qu'il y a eu une erreur : c'est le minimum ...
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
	ERREUR : la dernière demande n'a pas été exécutée. Contactez l'administarteur du site.
	<ul>
		<li><a href="webmaster.php?mode=<?php echo $_POST['amap']; ?>">Retour au menu webmaster</a>
		<li><a href="../index.php">Retour à l'Accueil</a>
	
	</ul>
	</body>
</html>
<?php } ?>


