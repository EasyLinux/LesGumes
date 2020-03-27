<?php include_once("webmaster/define.php"); ?>

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
			
			<h1 class="texte" style="text-align:center"> Accès réservés aux producteurs de l'AMAP</h1>
			<ul >
				<li class="h2"><a  href="espace_producteurs/espace_reserve.php?amap=amap_legumes&amp;ordre=passe">Rangs d'oignons</a></li>
				<li class="h2"><a class="h1" href="liste_produits_lp.php">Rublé</a></li>
				<li class="h2"><a class="h1" href="espace_producteurs/espace_reserve.php?classement=id&amp;amap=amap_champignons&amp;ordre=passe">Agari Breizh </a></li>
				<li class="h2"><a class="h1" href="espace_producteurs/espace_reserve.php?classement=id&amp;amap=amap_pommes&amp;ordre=passe">Le moulin des noues</a></li>
				<li class="h2"><a class="h1" href="espace_producteurs/espace_reserve.php?classement=id&amp;amap=amap_oeufs&amp;ordre=passe">EARL Bretteurs</a></li>
			</ul>

		</div>

	</body>
</html>
