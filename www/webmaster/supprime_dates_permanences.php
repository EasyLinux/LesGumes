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
		mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
		mysql_select_db(base_de_donnees); // S�lection de la base 
		$ok = mysql_query($requete);
		mysql_close();
		$page="Location:webmaster_maj_permanence.php?amap=".$_POST['amap'];
		header($page);
	}
	else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert � indiquer dans quelle langue est r�dig�e votre page -->
	<head>
		<title>AMAP Saint-S�bastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="stylesheet" type="text/css" media="all" href="styleW.css" />
		<!-- meta indique que l'on utilise des caract�res sp�cifiques au fran�ais ����... -->
	</head>
	<body>
	ERREUR : la derni�re demande n'a pas �t� ex�cuter. Contactez l'administarteur du site.
	<ul>
		<li><a href="webmaster.php?mode=<?php echo $_POST['amap']; ?>">Retour au menu webmaster</a>
		<li><a href="../index.php">Retour � l'Accueil</a>	
	</ul>
	</body>
</html>
<?php } ?>


