<?php 
include_once("webmaster/define.php");
$ok=-1;
if(isset($_POST['e_mail'])) {
	$ok=0;
	mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
	mysql_select_db(base_de_donnees); // S�lection de la base 
	$question="SELECT * FROM amap_generale WHERE e_mail='".$_POST['e_mail']."'";
	$reponse = mysql_query($question) or die(mysql_error());
	$ligne = mysql_num_rows($reponse);
	if($ligne==1) {
		$ok=1;
		$donnees=mysql_fetch_array($reponse);
		mail($_POST['e_mail'], "Vos_identifiants_AMAP", "Votre login est : ".$donnees['Login']);
		mysql_close();
		header("Location: index.php");
	}
	else {mysql_close();}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert � indiquer dans quelle langue est r�dig�e votre page -->
	<head>
		<title>AMAP Saint-S�bastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caract�res sp�cifiques au fran�ais ����... -->
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
				<h3 class="mot_passe_recette">Votre identifiant (login) vous sera envoy� par mail </h3>
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
				<h3 class="mot_passe_recette">Adresse mail non enregistr�e dans la liste g�n�rale. R�-essayez !!</h3>
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
		<!-- alt indique un texte alternatif au cas o� l'image ne peut pas �tre t�l�charg�e -->
	</p>
	</body>
</html>
