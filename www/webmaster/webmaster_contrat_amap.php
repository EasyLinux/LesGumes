<?php
include_once("define.php"); 
include_once("../espace_producteurs/mes_fonctions.php");

$tri = isset( $_GET['classement']) ? $_GET['classement'] : "id";
$sens = $_GET['sens']=="DESC" ? "DESC" : "ASC";

$retourAMAP = $_GET['amap'];
if ( $retourAMAP == "amap_legumes_liste_attente")
	$retourAMAP = "amap_legumes";

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
		<?php  AfficherTable(base_de_donnees, $_GET['amap'],$tri,$sens,'webmaster_contrat_amap.php'); ?>
		
		<?php if ( $_GET['amap']=="amap_legumes_liste_attente") { ?>
			<p>		
			<table>
			<tr><td>
			<form action="inserer_new_liste_attente.php" method="post">
			<table>
				<caption> Ajouter un nouvel amapien :</caption>
				<tr><th><label for="nom">Nom (obligatoire)</label></th>
					<td><input type="texte" name="nom"/></td>
				</tr><tr><th><label for="prenom">Prénom</label></th>
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
				</tr><tr><th><label for="comment">Commentaire</label></th>
					<td><input type="texte" name="comment"/></td>
				</tr>
				<tr><th colspan="2"><input type="submit" value="Inscrire cette personne sur le site et l'ajouter à la liste d'attente"/></td>
				</tr></table>
			</form> </td>
			<td>
			<form action="inserer_liste_attente.php" method="post">
			<table>
				<caption> Insérer un amapien existant :</caption>
				<tr><th><label for="id">Id de l'amapien</label></th>
				<td><input type="texte" name="id"/></td>
				</tr><tr><th><label for="comment">Commentaire</label></th>
				<td><input type="texte" name="comment"/></td>
				</tr>
				<tr><th colspan="2"><input type="submit" value="Ajouter cet amapien à la liste d'attente"/></td>
				</table>
			</form></td></tr>
			<tr><td>
			<form action="supprimer_liste_attente.php" method="post">
			<table>
				<caption> Supprimer cette ligne :</caption>
				<tr><th><label for="id">Id de la ligne</label></th>
				<td><input type="texte" name="id"/></td></tr>
 				<tr><th>Supprimer cet id </th>
				<td><input type="radio" name="complete" value="false" checked> uniquement de la liste d'attente<br>
				<input type="radio" name="complete" value="true"> de la liste d'attente et de la base de donnée</td>
 				</tr>
				<tr><th colspan="2"><input type="submit" value="Supprimer"/></td>
				</table>
			</form>
			</td></tr>
			
			</table>
		<?php } ?>

		</div>
	</body>
</html>
