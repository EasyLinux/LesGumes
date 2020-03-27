<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
	</head>
	<body>
	<a href="../index.php">Accueil</a>------<a href="javascript:history.back();">Page précédente</a>
	<form class="mot_passe_recette" method="post" action="commande_sql.php" >
	<p class="mot_passe_recette">
		<label for="commande">Commande : </label>
		<br />
		<input type="text" name="commande" id="commande" size="100" tabindex="10"/>
		<br />
		<br /><br />
		<input type="submit" tabindex="20"/>
		<input type="button" value="Annuler" onclick="javascript:history.back();" tabindex="50"/>
	</p>
	</form>
 	</body>
</html>
