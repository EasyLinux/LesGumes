<?php include("webmaster/define.php"); ?>

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
			<?php include("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php 
				if($_GET['action']=="modifier") {
					include("includes/menu_gauche.php"); 
					mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
					mysql_select_db(base_de_donnees); // S�lection de la base 
					$texte = addslashes($_POST['texte_recette']);
					$nom = addslashes($_GET['nom_recette']);
					$question="UPDATE recettes SET Recette='".$texte."' WHERE Nom_recette='".$nom."'";
					mysql_query( $question);
					mysql_close();
			?>
					<h3 class="mot_passe_recette">La recette <span class="mot_passe_recette"><?php echo stripslashes($_GET['nom_recette']); ?></span> a bien �t� modifi�e!</h3>
			<?php } 
				if($_GET['action']=="ajouter") {
					mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
					mysql_select_db(base_de_donnees); // S�lection de la base 
					$texte = $_POST['texte_recette'];
					$nom_recette = $_POST['nom_recette'];
					$auteur = $_POST['nom_auteur'];
					$rubrique=$_POST['rubrique'];
					$date = date("Y-m-d");
					
					$question="SELECT * FROM amap_generale WHERE Nom='".$_GET['nom']."' AND Prenom='".$_GET['prenom']."'";
					$reponse = mysql_query($question) or die(mysql_error());
					$donnees = mysql_fetch_array($reponse);

					$question="INSERT INTO recettes VALUES('".$nom_recette."', '".$auteur."', '".$date."', '".$texte."', '".$rubrique."')";
					if (!mysql_query($question)) {
						if (mysql_errno()=='1062') {
							mysql_close();
							include("includes/menu_gauche.php"); 
					?>
							<h3 class="mot_passe_recette">Le nom <span class="mot_passe_recette"><?php echo $nom_recette; ?></span> est d�j� utilis�!
							<br />Ajouter un num�ro au nom! Exemple : <span class="mot_passe_recette"><?php echo $nom_recette; ?> (n�)</span>
							<br />ou<br />Changer de nom!
							</h3>
							<br /><br />
							<h4 class="mot_passe_recette"><a style="cursor:pointer" onclick="javascript:history.back();">Revenir pour modifier</a></h4>
					<?php
						}
						else {
							mysql_close();
							include("includes/menu_gauche.php"); 
					?>
							<h3 class="mot_passe_recette">Base de donn�es non accessible!</h3>												
					<?php
						} }
					else {
						mysql_close();
						include("includes/menu_gauche.php"); 
			?>
						<h3 class="mot_passe_recette">Votre recette <span class="mot_passe_recette"><?php echo stripslashes($_POST['nom_recette']); ?></span> a bien �t� enregistr�e!</h3>
			<?php 	} 
				} ?>
		</div>		
		<div id="pied_page">
			<!-- <?php include("includes/pied_page.php") ?> -->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas o� l'image ne peut pas �tre t�l�charg�e -->
	</p>
	</body>
</html>
