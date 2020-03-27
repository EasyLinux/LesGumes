<?php
/* en arrivant du menu de l'index ordre='passe' et il faut demander le mot de passe */
/* ensuite ordre!='passe' et on ne demande plus le mot de passe */
include_once("webmaster/define.php");
include_once("fonctions_tisanes.php");

$ok=-1;
if (isset($_POST['motpasse'])) { $ok=0; $mot_test=$_POST['motpasse']; } else $mot_test='';
if($mot_test=="potronminet")$ok=1;

function entete1($tabTypeProduit) {
		// Partie du tableau indiquant le type des produits et dessous les produits
		?><tr><th></th>
		<?php
		for ($cpt=1; $cpt <= 4; $cpt++) {
			$nbProd = $tabTypeProduit[$cpt];
			if ($nbProd==0) {
				// Pas de produits pour ce type : ne rien faire
			} else {
				if ($cpt==1) {
				$txt = "Tisanes simples";
				} elseif ($cpt==2) {
				$txt = "Petites compositions";
				} elseif ($cpt==3) {
				$txt = "Grandes compositions";
				} elseif ($cpt==4) {
				$txt = "Sirops";
				}  
				?> <th colspan="<?php echo $nbProd;?>"><?php echo $txt;?> </th><?php				
			}			
		} 
		?>
		<th></th>
		<?php
}

function entete2($tabNomProduit, $nombreProd) {
		// Partie du tableau indiquant le type des produits et dessous les produits
		?><tr><th>Nom</th>
		<?php
		for ($cpt=1; $cpt <= $nombreProd; $cpt++) {
			$nomProd = $tabNomProduit[$cpt];
			// extraction des 7 premiers caractères
			$debut = substr($nomProd,0, 7);
			if ($debut=="petite " || $debut=="grande " ) {
				// on supprime le prefixe 'petite ' / 'grande '
				$nomProd = substr($nomProd,7);
			} else {
				$debut = substr($nomProd,0, 6);
				if ($debut=="sirop ") {
					// on supprime le prefixe 'sirop '
					$nomProd = substr($nomProd,6);
				}
			}
			?> <th><?php echo $nomProd;?></th> <?php
		}
				
		?>
		<th>Total</th>
		</tr>

		<?php
}

function getDateProchaineCommande() {
	$auj=time();
	$aujSQL = date("Y-m-d",$auj);
	
	//echo "auj : ".$auj."<BR>"; 
	//echo "aujSQL : ".$aujSQL."<BR>"; ;
	
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db(base_de_donnees); // Sélection de la base 
	$questionProchaineCommande="SELECT Min(Date) FROM amap_tisanes_permanences where Date>'".$aujSQL."'";
	//echo "questionProchaineCommande:".$questionProchaineCommande."<BR>"; 
	$tabProchaineCommande = mysql_query($questionProchaineCommande) or die(mysql_error());
	//echo "tabProchaineCommande:".$tabProchaineCommande."<BR>"; 
	$tabDateProchaineCommande=mysql_fetch_array($tabProchaineCommande);
	$dateProchaineCommande=$tabDateProchaineCommande[0];
	mysql_close();

	return $dateProchaineCommande;
}

function commandesEnCours($dateDerniereCommande) {
	$temp=strtotime($dateDerniereCommande);
	$limitePourCommande=$temp-JOURS_MARGE_TISANE*24*60*60;
	return (time()<$limitePourCommande); 
}


if($ok==1)
// Si le mot de passe est bon
{
//***********************************************************************
// On a le droit
//***********************************************************************
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
	<div style="text-align: center;">
		<?php
		// recherche des commandes pour la prochaine distribution 		
		$dateProchaineCommande=getDateProchaineCommande();
		//echo "date prochaine commande : *".$dateProchaineCommande."*<BR>"; 
		if (strlen($dateProchaineCommande)==0) {
			// Affichage d'un message spécifique si plus de distribution prévue
			?>
			<h3 class="mot_passe_recette">Plus de date de distribution de prévue.<br />voir avec le référent ou l'administateur de la base!!</h3><?php 
		} else {
			// Début des traitement pour affichage de la prochaine commande
			if (commandesEnCours($dateProchaineCommande)) {	
			?>
				<h3 class="mot_passe_recette">
				 ATTENTION : Les amapiens peuvent encore modifier leur choix jusqu'à la date de livraison moins 9 jours !!<br />
				 Ce récapitulatif n'est pas définitif
				</h3>
			<?php } else { 
			?>
				<button onclick="document.location.href='ImprimePDF_tisanes.php?date=<?php echo $dateProchaineCommande; ?>'" name="BtnImprime" type="button" class="BtnStd">Enregistrer pour imprimer</button>
			<?php } ?>
				<button onclick="document.location.href='index.php'" name="BtnRetour" type="button" class="BtnStd">Retour</button><br />
	</div>
	<div id='page_recap_tisane'><?php
	// Création de l'entête du tableau $tabNomProduit $tabIndice avec les colonnes-produits et $tabTypeProduit pour les types des produits corespondant
	?><table>
		<caption> Les produits commandés pour la livraison du <?php echo $dateProchaineCommande; ?> </caption>
	<?php

	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db(base_de_donnees); // Sélection de la base 
	
	$tab = computeProducts($dateProchaineCommande);
	$nombreProd= $tab['nombreProd'];
	$tabNomProduit= $tab['tabNomProduit'];
	$tabIndice= $tab['tabIndice'];
	$tabTypeProduit= $tab['tabTypeProduit'];
	
	$tab = computeCommandes($dateProchaineCommande);
	$tabCommandes = $tab['tabCommandes'];
	$noms = $tab['noms'];
	$totalColonne = $tab['totalColonne'];
	mysql_close();
	
	entete1($tabTypeProduit);
	entete2($tabNomProduit, $nombreProd);
	
	// il ne reste plus qu'à parcourir le tableau pour afficher les commandes
	$total1=0;
	$j=0;
	foreach($tabCommandes as $idPers => $value) {
		if($j % 3==1) $color= '#40a7f5'; elseif($j % 3==2) $color= '#f9f580'; else $color= 'white';
		$j++;
		?> <tr style="background-color: <?php echo $color;?>"><td> <?php echo $noms[$idPers];?></td>
		<?php
			$totalLigne =0;
			for ($cpt=1; $cpt <= $nombreProd; $cpt++) {
				$quantite = $value[$tabIndice[$cpt]];
				$totalLigne += $quantite;
				
				?> <td> <?php echo $quantite;?> </td> <?php				
			} 
			$total1 += $totalLigne; ?>
		<td> <?php echo $totalLigne;?></td>	
		</tr> <?php
	} 

	?> <tr> 
		<th>Total</th>
		<?php
		// la dernière ligne des totaux : 
		$total2 =0;
		for ($cpt=1; $cpt <= $nombreProd; $cpt++) {
			$quantite = $totalColonne[$tabIndice[$cpt]];
			$total2 += $quantite;
			?> <th> <?php echo $quantite;?> </th> <?php				
		} 
		$TT= ($total1 == $total2) ? $total1 : "Erreur" ?>
		<th> <?php echo $TT; ?> </th>
		
	</tr>	
	<?php
		entete2($tabNomProduit, $nombreProd);
		entete1($tabTypeProduit);
	?>
	</table>
	<?php } ?>
	</body>
</html>

<?php
} 
else // le mot de passe n'est pas bon
{
//***********************************************************************
// On affiche la zone de texte pour rentrer de nouveau le mot de passe.
//***********************************************************************
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

<script type="text/javascript">
<!--
function verifkey(boite) {
	if(boite.value=='') {
		alert('Entrer un mot de passe!');
		boite.focus();
		return false;
	}
	return true;
}
-->
</script>
	
	<body onload="document.getElementById('motpasse').focus();">
		<div id="page_principale">
			<form class="mot_passe_recette" onsubmit="return verifkey(this.motpasse);" method="post" action="liste_produits_tisanes.php" >
				<p class="mot_passe_recette">
					<?php if($ok==0) { ?>Mot de passe incorrect!!<br /><?php } ?>
					<label for="motpasse">Mot de passe</label> : <input type="password" name="motpasse" id="motpasse" size="50" maxlength="45" tabindex="10"/>
					<input type="submit" tabindex="20"/> <input type="reset" tabindex="30"/>
				</p>
			</form>

		</div>		
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</p>
	</body>
</html>
<?php } ?>