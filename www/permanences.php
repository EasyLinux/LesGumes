<?php include_once("webmaster/define.php"); 
$ok=-1;
if (isset($_COOKIE['identification_amap'])) // Si la variable existe
{	
	//pour imposer que la personne soit inscrite aux légumes rétablir les lignes ci-dessous et enlever ok=1
	$ok=0; //identifié mais pas inscrit aux légumes
	//mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	//mysql_select_db(base_de_donnees); // Sélection de la base 
	//$question="SELECT * FROM ".$_GET['amap']." WHERE id='".$_COOKIE['identification_amap']."'";
	//$reponse = mysql_query($question) or die(mysql_error());
	//$ligne = mysql_num_rows($reponse);
	//if($ligne>0) $ok=1; //identifié et inscrit aux légumes
	//mysql_close();
	$ok=1;
}
if($ok==1) {?>

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
			<?php include_once("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include_once("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php include_once("includes/menu_gauche.php") ?>
						
			<h3 style="color:yellow; text-align:center; text-decoration:underline">En cas de désinscription de dernière minute, n'oubliez pas d'<em><a style="color:#00FFFF; cursor:pointer" href="mailto:amap-lesgumes@googlegroups.com?subject=inscription permanences <?php echo $_GET['amap'] ?>">envoyer un mail sur la liste de diffusion !</a></em></h3>

			<?php if ($_GET['amap']=='amap_cerises') { ?>
						<h3 style="color:orange; text-align:center; text-decoration:underline"> Attention : les cueillettes des cerises ont lieu les jeudis soirs qui précédent la distribution.</h3>
			<?php }?>
			<?php if ($_GET['amap']=='amap_agrumes') { ?>
						<h3 style="color:orange; text-align:center; text-decoration:underline"> Attention : la date exacte de réception des cagettes ne sera connue qu'au dernier moment<br />
            Les cagettes sont à prendre aux Sorinières le jeudi soir pour les re-distribuer le lendemain</h3>
			<?php }
			if ($_GET['amap']=='amap_chevre') { ?>
						<h3 style="color:orange; text-align:center; text-decoration:underline"> Attention, la permanence est répartie sur 2 temps en fonction de la colonne choisie</h3>
			<?php } ?>


			<table class="h3">
					<caption class="h3">Tableau des permanences <?php echo $_GET['amap'] ?><br />Cliquer sur votre nom pour vous désinscrire!<br />Cliquer sur '?' pour vous inscrire !<br /></caption>
			
				<tr>
					<th>Date</th>
					<?php
					mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
					mysql_select_db(base_de_donnees); // Sélection de la base 
					$reponse = mysql_query("SELECT * FROM ".$_GET['amap']."_permanences ORDER BY Date") or die(mysql_error()); // Requête SQL
					$colonne = (mysql_num_fields($reponse)-2)/2;
					for($i=1;$i<=$colonne;$i++) { ?>
						<th>Personne_<?php echo $i; ?></th>
					<?php } ?>
					<th>Date</th>
				</tr>
				<?php if ($_GET['amap']=='amap_chevre') { ?>
						<tr><th></th><th>18h30-19h15</th><th>19h15-20h</th><th></th></tr>
				<?php } 
				$index=0;
				while ($donnees = mysql_fetch_array($reponse) ) { $index++;
				?>
				<tr class="h3" >
					<td class="h3_date" "><?php echo date("d-M-y",strtotime($donnees['Date'])); ?></td>
				<?php 
					if($donnees['Distribution']==1) for($i=1;$i<=$colonne;$i++) { 
				?>
					<td class="h3"><a class="tab_perm_leg" href="modifier_permanences.php?date=<?php echo $donnees['Date'];?>&amp;numero=<?php echo $i; ?>&amp;personne=<?php echo $donnees['Personne_'.$i];?>&amp;amap=<?php echo $_GET['amap']; ?>"><?php echo $donnees['Personne_'.$i]; ?></a></td>
				<?php } else { 
					if($_GET['amap']=='amap_legumes') { ?><td class="h3_dist" colspan="3"><?php echo 'pas de distribution'; ?></td> <?php } 
					else { ?> <td class="h3_dist" colspan="2"><?php echo 'pas de distribution'; ?></td> <?php }
				} ?>
					<td class="h3_date"><?php echo date("d-M-y",strtotime($donnees['Date'])); ?></td>
				</tr>
				<?php }
				mysql_close(); // Déconnexion de MySQL
			?>
			</table>
		</div>		
		<div id="pied_page">
			<!-- <?php include_once("includes/pied_page.php") ?> -->
		</div>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</body>
</html>
<?php
}
if($ok==-1 || $ok==0) { 
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
			<?php include_once("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include_once("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php 
				include_once("includes/menu_gauche.php"); 
			if($ok==-1) { ?><h3 class="mot_passe_recette">Il faut vous identifier pour accéder à ce service !!</h3><?php }
			if($ok==0) { ?><h3 class="mot_passe_recette">Il faut faire partie de l'<?php echo $_GET['amap'] ?> pour accéder à ce service !!</h3><?php } ?>
		</div>		
		<div id="pied_page">
			<!-- <?php include_once("includes/pied_page.php") ?> -->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</p>
	</body>
</html> 
 <?php } ?>
