<?php include("webmaster/define.php"); 
$ok=0; //inconnu
if (isset($_COOKIE['identification_amap'])) // Si la variable existe
{	
    $id=$_COOKIE['identification_amap'];
    mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db(base_de_donnees); // Sélection de la base 

	$question="SELECT Nom, Prenom FROM amap_generale WHERE id='".$id."'";
	$reponse = mysql_query($question) ;
	$donnees=mysql_fetch_array($reponse);
	if ( $donnees   ) {
		$nom=$donnees['Nom'];
		$prenom=$donnees['Prenom'];
		$ok=1; //connu et identifié
	}
	mysql_close(); // Déconnexion de MySQL  	 
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
			<?php include("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php include("includes/menu_gauche.php"); ?>
		<iframe src="https://docs.google.com/forms/d/1UVC9jklB2_ywFl8dpC2bsYWuWiQSlyiJf48xOuyrWI8/viewform?embedded=true&entry_0=TEST&entry_1=Barack&entry_2=Barack" 
		width="900" height="1200" frameborder="0" marginheight="0" marginwidth="0">Chargement en cours...</iframe>	
		</div>
		<div id="pied_page">
			<!-- <?php include("includes/pied_page.php") ?> -->
		</div>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</body>
</html>
<?php
}
else  { ?>

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
			<?php 
				include("includes/menu_gauche.php"); 
			?><h3 class="mot_passe_recette">Il faut vous identifier pour accéder à ce service !!</h3>
		</div>		
		<div id="pied_page">
			<!-- <?php include("includes/pied_page.php") ?> -->
		</div>

	</body>
</html> 
 <?php } ?>
