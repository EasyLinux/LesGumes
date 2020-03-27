<?php 
include_once("webmaster/define.php");
$ok=-1;
if(isset($_POST['e_mail'])) {
	$ok=0;
	mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysqli_select_db(base_de_donnees); // Sélection de la base 
	$question="SELECT * FROM amap_generale WHERE e_mail='".$_POST['e_mail']."'";
	$reponse = mysqli_query($question) or die(mysqli_error());
	$ligne = mysqli_num_rows($reponse);
	if($ligne==1) {
		$ok=1;
		$donnees=mysqli_fetch_array($reponse);
		mail($_POST['e_mail'], "Vos_identifiants_AMAP", "Votre login est : ".$donnees['Login']);
		mysqli_close();
		header("Location: index.php");
	}
	else {mysqli_close();}
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
			<?php include_once("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include_once("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php include_once("includes/menu_gauche.php"); 
			if($ok==-1) {?>
				<h3 class="mot_passe_recette">Votre identifiant (login) vous sera envoyé par mail </h3>
				<form class="mot_passe_recette" method="post" action="mot_passe_oublie.php">
					<p class="mot_passe_recette">
						<label for="e_mail">Votre e_mail d'enregistrement :</label>
						<input type="text" name="e_mail" id="e_mail" size="50" maxlength="45" tabindex="10"/>
						<input type="submit" tabindex="20"/>
						<input type="button" value="Annuler" onclick="document.location.href='index.php'" tabindex="50"/>
					</p>
				</form>
			<?php } 
			if($ok==0) {?>
				<h3 class="mot_passe_recette">Adresse mail non enregistrée dans la liste générale. Ré-essayez !!</h3>
				<form class="mot_passe_recette" method="post" action="mot_passe_oublie.php">
					<p class="mot_passe_recette">
						<label for="e_mail">Votre e_mail d'enregistrement :</label>
						<input type="password" name="e_mail" id="e_mail" size="50" maxlength="45" tabindex="10"/>
						<input type="submit" tabindex="20"/>
						<input type="button" value="Annuler" onclick="document.location.href='index.php'" tabindex="50"/>
					</p>
				</form>
			<?php } ?>
		</div>		
		<div id="pied_page">
			<!--<?php include_once("includes/pied_page.php") ?>-->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</p>
	</body>
</html>
