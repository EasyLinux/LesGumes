<?php
include_once("define.php"); 
include_once("../espace_producteurs/mes_fonctions.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="styleW.css" />
	</head>
	
	<body>
	
<script type="text/javascript">
<!--

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

function ChangeAmap() {     
  	var i=document.getElementById("nom_amap").selectedIndex;
  
  	var objForm2 = createForm("ordermanager", "get", "webmaster_infos.php");
  	objForm2.appendChild(createHiddenInput("nom_amap", document.getElementById("nom_amap").options[i].value));
  	document.body.appendChild(objForm2);
  	objForm2.submit();
}

function PermissionBouton(table) {
	if(table==1) {
		document.getElementById("inserer").style.visibility='visible';
		document.getElementById("retirer").style.visibility='hidden';
		document.getElementById("verrouiller").style.visibility='hidden';
    document.getElementById("deverrouiller").style.visibility='hidden';
		for (var i=0; i<document.getElementById("table_amap").options.length; i++) {
                document.getElementById("table_amap").options[i].selected = ""; }
  }
	else {
	 var i=document.getElementById("table_amap").selectedIndex;
	 var text= document.getElementById("table_amap").options[i].text;
	 if ( text.indexOf('*') != -1)  {    // contrat non verrouillé
	     document.getElementById("verrouiller").style.visibility='visible';
 	     document.getElementById("deverrouiller").style.visibility='hidden';
   }
   else {         // contrat verrouillé
    	document.getElementById("deverrouiller").style.visibility='visible';
    	document.getElementById("verrouiller").style.visibility='hidden';
   }
	
		document.getElementById("inserer").style.visibility='hidden';
		document.getElementById("retirer").style.visibility='visible';		
		for (var i=0; i<document.getElementById("table_generale").options.length; i++) {
                document.getElementById("table_generale").options[i].selected = ""; }
	}
}

function PermissionRadiation() {
	document.getElementById("supprimer").style.visibility='visible';
}

function Inserer(amap) {
	var i=document.getElementById("table_generale").selectedIndex;
	var adherent= document.getElementById("table_generale").options[i].value;
	
	var objForm2 = createForm("ordermanager", "get", "inserer_adherent.php");
	objForm2.appendChild(createHiddenInput("id", adherent));
	objForm2.appendChild(createHiddenInput("amap", amap));
	document.body.appendChild(objForm2);
	objForm2.submit();
	
}

function Retirer(amap) {
	var i=document.getElementById("table_amap").selectedIndex;
	var adherent= document.getElementById("table_amap").options[i].value;
	
	if (window.confirm("Retirer l'adhérent "+adherent+" de l'"+amap)) {
		var objForm2 = createForm("ordermanager", "get", "retirer_adherent.php");
		objForm2.appendChild(createHiddenInput("id", adherent));
		objForm2.appendChild(createHiddenInput("amap", amap));
		document.body.appendChild(objForm2);
		objForm2.submit();
	}
}  

function Verrouiller(amap) {         
	var i=document.getElementById("table_amap").selectedIndex;
	var adherent= document.getElementById("table_amap").options[i].value; 
 	
		if (window.confirm("verrouiller le contrat de l'adhérent "+adherent+" de l'"+amap)) {
  		var objForm2 = createForm("ordermanager", "get", "verrouiller_contrat.php");
  		objForm2.appendChild(createHiddenInput("id", adherent));
  		objForm2.appendChild(createHiddenInput("amap", amap));
   		objForm2.appendChild(createHiddenInput("etat", 1));
   		document.body.appendChild(objForm2);
  		objForm2.submit();
	 }	
}

function Deverrouiller(amap) {         
	var i=document.getElementById("table_amap").selectedIndex;
	var adherent= document.getElementById("table_amap").options[i].value;        
	
		if (window.confirm("déverrouiller le contrat de l'adhérent "+adherent+" de l'"+amap)) {
  		var objForm2 = createForm("ordermanager", "get", "verrouiller_contrat.php");
  		objForm2.appendChild(createHiddenInput("id", adherent));
  		objForm2.appendChild(createHiddenInput("amap", amap));
  		objForm2.appendChild(createHiddenInput("etat", 0));
      document.body.appendChild(objForm2);
  		objForm2.submit();
	 }
}


function Radier() {
	var i=document.getElementById("table_ss_contrat").selectedIndex;
	var adherent= document.getElementById("table_ss_contrat").options[i].value;

	if (window.confirm("Radier l'adhérent "+adherent+" de l'association")) {
		var objForm2 = createForm("ordermanager", "get", "radier_adherent.php");
		objForm2.appendChild(createHiddenInput("id", adherent));
		document.body.appendChild(objForm2);
		objForm2.submit();
	}
}

  
//-->
</script>

	
	<div id="page_principale">
		<h2> Adhérents du contrat <?php 
			$amap=$_GET['nom_amap'];	// /!\ attention, voir pourquoi on ne peut pas utilisé GET['amap'] ici -> pb le GET['amap'] est remis à vide dans la suite quand on ajoute un amapien dans la table
			echo $amap;?>
			
		<h4>Sélectionner une personne pour la retirer ou l'insérer :</h4>
		<form id="MForm2" action="">
			<?php
			mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
			mysqli_select_db(base_de_donnees); // Sélection de la base 
			$ListeGenerale=mysqli_query("SELECT id, Nom, Prenom FROM amap_generale ORDER BY Nom") or die(mysqli_error());
			$NbAdherent = mysqli_num_rows($ListeGenerale);
			$question="SELECT id, Nom, Prenom, Contrat_verrouille FROM ".$amap." ORDER BY Nom";

			$ListeAmap=mysqli_query($question) or die(mysqli_error());
			$NbAmap = mysqli_num_rows($ListeAmap);
			?>
			<table >
				<tr>
					<th>Table des amapiens (<?php echo $NbAdherent; ?>)</th>
					<th>Actions</th>
					<th>Table des adhérents (<?php echo $NbAmap; ?>)</th>
				</tr>
				<tr >
					<td>
						<select size="35" name="table_generale" id="table_generale" onchange="javascript:PermissionBouton(1)">
							<?php while ($donnees = mysqli_fetch_array($ListeGenerale)){ ?>
								<option value="<?php echo $donnees['id']; ?>"><?php echo $donnees['Nom'].' '.$donnees['Prenom'].' [id='.$donnees['id'].']'; ?></option>
							<?php } ?>
						</select>
					</td>
					<td style="text-align:center">
						<input style="visibility:hidden" name="inserer" id="inserer" type="button" value="Insérer dans <?php echo $amap; ?> &mdash;&rsaquo;" onclick="javascript:Inserer('<?php echo $amap; ?>')"/><br />
						<input style="visibility:hidden" name="retirer" id="retirer" type="button" value="&lsaquo;&mdash; Retirer de <?php echo $amap; ?>" onclick="javascript:Retirer('<?php echo $amap; ?>')"/><br /><br />
						<input style="visibility:hidden" name="verrouiller" id="verrouiller" type="button" value="Verrouiller contrat" onclick="javascript:Verrouiller('<?php echo $amap; ?>')"/><br />
						<input style="visibility:hidden" name="deverrouiller" id="deverrouiller" type="button" value="Deverrouiller contrat" onclick="javascript:Deverrouiller('<?php echo $amap; ?>')"/><br /><br />
						<input name="imprimerAM" id="imprimerAM" type="button" value="Tous les amapiens" onclick="document.location.href='ImprimeAM.php?amap=<?php echo $amap; ?>'"/><br />
						<input name="imprimerLG" id="imprimerLG" type="button" value="Liste des adhérents" onclick="document.location.href='ImprimeLG.php'"/><br /><br />
						<input type="button" value="Retour à l'accueil" onclick="document.location.href='../index.php'"/><br />
						<input type="button" value="Menu webmaster" onclick="document.location.href='webmaster.php?mode=<?php echo $_GET['nom_amap']; ?>'"/>
					</td>
					<td>                                                                                      
						<select size="35" name="table_amap" id="table_amap" onchange="javascript:PermissionBouton(2)">
					<?php while ($donnees = mysqli_fetch_array($ListeAmap)){ 
						if( $donnees['Contrat_verrouille'] == 0 ) {$etat='*';} else  { $etat='';}?>
							<option value="<?php echo $donnees['id']; ?>"><?php echo $donnees['Nom'].' '.$donnees['Prenom'].' '.$etat; ?></option>
					<?php } ?>
						</select>
					</td>
				</tr>
				<!--?php
				$question="SELECT id, Nom, Prenom FROM amap_generale ";
				$question.="WHERE id NOT IN (SELECT id FROM amap_legumes) AND id NOT IN (SELECT id FROM amap_cerises) ";
				$question.="AND id NOT IN (SELECT id FROM amap_pain) AND id NOT IN (SELECT id FROM amap_oeufs) ";
				$question.="AND id NOT IN (SELECT id FROM amap_poulets) AND id NOT IN (SELECT id FROM amap_produits_laitiers) ";
				$question.="AND id NOT IN (SELECT id FROM amap_pommes) AND id NOT IN (SELECT id FROM amap_poissons) ORDER BY Nom";
				$SansContrat=mysqli_query($question);
				$NbSsContrat = mysqli_num_rows($SansContrat);
				?-->
				
			</table>
			<?php mysqli_close();?>
		</form>
	</div>
	</body>
</html>
