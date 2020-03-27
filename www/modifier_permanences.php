<?php
include("webmaster/define.php");
if (isset($_COOKIE['identification_amap'])) // Si la variable existe
{
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db(base_de_donnees); // Sélection de la base 
	$id=$_COOKIE['identification_amap'];
	//$question="SELECT * FROM ".$_GET['amap']." WHERE id='".$id."'";
	$question="SELECT * FROM amap_generale WHERE id='".$id."'";
	$reponse= mysql_query($question) or die(mysql_error());
	$donnees = mysql_fetch_array($reponse);
	$nom_prenom = $donnees['Prenom'].' '.$donnees['Nom'];
	if (!get_magic_quotes_gpc()) {
		$nom_prenom_sl = addslashes($nom_prenom);
	}
	else {
		$nom_prenom_sl = $nom_prenom;
	}
	if($_GET['personne']=="?") {
		$question="UPDATE ".$_GET['amap']."_permanences SET Personne_".$_GET['numero']."='".$nom_prenom_sl."' WHERE Date='".$_GET['date']."'";
		mysql_query($question);
		$question="UPDATE ".$_GET['amap']."_permanences SET id_".$_GET['numero']."='".$donnees['id']."' WHERE Date='".$_GET['date']."'";
		mysql_query($question);
		mysql_close();
		header("Location: permanences.php?amap=".$_GET['amap']);
	}
	else if($_GET['personne']==$nom_prenom) {
		$question="UPDATE ".$_GET['amap']."_permanences SET Personne_".$_GET['numero']."='?' WHERE Date='".$_GET['date']."'";
		mysql_query($question);
		$question="UPDATE ".$_GET['amap']."_permanences SET id_".$_GET['numero']."='0' WHERE Date='".$_GET['date']."'";
		mysql_query($question);
		mysql_close();
		header("Location: permanences.php?amap=".$_GET['amap']);
	}
	mysql_close();
}
//***********************************************************************
// On affiche la zone de texte pour rentrer de nouveau le mot de passe.
//***********************************************************************
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>
	<body onload="document.getElementById('motpasse').focus();">
		<div id="en_tete">
			<?php include("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php include("includes/menu_gauche.php"); ?>
			<h3 class="mot_passe_recette">Votre identification ne correspond pas au nom inscrit!</h3>
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
