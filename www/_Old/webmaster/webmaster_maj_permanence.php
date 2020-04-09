<?php include_once("define.php"); ?>
<?php 	
	$amap  = $_GET['amap'];
	$tablePermanences= $amap."_permanences";

	mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysqli_select_db(base_de_donnees); // Sélection de la base 
	$reponse = mysqli_query("SELECT MAX(date),MIN(date) FROM ".$tablePermanences) or die(mysqli_error()); // Requête SQL
	$donnees = mysqli_fetch_array($reponse);
	$derniere_date_bdd = $donnees[0]; 
	$premiere_date_bdd = $donnees[1]; 	
	$today =date("Y-m-d",time());

	mysqli_close();
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
	<h2>Ajouter des dates de distribution pour le contrat <?php echo $amap; ?> </h2>
	<?php if( isset($_GET['erreur'])) { ?>
		<p style="color:orange" > ATTENTION, la précédente demande d'ajout ou de suppression de date a échouée ... 	</p>	
	<?php } ?>
		
		<p> Première permanence existante dans la table : <?php echo date("d-m-Y", strtotime($premiere_date_bdd)); ?><br />	
			Dernière permanence existante dans la table : <?php echo date("d-m-Y", strtotime($derniere_date_bdd)); ?></p>	
	<!-- changement du champs de la table corrsepondant au type de l'AMAP -->
	<table>
		<tr><th> AJOUT DE DATES</th><th>SUPPRESSION DE DATES</th></tr>
		<tr><td>
			<form method="post" action="ajoute_dates_permanences.php">
				<p> Première date à ajouter : <input type="date" name="first" value="<?php echo $derniere_date_bdd;?>"/></p>
				<p> Dernière date à ajouter : <input type="date" name="last" value="<?php echo $derniere_date_bdd;?>"/></p>
				<p> Périodicité :<input type="number" name="periode" value="7"/>
				<input type="hidden" name="amap" value="<?php echo $amap; ?>"/></p>
				<p align="center"> <input type="submit"/> </p>	
			</form></td>
			<td>
				<form method="post" action="supprime_dates_permanences.php">
					<p><br /></p>
					<p>Supprimer toutes les dates égales ou antérieures au : <input type="date" name="first" value="<?php echo $today;?>"/></p>
					<input type="hidden" name="amap" value="<?php echo $amap; ?>"/></p>
					<p><br /></p>
					<p align="center"> <input type="submit"/> </p>	
				</form>
			</td></tr></table>
	<ul>
		<li><a href="webmaster.php?mode=<?php echo $amap; ?>">Retour au menu webmaster</a>
		<li><a href="../index.php">Retour à l'Accueil</a>	
	</ul>
	</body>
</html>


