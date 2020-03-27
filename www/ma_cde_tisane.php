<?php
include_once("webmaster/define.php");
include_once("fonctions_tisanes.php");

//Principe de fonctionnement
/*
	la table "amap_tisanes_cde" doit avoir �tre cr��e par l'administrateur lors de la saisie des produits disponibles de Leila
	Si aucune ligne de la table ne concerne la prochaine distribution, message sp�cifique "Le choix des produits de la prochaine distribution n'est pas encore accessible"

	Dans le contrat, l'amapien a d�fini le nombre de tisanes simples, petites ou grandes compositions et le nombre de sirop.
	il doit maintenant d�finir le nom exact de chaque produit .
	On va donc ajouter une combo par produit qui contiendra l'ensemble des produits correspondant au type de produit.
	On ajoute aussi comme choix "choix ind�fini" qui sera la valeur par d�faut. Si l'amapien ne saisit pas une tisane,
	Leila lui fournira une tisane de son choix (premier choix de la combo : choix ind�fini ; associ� � la valeur "null" en base)
*/
function creerCombosPourType($typeProd,$nomType,$dateDerniereCommande, $id, $modeAcces){
	// R�cup�ration des lignes de la commande concern�e pour le type demand�
	$questionCommande="SELECT * FROM amap_tisanes_cde where Id_Personne=$id and Date='$dateDerniereCommande' and Type_Produit=$typeProd";
	//echo "questionCommande : $questionCommande <BR>"; 
	$tabDerniereCommandes = mysql_query($questionCommande) or die(mysql_error());
	// echo "tabDerniereCommandes: $tabDerniereCommandes <BR>"; 
	$cpt=0;
	while ($tabDerniereCommande=mysql_fetch_array($tabDerniereCommandes)) {
		$cpt++;		
		$questionChoix="SELECT * FROM amap_tisanes_produits where Type=$typeProd ";
		if ($modeAcces == 2) {
			$questionChoix.="and Dispo=1 ";
		}
		$questionChoix.="ORDER by id";
		$tabChoix = mysql_query($questionChoix) or die(mysql_error());		
		$idProduit = $tabDerniereCommande["Id_Produit"];
		
		// cr�ation d'une combo avec les choix de tisanes possibles 
		?>
		<tr>
			<td><?php echo$nomType ;?> n�<?php echo $cpt; ?> </td>
			<td> 
				<select <?php 	if ($modeAcces == 1) { echo "disabled " ; } 
								echo "name='".$tabDerniereCommande["Indice"]."'"; 
					?>>
					<option value="NULL" <?php
						 if ($idProduit == null) { ?> selected="selected" <?php 
						 } ?> >choix ind�fini</option> <?php 
					
					while ($choix=mysql_fetch_array($tabChoix)) { 
					?>	
					
					<option value="<?php echo $choix["id"] ;?>" <?php
							if ($idProduit == $choix["id"]) { ?> selected="selected" <?php 
							} ?>> <?php echo $choix["Nom_produit"]." - ".$choix["Quantite"];?>
					</option> <?php 
					} ?>
				</select>
			</td>
		</tr>
	<?php
	} 
} 

$ok=-1;/* identification non faite */
if (isset($_COOKIE['identification_amap'])) // Si la variable existe
{
	$ok=0;/* identification faite mais non inscrit � l'amap */
	mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
	mysql_select_db(base_de_donnees); // S�lection de la base 
	$id=$_COOKIE['identification_amap'];
//$id=203;
	$question="SELECT * FROM amap_tisanes WHERE id='".$id."'";
	$reponse = mysql_query($question) or die(mysql_error());
  
	if( mysql_num_rows($reponse)>0) {
		$ok=1;/* inscrit � l'amap modification possible */
		$contrat = mysql_fetch_array($reponse);
    
	}
	mysql_close(); // D�connexion de MySQL
}
if ($ok==1) // inscrit � l'amap
{
//***********************************************************************
// On a le droit
//***********************************************************************
?>

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
	
<script language="javascript">
<!-- ins�rer les �ventuelles fonctions javascript -->
function createHiddenInput(strName, varValue)
{
	var objHidden = document.createElement("input");
	objHidden.setAttribute("type", "hidden");
	objHidden.setAttribute("name", strName);
	objHidden.setAttribute("value", varValue);
	return objHidden;
}

function createForm(strName, strMethod, strAction)
{
	var objForm = document.createElement("form");
	objForm.setAttribute("name", strName);
	objForm.setAttribute("method", strMethod);
	objForm.setAttribute("action", strAction);
	return objForm;
}

<!-- sauvegarde des valeurs dans des champs d'un nouveau formulaire. Name=Indice Value=Id_Produit -->
function ConfirmeRecord(strID, dateDerniereCommande) {  
	var objForm = document.MForm ;
	
	if (window.confirm("Confirmez vous la sauvegarde de votre panier tisanes ?")) {
	
		var objForm2 = createForm("ordermanager", "post", "maj_livraison_tisanes.php");
		
		objForm2.appendChild(createHiddenInput("ID", strID));
		objForm2.appendChild(createHiddenInput("dateDerniereCommande", dateDerniereCommande));

		
		for ( var i=0; i< objForm.elements.length;i++) {
			var e = objForm.elements[i] ;
			if (e.type=='select-one') {
				objForm2.appendChild(createHiddenInput(objForm.elements[i].name, objForm.elements[i].value));
	<!--alert("name : " +objForm.elements[i].name+" : value : "+objForm.elements[i].value); -->
			}
		}		
		document.body.appendChild(objForm2);
		objForm2.submit();
	}
}
</script>

		<div id="en_tete">
			<?php include_once("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include_once("includes/bandeau.php");?>
		</div>
		<div id="page_principale_tisane">	
		<?php 
// consultation ou mise � jour de la table amap_tisanes_cde
// consultation ET mise � jour ne se font que si les enregistrements de la table amap_tisanes_cde ont d�j� �t� cr��s par l'administrateur 
// pour la prochaine livraison
// La mise � jour n�cessite en plus que la date du jour soit inf�rieure � la (date de distribution - jours de marge) 

		mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
		mysql_select_db(base_de_donnees); // S�lection de la base 
		
		// existe-t-il au moins une commande pour la personne connect�e?
		$questionAuMoinsUneCommande="SELECT Date,Date_modif FROM amap_tisanes_cde where Id_Personne=".$id." and date=(select Max(Date) FROM amap_tisanes_cde where Id_Personne=".$id.")";
		// echo "question AuMoinsUneCommande:".$questionAuMoinsUneCommande."<BR>"; 
	   	$tabDerniereCommande = mysql_query($questionAuMoinsUneCommande) or die(mysql_error());
		// echo "tabDerniereCommande:".$tabDerniereCommande."<BR>"; 
		$tabDateDerniereCommande=mysql_fetch_array($tabDerniereCommande);
		// echo "tabDateDerniereCommande:".$tabDateDerniereCommande."<BR>"; 
		$dateDerniereCommande=$tabDateDerniereCommande[0];
		// echo "date derni�re commande : ".$dateDerniereCommande."<BR>"; 
		$dateModif=$tabDateDerniereCommande[1];
		// echo "date modif : ".$dateModif."<BR>"; 
		
		$auj=time();
		// echo "auj : ".$auj."<BR>"; 
		
		$modeAcces = 0; // 0 : rien � visualiser	1 : acc�s en consult de la derni�re commande	2 : acc�s en MAJ de la derni�re commande
		$joursDeMarge = JOURS_MARGE_TISANE; // nb de jours de marge entre la commande et la distribution. 4 jours : modif possible jusqu'au dimanche pr�c�dent inclus
		// Si on a r�cup�r� une date de derni�re commande, donc il y a eu au moins une commande, donc au moins quelque chose � montrer
		if ($dateDerniereCommande!=null) {
			// comparaison date livraison avec date limite
			$temp=strtotime($dateDerniereCommande);
			$limitePourCommande=$temp-$joursDeMarge*24*60*60;
			// echo "limitePourCommande : ".$limitePourCommande."<BR>"; 
			if($auj<$limitePourCommande) {
				$modeAcces = 2; // Mode MAJ sur la prochaine commande
			} else {
				$modeAcces = 1; // Mode consultation sur la derni�re commande
			}
		} // sinon modeAcces reste � 0
	
		// echo "modeAcces:".$modeAcces."<BR>"; 
		
		if ($modeAcces == 0) {?>
			<h3 class="mot_passe_recette">Pas de derni�re commande de tisanes � afficher</h3>
		<?php } else {
			?> 
			<form name="MForm">
			<table>
				<caption>Commande de tisanes pour la livraison du <?php
				echo $dateDerniereCommande;
				if ($modeAcces == 1) {
					echo "<BR />";
				}
				
			// Affichage de la derni�re commande

			// on it�re sur les 4 types possibles
			?></caption>
		<?php
			$questionNbLigne="SELECT COUNT(*) FROM amap_tisanes_cde where Id_Personne=$id and Date='$dateDerniereCommande'";
			$reponseNBLigne = mysql_query($questionNbLigne) or die(mysql_error());
			$nbLigne = mysql_fetch_array($reponseNBLigne);
			$nbLigne = $nbLigne[0] +1;
			if ($nbLigne < 3) $nbLigne = 3; // Pb d'affichage si pas assez de lignes
			// echo "nbligne=".$nbLigne; ?>
			<tr>
				<td rowspan=<?php echo "$nbLigne>"; if ($modeAcces == 2) {?>	
					<button onclick="javascript:ConfirmeRecord(<?php echo $id.",'".$dateDerniereCommande."'"; ?>)" id="BtnValider" name="BtnValider" type="Button" class="BtnStd">Enregistrer</button><br />
					<button onclick="document.location.href='index.php'" id="BtnAnnuler" name="BtnAnnuler" type="Button" class="BtnStd">Annuler</button><br /><?php 
				}?>	
					<button onclick="document.location.href='mailto:sodimoreau@sfr.fr?subject=AMAP tisanes'" name="BtnMail" type="Button" class="BtnStd">Ecrire au responsable</button><br />
					<?php Echo "<br />La derni�re modification<br />date du ".date("d-M-Y",strtotime($dateModif));
					?>
					
				</td>
				<th> Type de produit</th>
				<th> Votre choix</th>
			</tr>
			
			<?php
			creerCombosPourType(1, "Tisane simple", $dateDerniereCommande, $id, $modeAcces);
			creerCombosPourType(2, "Petite composition", $dateDerniereCommande, $id, $modeAcces);
			creerCombosPourType(3, "Grande composition", $dateDerniereCommande, $id, $modeAcces);
			creerCombosPourType(4, "Sirop", $dateDerniereCommande, $id, $modeAcces);
			?> </table> 
			</form> <?php
		}	
		?>
		<h4>Pour rappel, en absence de choix de votre part (les choix ind�finis des listes d�roulantes),<br /> Leila d�terminera le produit � livrer en fonction de ses stocks disponibles.</h4>
	</body>
</html>

<?php   
}   
if($ok==0) // non inscrit � l'amap
{
?>

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
			?>
			<h3 class="mot_passe_recette">Vous n'�tes pas inscrit � l'AMAP tisanes</h3>
		</div>		
		<div id="pied_page">
			<!-- <?php include_once("includes/pied_page.php") ?> -->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas o� l'image ne peut pas �tre t�l�charg�e -->
	</p>
	</body>
</html>
<?php } 
if($ok==-1) { //premier chargement du mot de passe
 ?>

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
			?>
			<h3 class="mot_passe_recette">Il faut vous identifier pour acc�der � ce service !!</h3>
		</div>		
		<div id="pied_page">
			<!-- <?php include_once("includes/pied_page.php") ?> -->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas o� l'image ne peut pas �tre t�l�charg�e -->
	</p>
	</body>
</html> 
 <?php 
 } 
 ?>
