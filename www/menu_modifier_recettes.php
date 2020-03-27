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
			<?php include("includes/menu_gauche.php");
			mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
			mysql_select_db(base_de_donnees); // S�lection de la base 
			$reponse = mysql_query("SELECT Nom_recette FROM recettes WHERE Rubrique='d�sserts'") or die(mysql_error()); // Requ�te SQL
			if(mysql_num_rows($reponse)!=0) {;
			?>
				<table class="h3">
					<tr><th>desserts</th></tr>
					<?php while ($donnees = mysql_fetch_array($reponse) ) { ?>
					<tr><td><a class="tab_perm_leg" href="modifier_table_recettes.php?nom_recette=<?php echo $donnees['Nom_recette']?>&amp;action=modifier"><?php echo $donnees['Nom_recette'];?></a></td></tr><?php } ?>
				</table>
			<?php }
			$reponse = mysql_query("SELECT Nom_recette FROM recettes WHERE Rubrique='l�gumes'") or die(mysql_error()); // Requ�te SQL
			if(mysql_num_rows($reponse)!=0) {;
			?>
				<table class="h3" >
					<tr><th>l�gumes</th></tr>
					<?php while ($donnees = mysql_fetch_array($reponse) ) { ?>
					<tr><td><a class="tab_perm_leg" href="modifier_table_recettes.php?nom_recette=<?php echo $donnees['Nom_recette']?>&amp;action=modifier"><?php echo $donnees['Nom_recette'];?></a></td></tr><?php } ?>
				</table>
			<?php }
			$reponse = mysql_query("SELECT Nom_recette FROM recettes WHERE Rubrique='soupes & potages'") or die(mysql_error()); // Requ�te SQL
			if(mysql_num_rows($reponse)!=0) {;
			?>
				<table class="h3" >
					<tr><th>soupes & potages</th></tr>
					<?php while ($donnees = mysql_fetch_array($reponse) ) { ?>
					<tr><td><a class="tab_perm_leg" href="modifier_table_recettes.php?nom_recette=<?php echo $donnees['Nom_recette']?>&amp;action=modifier"><?php echo $donnees['Nom_recette'];?></a></td></tr><?php } ?>
				</table>
			<?php }
			$reponse = mysql_query("SELECT Nom_recette FROM recettes WHERE Rubrique='viande'") or die(mysql_error()); // Requ�te SQL
			if(mysql_num_rows($reponse)!=0) {;
			?>
				<table class="h3" >
					<tr><th>viande</th></tr>
					<?php while ($donnees = mysql_fetch_array($reponse) ) { ?>
					<tr><td><a class="tab_perm_leg" href="modifier_table_recettes.php?nom_recette=<?php echo $donnees['Nom_recette']?>&amp;action=modifier"><?php echo $donnees['Nom_recette'];?></a></td></tr><?php } ?>
				</table>
			<?php }
			$reponse = mysql_query("SELECT Nom_recette FROM recettes WHERE Rubrique='poissons'") or die(mysql_error()); // Requ�te SQL
			if(mysql_num_rows($reponse)!=0) {;
			?>
				<table class="h3" >
					<tr><th>poissons</th></tr>
					<?php while ($donnees = mysql_fetch_array($reponse) ) { ?>
					<tr><td><a class="tab_perm_leg" href="modifier_table_recettes.php?nom_recette=<?php echo $donnees['Nom_recette']?>&amp;action=modifier"><?php echo $donnees['Nom_recette'];?></a></td></tr><?php } ?>
				</table>
			<?php }
			$reponse = mysql_query("SELECT Nom_recette FROM recettes WHERE Rubrique='autres'") or die(mysql_error()); // Requ�te SQL
			if(mysql_num_rows($reponse)!=0) {;
			?>
				<table class="h3" >
					<tr><th>autres</th></tr>
					<?php while ($donnees = mysql_fetch_array($reponse) ) { ?>
					<tr><td><a class="tab_perm_leg" href="modifier_table_recettes.php?nom_recette=<?php echo $donnees['Nom_recette']?>&amp;action=modifier"><?php echo $donnees['Nom_recette'];?></a></td></tr><?php } ?>
				</table>
			<?php } ?>
			
			
<!--
					<ul style="list-style: none; margin-left:170px; padding:0">
					<?php
						mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
						mysql_select_db(base_de_donnees); // S�lection de la base 
						$reponse = mysql_query("SELECT Nom_recette FROM recettes ORDER BY Nom_recette") or die(mysql_error()); // Requ�te SQL
						while ($donnees = mysql_fetch_array($reponse) )	{
						?>
						<li>
							<a style="color:white" href="modifier_table_recettes.php?nom_recette=<?php echo $donnees['Nom_recette']?>&amp;action=modifier"><?php echo $donnees['Nom_recette'];?></a>
						</li>
						<?php
						}
						mysql_close(); // D�connexion de MySQL
						?>

					</ul>
-->
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
