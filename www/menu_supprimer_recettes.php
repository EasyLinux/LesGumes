<?php include("webmaster/define.php"); ?>

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
			<?php include("includes/menu_gauche.php");
			mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
			mysql_select_db(base_de_donnees); // Sélection de la base 
			$reponse = mysql_query("SELECT Nom_recette FROM recettes WHERE Rubrique='désserts'") or die(mysql_error()); // Requête SQL
			if(mysql_num_rows($reponse)!=0) {;
			?>
				<table class="h3" >
					<tr><th>desserts</th></tr>
					<?php while ($donnees = mysql_fetch_array($reponse) ) { ?>
					<tr><td><a class="tab_perm_leg" href="modifier_table_recettes.php?nom_recette=<?php echo $donnees['Nom_recette']?>&amp;action=supprimer"><?php echo $donnees['Nom_recette'];?></a></td></tr><?php } ?>
				</table>
			<?php }
			$reponse = mysql_query("SELECT Nom_recette FROM recettes WHERE Rubrique='légumes'") or die(mysql_error()); // Requête SQL
			if(mysql_num_rows($reponse)!=0) {;
			?>
				<table class="h3" >
					<tr><th>légumes</th></tr>
					<?php while ($donnees = mysql_fetch_array($reponse) ) { ?>
					<tr><td><a class="tab_perm_leg" href="modifier_table_recettes.php?nom_recette=<?php echo $donnees['Nom_recette']?>&amp;action=supprimer"><?php echo $donnees['Nom_recette'];?></a></td></tr><?php } ?>
				</table>
			<?php }
			$reponse = mysql_query("SELECT Nom_recette FROM recettes WHERE Rubrique='soupes & potages'") or die(mysql_error()); // Requête SQL
			if(mysql_num_rows($reponse)!=0) {;
			?>
				<table class="h3" >
					<tr><th>soupes & potages</th></tr>
					<?php while ($donnees = mysql_fetch_array($reponse) ) { ?>
					<tr><td><a class="tab_perm_leg" href="modifier_table_recettes.php?nom_recette=<?php echo $donnees['Nom_recette']?>&amp;action=supprimer"><?php echo $donnees['Nom_recette'];?></a></td></tr><?php } ?>
				</table>
			<?php }
			$reponse = mysql_query("SELECT Nom_recette FROM recettes WHERE Rubrique='viande'") or die(mysql_error()); // Requête SQL
			if(mysql_num_rows($reponse)!=0) {;
			?>
				<table class="h3" >
					<tr><th>viande</th></tr>
					<?php while ($donnees = mysql_fetch_array($reponse) ) { ?>
					<tr><td><a class="tab_perm_leg" href="modifier_table_recettes.php?nom_recette=<?php echo $donnees['Nom_recette']?>&amp;action=supprimer"><?php echo $donnees['Nom_recette'];?></a></td></tr><?php } ?>
				</table>
			<?php }
			$reponse = mysql_query("SELECT Nom_recette FROM recettes WHERE Rubrique='poissons'") or die(mysql_error()); // Requête SQL
			if(mysql_num_rows($reponse)!=0) {;
			?>
				<table class="h3" >
					<tr><th>poissons</th></tr>
					<?php while ($donnees = mysql_fetch_array($reponse) ) { ?>
					<tr><td><a class="tab_perm_leg" href="modifier_table_recettes.php?nom_recette=<?php echo $donnees['Nom_recette']?>&amp;action=supprimer"><?php echo $donnees['Nom_recette'];?></a></td></tr><?php } ?>
				</table>
			<?php }
			$reponse = mysql_query("SELECT Nom_recette FROM recettes WHERE Rubrique='autres'") or die(mysql_error()); // Requête SQL
			if(mysql_num_rows($reponse)!=0) {;
			?>
				<table class="h3" >
					<tr><th>autres</th></tr>
					<?php while ($donnees = mysql_fetch_array($reponse) ) { ?>
					<tr><td><a class="tab_perm_leg" href="modifier_table_recettes.php?nom_recette=<?php echo $donnees['Nom_recette']?>&amp;action=supprimer"><?php echo $donnees['Nom_recette'];?></a></td></tr><?php } ?>
				</table>
			<?php } ?>
		

		
<!--			
					<ul style="list-style: none; margin-left:170px; padding:0">
					<?php
						mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
						mysql_select_db(base_de_donnees); // Sélection de la base 
						$reponse = mysql_query("SELECT Nom_recette FROM recettes ORDER BY Nom_recette") or die(mysql_error()); // Requête SQL
						while ($donnees = mysql_fetch_array($reponse) )	{
					?>
						<li>
							<a style="color:white" href="modifier_table_recettes.php?nom_recette=<?php echo $donnees['Nom_recette']?>&amp;action=supprimer"><?php echo $donnees['Nom_recette'];?></a>
						</li>
					<?php
						}
						mysql_close(); // Déconnexion de MySQL
					?>
					</ul>
	-->		
			
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
