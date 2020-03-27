<?php 
if (isset($_POST['motpasse_1']))
	$motpasse_1 = $_POST['motpasse_1'];
else
	$motpasse_1 = "";

$motpasse_1 = sha1($motpasse_1); // Encodage mot de passe

if (isset($_POST['motpasse_2']))
	$motpasse_2 = $_POST['motpasse_2'];
else
	$motpasse_2 = "";

$motpasse_2 = sha1($motpasse_2); // Encodage mot de passe
?>
<?php 
include("webmaster/define.php");
$ok=-1;
if(isset($_GET['reconnu'])) {
	setcookie('identification_amap','',time()-86400);
	header("Location: index.php");
}
if(isset($_POST['motpasse_1'])) {
	$ok=0;
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db(base_de_donnees); // Sélection de la base 
	$question="SELECT * FROM amap_generale WHERE Mot_passe='".$motpasse_1."' AND Login='".$_POST['login_1']."'";
	$reponse = mysql_query($question) or die(mysql_error());
	$ligne = mysql_num_rows($reponse);
	if($ligne==1) {
		$ok=1; //caracteres interdits
		if(preg_match('#^[a-zA-Z0-9.@_-]{4,40}$#',$_POST['motpasse_2']) && preg_match('#^[a-zA-Z0-9.@_-]{4,40}$#',$_POST['login_2'])) {
			$ok=2; // mot correct mais non identique
			if($_POST['motpasse_2']==$_POST['motpasse_3']) {
				$ok=3; //déjà utilisé
				mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
				mysql_select_db(base_de_donnees); // Sélection de la base 
				$question="SELECT * FROM amap_generale WHERE Mot_passe='".$motpasse_2."' AND Login='".$_POST['login_2']."'";
				$reponse = mysql_query($question) or die(mysql_error());
				$ligne = mysql_num_rows($reponse);
				if($ligne==0) {
					$question="UPDATE amap_generale SET Mot_passe='".$motpasse_2."', Login='".$_POST['login_2']."' WHERE Mot_passe='".$motpasse_1."' AND Login='".$_POST['login_1']."'";
					$reponse = mysql_query($question) or die(mysql_error());
					mysql_close();
					header("Location: index.php");
				}
			}
		}
	}
	mysql_close();
}
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
	<body>
		<div id="en_tete">
			<?php include("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php include("includes/menu_gauche.php"); ?>
			<h3 class="mot_passe_recette">Ne plus être reconnu <a href="mot_passe_change_2.php?reconnu=0">cliquer ici !!</a></h3>
			<h3 class="mot_passe_recette">Changer login et/ou mot de passe !!</h3><?php
			if($ok==-1 || $ok==0 || $ok==1 || $ok==2 || $ok==3) {
				if($ok==0) {?><h3 class="mot_passe_recette">Ancien(s) identifiant(s) incorrect(s). Ré-essayez !!</h3><?php }
				if($ok==2) {?><h3 class="mot_passe_recette">La confirmation du nouveau mot de passe est incorrecte. Ré-essayez !!</h3><?php }
				if($ok==1) {?><h3 class="mot_passe_recette">Mot de passe trop court (4 min), ou caractères interdits : que des lettres non accentuées, des chiffres ou @._- .<br />Recommencez !!</h3><?php } 
				if($ok==3) {?><h3 class="mot_passe_recette">Identifiants déjà utilisés. Recommencez !!</h3><?php } ?>
				<form class="mot_passe_recette" method="post" action="mot_passe_change.php" >
					<p class="mot_passe_recette">
						<label for="login_1">Votre ancien login :</label><br />
						<input type="text" name="login_1" id="login_1" size="50" maxlength="45" tabindex="10"/>
						<br />
						<label for="motpasse_1">Votre ancien mot de passe :</label><br />
						<input type="password" name="motpasse_1" id="motpasse_1" size="50" maxlength="45" tabindex="10"/>
						<br /><br /><br />
						<label for="login_2">Votre nouveau login :</label><br />
						<input type="text" name="login_2" id="login_2" size="50" maxlength="45" tabindex="10"/>
						<br />
						<label for="motpasse_2">Votre nouveau mot de passe :</label><br />
						<input type="password" name="motpasse_2" id="motpasse_2" size="50" maxlength="45" tabindex="10"/>
						<br />
						<label for="motpasse_3">Confirmez votre nouveau mot de passe :</label><br />
						<input type="password" name="motpasse_3" id="motpasse_3" size="50" maxlength="45" tabindex="10"/>
						<br /><br />
						<input type="submit" tabindex="20"/>
						<input type="button" value="Annuler" onclick="document.location.href='index.php'" tabindex="50"/>
					</p>
				</form>				
			<?php } ?>
		</div>		
		<div id="pied_page">
			<!--<?php include("includes/pied_page.php") ?>-->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</p>
	</body>
</html>
