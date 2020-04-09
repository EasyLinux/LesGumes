<?php include("webmaster/define.php"); ?>

<?php
function DateFr ($date_demandee) {
 $jours= Array ("dim", "lun", "mar", "mer", "jeu", "ven", "sam");
 $time_info= getdate ($date_demandee);
 $nom_jour= $jours [$time_info["wday"]];
 return $nom_jour . " " . strftime ("%d/%m/%Y", $date_demandee);
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
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>
	<body>
		<div id="en_tete">
			<?php include("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php 
				include("includes/menu_gauche.php");
				mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
				mysqli_select_db(base_de_donnees); // Sélection de la base 
				$nom = $_GET['nom_recette'];
				$question="SELECT * FROM recettes WHERE Nom_recette='".$nom."'";
				$reponse = mysqli_query($question) or die(mysqli_error()); // Requête SQL
				$donnees = mysqli_fetch_array($reponse);
			?>
			<h3 class="modifier_recette">Fiche recette</h3>
			<h4 class="lire_recette"><?php echo stripslashes($_GET['nom_recette']);?><br /><br />
				Auteur : <?php echo $donnees['Auteur'];?> <br /><br />
				Créée le : <?php echo date("d/m/Y",strtotime($donnees['Date_creation']));?> <br />
			</h4>
			<p class="lire_recette">
				<?php echo nl2br(stripslashes($donnees['Recette']));?>
			</p>
			<?php
			mysqli_close();
			?>
		</div>		
		<div id="pied_page">
			<!-- <?php include("includes/pied_page.php") ?> -->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</p>
	</body>
</html>
