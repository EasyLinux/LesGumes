<?php
include_once("webmaster/define.php");
$ok=-1;

if (isset($_COOKIE['identification_amap'])) // Si la variable existe
{
	if (isset($_GET['id'])) // Si la variable existe
	{
		$ok=1;
		mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
		mysql_select_db(base_de_donnees) or die ('Impossible de sélectionner la base de données : ' . mysql_error()); // Sélection de la base 
		if($_GET['reponse']=='oui') $question = "UPDATE enquete SET Ca_marche='OK' WHERE id='".$_GET['id']."'";
		else $question = "UPDATE enquete SET Ca_marche='Pas bon!' WHERE id='".$_GET['id']."'";
		$reponse = mysql_query($question) or die('Impossible de sélectionner la base de données : ' .mysql_error());
		mysql_close();
	}
	else
	{
		$ok=1;
		$id=$_COOKIE['identification_amap'];
	}		
}	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name='keywords' content='AMAP,Saint-Sébastien,Saint Seb,LesGUMES,Les GUMES,les rangs oignons, la grange aux loups, Rublé' />
    <!-- pour le référencement google -->
    <meta name="google-site-verification" content="qYm35CL7C2njIbVne6NwnGffD7bx8f8JKvmZ94og4l8" />		
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
			?>
			<div>
			  	<div id="menu_news">
  					<h3>Dernières nouvelles</h3>
  					<?php
  					mysql_connect(hote, login, mot_passe_sql)  or die ('Impossible de sélectionner la base de données : ' . mysql_error());;
  					mysql_select_db(base_de_donnees);
  					// On récupère les 5 dernières news
  					$reponse = mysql_query('SELECT * FROM news ORDER BY id DESC LIMIT 0, 5');
  					?>
  					<!--	<h1>Bienvenue sur mon site !</h1>
  					<p>Voici les dernières news :</p>-->
  					<?php while ($donnees = mysql_fetch_array($reponse))
  					{
  					?>
  					<div class="news">
  						<h3><?php echo $donnees['titre']; ?></h3>
  						<p style="font-size: 12px;text-align:center;padding:0px"><em><strong>posté le <?php echo date("d/m/Y à H\hi", strtotime($donnees['date'])); ?></strong></em></p>
  						<p>
  						<?php
  						// On enlève les éventuels antislash PUIS on crée les entrées en HTML (<br />)
  						$contenu = nl2br(stripslashes($donnees['contenu']));
  						echo $contenu;
  						?>
  						</p>
  					</div>
  					<?php
  					} // Fin de la boucle des news ?>
			 	</div>
<!--				
				<div style="padding-top:10px" >
					<h3 style="color:yellow; text-align:center;">AMAP produits laitiers.<br />
					Après la livraison découverte du 10 décembre<br /><br />
					<span style="color: white;">nous vous demandons d'indiquer <a href="doodle.php">ici</a>
					<br />quelle valeur de contrat vous comptez souscrire.
					</span>
					<br /><br />Ce recensement est indispensable à la mise en route de cette AMAP!!<br />
					</h3>
					<h3 style="text-align:center; color:yellow;">
						<img src="images/prod_lait.jpg" alt="" />
						<span style="position:relative; bottom:50px;" >Début prévu mi janvier!</span><img src="images/prod_lait.jpg" alt="" />
					</h3>
					<h3 style="text-align:center; color:yellow;">
						<img src="images/ferme_ruble.jpg" alt="" />
					</h3>
				</div>
-->				
			</div>
		</div>  
		<div id="pied_page">
			<!--<?php include_once("includes/pied_page.php") ?>-->
		</div>  
	</body>
</html>


			
		<!--	
			<?php
			if($ok==-1) {?>
				<h3 class="mot_passe_recette">Site en phase de test : identifiez-vous et modifiez vos identifiants par défaut !
				<br />Vérifiez ensuite que tout fonctionne bien et enregistrez vos observations dans le tableau !!</h3>
				<?php }
			if($ok==1) { ?>
				<h3 class="mot_passe_recette">Site en phase de test : Dites si tout fonctionne bien en cliquant sur le bon bouton !!!!</h3>
				<form class="mot_passe_recette" method="post" action="index.php" >
					<p class="mot_passe_recette">
						<input type="button" value="Tout marche chez moi" onclick="window.location.href='index.php?id=<?php echo $id; ?>&amp;reponse=oui'" />
						<input type="button" value="Il y a des problèmes chez moi" onclick="window.location.href='index.php?id=<?php echo $id; ?>&amp;reponse=non'" />
					</p>
				</form>
				<?php
			}
		/*	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
			mysql_select_db(base_de_donnees); // Sélection de la base 
			$question="SELECT * FROM enquete WHERE CA_marche='OK'";
			$reponse = mysql_query($question);
			$nbre_ok=mysql_num_rows($reponse);
			$question="SELECT * FROM enquete ORDER BY Nom";
			$reponse = mysql_query($question) or die(mysql_error());
			$nbre_total=mysql_num_rows($reponse);
			$donnees=mysql_fetch_array($reponse);
			*/
			?>
			<table class="h3">
				<caption class="h3"><?php echo $nbre_ok." OK sur ".$nbre_total." personnes"; ?></caption>
				<?php while($donnees) { ?>
				<tr> 
					<?php for($i=1; $i<6 && $donnees; $i++) { ?>
						<th style="font-size:small"><?php echo $donnees['Nom']; ?></th> <?php
						if($donnees['Ca_marche']=='OK') { ?><td class="h3" style="color:blue; font-weight:bold"><?php echo $donnees['Ca_marche']; ?></td><?php } 
						elseif ($donnees['Ca_marche']=='Pas bon!') { ?><td class="h3" style="color:red; font-weight:bold; text-decoration:blink"><?php echo $donnees['Ca_marche']; ?></td><?php } 
						else { ?><td class="h3"><?php echo $donnees['Ca_marche']; ?></td><?php }
						$donnees=mysql_fetch_array($reponse); ?>
					<?php } ?>
				</tr>
				<?php } ?>
			</table>
		
			<?php
			mysql_close();?>
		</div>	
		-->
