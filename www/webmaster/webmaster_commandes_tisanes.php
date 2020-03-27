<?php include_once("define.php"); 
define ( "UNITAIRE", "1");
define ( "PETIT", "2");
define ( "GRAND", "3");
define ( "SIROP", "4");

function insererCommande($id, $nom, $date, $type){
	$question = "INSERT INTO amap_tisanes_cde (Id_Personne, Nom, Date, Type_Produit, Date_modif) values($id, '$nom', '$date', $type, CURRENT_DATE)";
	$insert = mysql_query( $question) or die(mysql_error());
}

mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysql_select_db(base_de_donnees); // Sélection de la base

if( isset($_GET['date']) && isset($_GET['trimestre'])){
	// il faut générer les commandes pour la date passée en paramètre
	$date = $_GET['date'];
	$trimestre= $_GET['trimestre'];
	$reponseCommandes = mysql_query("SELECT DISTINCT Nom FROM amap_tisanes_cde WHERE date='".$date."'") or die(mysql_error());
	$nbAmapiens = mysql_num_rows($reponseCommandes);
	if ( $nbAmapiens == 0) {
		// on ajoute autant de ligne dans amap_tisane_cde que de produits commandés pour cette date dans amap_tisanes
		$question ="SELECT id, Nom, ".$trimestre."unitaire, ".$trimestre."petit, ".$trimestre."grand, ".$trimestre."sirop from amap_tisanes";
		$contrats = mysql_query($question) or die(mysql_error());
		while ( $contrat= mysql_fetch_array( $contrats)) {
			for ( $i= 1; $i <= $contrat[$trimestre."unitaire"]; $i++) {
				insererCommande( $contrat[id], $contrat[Nom], $date, UNITAIRE );
			}
			for ( $i= 1; $i <= $contrat[$trimestre."petit"]; $i++) {
				insererCommande( $contrat[id], $contrat[Nom], $date, PETIT );
			}
			for ( $i= 1; $i <= $contrat[$trimestre."grand"]; $i++) {
				insererCommande( $contrat[id], $contrat[Nom], $date, GRAND );
			}
			for ( $i= 1; $i <= $contrat[$trimestre."sirop"]; $i++) {
				insererCommande( $contrat[id], $contrat[Nom], $date, SIROP );
			}
		}
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="styleW.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>
	<body>
	
	<?php 
		$question="SELECT Date FROM amap_tisanes_permanences ORDER BY Date";
		$reponseDates = mysql_query($question) or die(mysql_error()); 
	?>
	<table>
		<caption> Distributions et commandes de tisanes </caption>
		<tr>
			<th width = "10%" >Date</th>
			<th width = "90%"> Etat</th>
		</tr>
		
		<?php
			$cpt= 0;
			while ( $dates = mysql_fetch_array($reponseDates)) { 
				// recupérer les commandes déjà insérer : 
				//	si il en existe pour la date courante ajouter les noms des amapiens ayant des produits à cette commande
				// sinon ajouter le bouton pour générer les commande de cette date
				$cpt ++;
				$dateCourante =  $dates[0];
				$questionSelectDate="SELECT DISTINCT Nom FROM amap_tisanes_cde WHERE date='$dateCourante' ORDER BY Nom";
				$reponseCommandes = mysql_query($questionSelectDate) or die(mysql_error()); 
				$nbAmapiens = mysql_num_rows($reponseCommandes);			
			?>
				<tr>
					<td> <?php echo $dateCourante;?> 
					<td> 
						<?php if ( $nbAmapiens == 0) { ?>
							<a href="webmaster_commandes_tisanes.php?date=<?php echo $dateCourante;?>&amp;trimestre=t<?php echo $cpt;?>_"> Générer les commandes pour le <?php echo $dateCourante;?> </a>
						<?php } else { 
							while ($commandes = mysql_fetch_array($reponseCommandes)) {
								echo $commandes[0].'-';
							}
						} ?>
					</td>
				</tr>
		<?php
			}
		?>
		</table>
		
	<?php 
		mysql_close(); // Déconnexion de MySQL	
	?>
	</body>
</html>
