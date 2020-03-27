<?php
include_once("define.php"); 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="../espace_producteurs/style_producteurs.css" />
	</head>
	
	<body>
		<div id="page_principale">
		<p> <strong>&nbsp;&nbsp;Navigation &nbsp;&nbsp;&nbsp;:&nbsp;</strong>
			<input type="button" value="Retour à l'accueil" onclick="document.location.href='../index.php'"/>
			<input type="button" value="Menu Webmaster" onclick="document.location.href='webmaster.php?mode=<?php echo $retourAMAP; ?>'"/>
		</p>
			<p>		
			<form action="inserer_amapien.php" method="post">
			<table>
				<caption> Ajouter un nouvel amapien :</caption>
				<tr><th><label for="nom">Nom (obligatoire)</label></th>
					<td><input type="texte" name="nom"/></td>
				</tr><tr><th><label for="prenom">Prénom (obligatoire)</label></th>
					<td><input type="texte" name="prenom"/></td>
				</tr><tr><th><label for="mail">Mail (obligatoire)</label></th>
					<td><input type="texte" name="mail"/></td>
				</tr><tr><th><label for="telephone">Téléphone</label></th>
					<td><input type="texte" name="telephone"/></td>
				</tr><tr><th><label for="portable">Portable</label></th>
					<td><input type="texte" name="portable"/></td>
				</tr><tr><th><label for="adresse">Adresse</label></th>
					<td><input type="texte" name="adresse"/></td>
				</tr><tr><th><label for="adresse">Code Postal</label></th>
					<td><input type="texte" name="codePostal"/></td>
				</tr><tr><th><label for="adresse">Ville</label></th>
					<td><input type="texte" name="ville"/></td>				
				</tr>
				<tr><th colspan="2"><input type="submit" value="Inscrire cette personne sur le site"/></td>
				</tr></table>
			</form> 

		</div>
	</body>
</html>
