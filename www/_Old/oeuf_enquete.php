<?php
include("webmaster/define.php");
$ok=-1;
if (isset($_COOKIE['identification_amap'])) 
{
	$ok=1;
	$id=$_COOKIE['identification_amap'];
	mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysqli_select_db(base_de_donnees); // Sélection de la base 
	$question="SELECT * FROM enquete_oeufs WHERE id=$id";
	$reponse = mysqli_query($question);
	$donnees=mysqli_fetch_array($reponse);
	$nom=$donnees['Nom'];
	$prenom=$donnees['Prenom'];
	$ouinon=$donnees['Ouinon'];
	if($ouinon!=-1) {
		$frequence=$donnees['Frequence'];
		$duree=$donnees['Duree'];
		$nombre=$donnees['Nombre'];
		$remarque=$donnees['Remarques'];
	}
	else $frequence=$duree=$nombre=$remarque="";
	mysqli_close();
}		
?>

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
		
		
			<?php if ($ok==-1)	{ ?>
				<h3 class="mot_passe_recette">Il faut vous identifier pour accéder à ce service !!</h3>
			<?php }
			else { ?>
				<div style="color:red; text-align:center; background-color:yellow; font-weight: bold;font-size: large;">
				<p>
					Pour l'instant le producteur en est à la phase de recherche des débouchés <br />
					Le producteur est engagé dans une procédure de <span style="color:blue;">CONVERSION au BIO (ECOCERT ou ACLAVE)</span><br />
					L'AMAP pourrait débuter en MARS-AVRIL
				</p>
				</div>
				<div style="color:blue; text-align:left; background-color:#DDDDDD;">
				<table style="border-collapse:collapse;" width="100%">
				   <tr>
					   <td style="border: 1px solid black;text-align:right;color:blue; font-weight: bold;">Nom</td><td style="border: 1px solid black;text-align:center;">René PEAUDEAU</td>
					   <td style="border: 1px solid black;text-align:right;color:blue; font-weight: bold;">Adresse exploitation</td><td style="border: 1px solid black;text-align:center;">Legé 44650</td>
					   <td style="border: 1px solid black;text-align:right;color:blue; font-weight: bold;">Taille</td><td style="border: 1px solid black;text-align:center;">6 ha</td>
				   </tr>
				   <tr>
					   <td style="border: 1px solid black;text-align:right;color:blue; font-weight: bold;">Production</td><td style="border: 1px solid black;text-align:center;">oeufs BIO</td>
					   <td style="border: 1px solid black;text-align:right;color:blue; font-weight: bold;">Race des pondeuses</td><td style="border: 1px solid black;text-align:center;">non encore déterminée</td>
					   <td style="border: 1px solid black;text-align:right;color:blue; font-weight: bold;">Nombre de poules</td><td style="border: 1px solid black;text-align:center;">sans doute ...<br />(fonction du résultat de l'étude de marché)</td>
				   </tr>
				   <tr>
						<td style="border: 1px solid black;text-align:right;color:blue; font-weight: bold;">Prix</td><td style="border: 1px solid black;text-align:center;">sans doute 2 les 6 oeufs<br />calibre : moyen gros</td>
						<td style="border: 1px solid black;text-align:right;color:blue; font-weight: bold;">Fréquence des livraisons</td><td style="border: 1px solid black;text-align:center;">15 jours ou plus</td>
						<td style="border: 1px solid black;text-align:right;color:blue; font-weight: bold;">Durée du contrat</td><td style="border: 1px solid black;text-align:center;">Fonction des résultats de l'enquête</td>
				   </tr>
				</table>
				</div>
				<p style="color:#DD1111; font-weight: bold;font-size: large;">
					<?php echo($nom.' '.$prenom);?>
				</p>
				<form id="monFormulaire" method="post" action="traitement_oeufs.php">
				<p>
					<span style="color:yellow;font-weight: bold;font-size: large;">
					Etes-vous intéressé((e)(s)) ou non pour prendre régulièrement des oeufs en contrat AMAP?
					</span><br />
					
					<input 	type="radio" name="ouinon" value="oui" id="oui" <?php if($ouinon!=0) { ?> checked="checked" <?php } ?> >
					<label for="oui">oui</label><br />
					
					<input type="radio" name="ouinon" value="non" id="non" <?php if($ouinon==0) { ?> checked="checked" <?php } ?> />
					<label for="non">non</label><br /><br />
					
					<span style="color:yellow;font-weight: bold;font-size: large;">
					Si vous avez répondu oui, complétez la suite du questionnaire, sinon validez.
					</span><br />					
					
					<label for="frequence">Fréquence des livraisons</label>
				    <select name="frequence" id="frequence" <?php if($ouinon==0) { ?> disabled="true" <?php } ?> >
					   <option value="15jours" <?php if($frequence=="15jours") { ?> selected="selected" <?php } ?> >15 jours</option> 
					   <option value="3semaines" <?php if($frequence=="3semaines") { ?> selected="selected" <?php } ?> >3 semaines</option>
					   <option value="mois" <?php if($frequence=="mois") { ?> selected="selected" <?php } ?> >mois</option>
				    </select>
					
					<label for="duree">Durée du contrat</label>
				    <select name="duree" id="duree" <?php if($ouinon==0) { ?> disabled="true" <?php } ?> >
					   <option value="6mois" <?php if($duree=="6mois") { ?> selected="selected" <?php } ?> >6 mois</option>
					   <option value="1an" <?php if($duree=="1an") { ?> selected="selected" <?php } ?> >1 an</option>
				    </select> 

					<label for="nombre">Nombre d'oeufs</label> : <select name="nombre" id="nombre" <?php if($ouinon==0) { ?> disabled="true" <?php } ?>  />
						<option value="6" <?php if($nombre=="6") { ?> selected="selected" <?php } ?> >6</option>
						<option value="12" <?php if($nombre=="12") { ?> selected="selected" <?php } ?> >12</option>
						<option value="18" <?php if($nombre=="18") { ?> selected="selected" <?php } ?> >18</option>
						<option value="24" <?php if($nombre=="24") { ?> selected="selected" <?php } ?> >24</option>
						<option value="30" <?php if($nombre=="30") { ?> selected="selected" <?php } ?> >30</option>
						<option value="36" <?php if($nombre=="36") { ?> selected="selected" <?php } ?> >36</option>
					</select><br /><br />	
					
					<label for="remarques">Remarques <span style="color:yellow"; >(Vous pouvez préciser un autre prix, une autre durée, etc.)</span> :</label><br />
					<textarea name="remarques" id="remarques"  rows="10" cols="50"><?php if($ouinon!=-1) echo(stripslashes($remarque)); ?></textarea>

					<input style="position:relative; left:50px; bottom:150px;" type="submit" value="Valider" />
					<input style="position:relative; left:50px; bottom:150px;" type="button" value="Annuler" onclick="window.location.href='index.php'" />

				</p>
				</form>
			 <?php } ?>
		</div>	

		
<script type="text/javascript">
<!--
function masquer()
{
	var monForm = document.getElementById("monFormulaire");

	if(document.getElementById("monFormulaire").non.checked) {
		document.getElementById("monFormulaire").frequence.disabled=true;
		document.getElementById("monFormulaire").duree.disabled=true;
		document.getElementById("monFormulaire").nombre.disabled=true;
	}
	else {
		document.getElementById("monFormulaire").frequence.disabled=false;
		document.getElementById("monFormulaire").duree.disabled=false;
		document.getElementById("monFormulaire").nombre.disabled=false;
	
	}
};
document.getElementById("monFormulaire").oui.onclick = masquer; 
document.getElementById("monFormulaire").non.onclick = masquer; 
//-->
</script>

		
		
		
	</body>
</html>
