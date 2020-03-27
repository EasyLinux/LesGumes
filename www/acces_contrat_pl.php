<?php

//Principe de fonctionnement
//la table "amap_produits_laitiers_cde_en_cours" doit être créée en dupliquant le contenu de la table "amap_produits_laitiers_cde"
//dans cette table le champ "Date_modif" doit être remplacé par Date_livraison et initialisé à NULL


include_once("webmaster/define.php");
$ok=-1;/* identification non faite */
if (isset($_COOKIE['identification_amap'])) // Si la variable existe
{
	$ok=0;/* identification faite mais non inscrit à l'amap */
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db(base_de_donnees); // Sélection de la base 
	$id=$_COOKIE['identification_amap'];
	$question="SELECT * FROM amap_produits_laitiers_cde WHERE id='".$id."'";
	$reponse = mysql_query($question);
	$ligne = mysql_num_rows($reponse);
	if($ligne>0) {
		$ok=1;/* inscrit à l'amap modification possible */
		$donnee = mysql_fetch_array($reponse);
		$VPunite=$donnee['Unite'];
	}
	mysql_close(); // Déconnexion de MySQL
}
if ($ok==1) // inscrit à l'amap
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
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>
	<body>
	
<script language="javascript">
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

function RemiseZero(strVPunite) {
	var objForm = document.MForm ;
	var objElemMessage=document.getElementById('message');
	var objElemTotalUnite=document.getElementById('total_unite');
	var objElemTotalPrix=document.getElementById('total_prix');
	var objElemBtnValider=document.getElementById('BtnValider');
	var objElemBtnAnnuler=document.getElementById('BtnAnnuler');

	for ( var i=0; i< objForm.elements.length;i++) {
		var e = objForm.elements[i] ;
		if (e.type=='select-one') {
			e.selectedIndex="0";
		}
	}		
	objElemMessage.innerHTML='Il faut ajouter ';
	objElemMessage.innerHTML+=strVPunite;
	objElemMessage.innerHTML+=' unité(s)';
	objElemTotalUnite.innerHTML='0 u';
	objElemTotalPrix.innerHTML='0 &euro;';
	objElemBtnValider.style.display='none';
	objElemBtnAnnuler.style.display='none';
	for(var i=1;i<17;i++) {
		var objElemPrix=document.getElementById('prix_'+i);
		var objElemUnite=document.getElementById('unite_'+i);
		objElemUnite.innerHTML='0 u';
		objElemPrix.innerHTML='0 &euro;';
	}
}

function MAJtableau(strProduit, strUnite, strPrix, strValeur, strVPunite){
 
  var objElemProduit=document.getElementById(strProduit);
	var objElemUnite=document.getElementById(strUnite);

	var objElemPrix=document.getElementById(strPrix);
	var objElemTotalUnite=document.getElementById('total_unite');
	var objElemTotalPrix=document.getElementById('total_prix');
	var objElemMessage=document.getElementById('message');
	var objElemBtnValider=document.getElementById('BtnValider');
	var objElemBtnAnnuler=document.getElementById('BtnAnnuler');
	var u=parseInt(objElemUnite.innerHTML);
 	var vp=parseInt(strVPunite);
	var diff=vp;
	
	u=parseInt(objElemTotalUnite.innerHTML)-u;
	if(strUnite=='unite_11') { strNvUnit=(2*strValeur).toString(); strNvUnit=strNvUnit.concat(' u'); u+=2*strValeur;}
	else { strNvUnit=strValeur.concat(' u'); u+=1*strValeur;}
	objElemUnite.innerHTML=strNvUnit;
	objElemTotalUnite.innerHTML=u;
	objElemTotalUnite.innerHTML+=' u';
	if(strUnite=='unite_11') { strNvPrix=(4*strValeur).toString(); strNvPrix=strNvPrix.concat(' \u20AC'); }
	else { strNvPrix=(2*strValeur).toString(); strNvPrix=strNvPrix.concat(' \u20AC'); }
	objElemPrix.innerHTML=strNvPrix;
	objElemTotalPrix.innerHTML=2*u;
	objElemTotalPrix.innerHTML+='  \u20AC';
	diff=vp-u; if (diff<0) diff=-diff;
	if (vp<u) objElemMessage.innerHTML='Il faut enlever ';
	if (vp>u) objElemMessage.innerHTML='Il faut ajouter ';
	objElemMessage.innerHTML+=diff;
	objElemMessage.innerHTML+=' unité(s)';
	objElemBtnValider.style.display='none';
	objElemBtnAnnuler.style.display='none';
	if (diff==0) {
		objElemMessage.innerHTML='';
		objElemBtnValider.style.display='inline';
		objElemBtnAnnuler.style.display='inline';
	}
}

function ConfirmeRecord(strID) {
	var objForm = document.MForm ;
	var j=0;
	
	if (window.confirm("Ces produits seront automatiquement reconduits d'une livraison à l'autre\n\ntant que vous ne modifiez pas ce choix par cette même procédure !")) {
		var objForm2 = createForm("ordermanager", "post", "maj_livraison_prod_lait.php");
		objForm2.appendChild(createHiddenInput("ID", strID));
		for ( var i=0; i< objForm.elements.length;i++) {
			var e = objForm.elements[i] ;
			if (e.type=='select-one') {
				j++;
				objForm2.appendChild(createHiddenInput("Elt"+j+"ID", objForm.elements[i].value));
			}
		}		
		document.body.appendChild(objForm2);
		objForm2.submit();
	}
}
//-->
</script>

	
		<div id="en_tete">
			<?php include_once("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include_once("includes/bandeau.php");
			
//mise à jour de la table_cde_en_cours par recopie de la table_cde
//cette mise à jour ne se fait que si     
//		[Date de la table cde_en_cours] < DateAujourd'hui
//      et si      [datelimite] <= [date_aujourd'hui] <= [dateProchaineLivraison]

//cette partie de programme se trouve aussi dans l'accès à la liste des produits réservée au producteur
//si bien que c'est le premier accès d'un amapien ou le premier acces du producteur après la date limite qui provoque
//la mise à jour de la table cde_en_cours 
		mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
		mysql_select_db(base_de_donnees); // Sélection de la base 
		$question="SELECT * FROM amap_produits_laitiers_permanences ORDER BY DATE";
		$reponse1 = mysql_query($question);
		$question="SELECT Date_livraison FROM amap_produits_laitiers_cde_en_cours";
		$reponse2 = mysql_query($question);
		$TableDateLiv=mysql_fetch_array($reponse2);
		$DateLivEnCours=strtotime($TableDateLiv[0]);
		$auj=time();
		$flag=0; //la date dans cde_en_cours est sup à la date d'aujourd'hui
		//on écrase la table cde_en_cours que si il existe une livraison plus loin que la date d'aujourd'hui et à moins de 9 jours
		if($auj>$DateLivEnCours || $DateLivEnCours==NULL) { 
			$flag=-1;//impossible imprimer les amapiens peuvent encore modifier leur choix
			while($DateLiv = mysql_fetch_array($reponse1)) if($DateLiv['Distribution']=='1'){
				if($DateLivEnCours==NULL) {
					$flag=1;
					$LaDate=$DateLiv['Date'];
					break;
				}
				$temp=strtotime($DateLiv['Date']);
				$limite=$temp-JOURS_MARGE_PDT_LAITIER*24*60*60;
				if($auj>=$limite && $auj<=$temp) { 
					$flag=1;
					$LaDate=$DateLiv['Date'];
					break;
				}
			}
		}
		if($flag==1) {
			$question="TRUNCATE TABLE amap_produits_laitiers_cde_en_cours";
			$reponse=mysql_query($question);
			$question="INSERT INTO amap_produits_laitiers_cde_en_cours SELECT * FROM amap_produits_laitiers_cde";
			$reponse=mysql_query($question);
			$question="UPDATE amap_produits_laitiers_cde_en_cours SET Date_livraison='".$LaDate."'";
			$reponse=mysql_query($question);
		}
		mysql_close();
//fin mise à jour table_cde_en_cours
			
			mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
			mysql_select_db(base_de_donnees); // Sélection de la base 
			$question="SELECT * FROM amap_produits_laitiers_permanences  ORDER BY DATE";
			$reponse1 = mysql_query($question);
			$question="SELECT Date_livraison FROM amap_produits_laitiers_cde_en_cours";
			$reponse2 = mysql_query($question);
			$TableDateLiv=mysql_fetch_array($reponse2);
			$DateLivEnCours=strtotime($TableDateLiv[0]);
			$auj=time();
			$flag=0;
			//il faut trouver une date de livraison telle que aujourd'hui < à cette date - JOURS_MARGE_PDT_LAITIER jours
			while($DateLiv = mysql_fetch_array($reponse1)) if($DateLiv['Distribution']=='1'){
				$temp=strtotime($DateLiv['Date']);
				$limite=$temp-JOURS_MARGE_PDT_LAITIER*24*60*60;
				if($auj<$limite) {$flag=1;$ProchLiv=date("d-M-Y",strtotime($DateLiv['Date']));break;}
			}
			mysql_close();?>
		</div>
		<?php if($flag==0) echo "  Vous n'avez plus la possibilité de modifier le dernier enregistrement.";
		else { ?>
		<div id="page_principale">
			<h3 class="mot_passe_recette" style="font-size: 16px; margin-left: 1px;margin-top: 5px; background-color: yellow;">
				AMAP "produits laitiers". Modifiez le contenu de votre panier.<br />
				Ces produits sont automatiquement reconduits d'une livraison à l'autre tant que vous ne revenez pas modifier ce choix par cette même procédure !!

			</h3>
			<form name="MForm">
			<table 	style="
					margin: 5px auto;
					border-collapse: collapse;
					border: 2px ridge white"
					>
				<caption style="
					margin: 5px auto;
					background-color: #DDDDFF;
					border: 2px ridge white;
					text-align: center;
					padding: 0px 0px 0px 10px;
					color: red;
					font-weight: bold"		
				>
				<?php
				echo 'Valeur de votre panier='.$VPunite.' unités soit '; echo 2*$donnee['Unite']; echo ' &euro;';
				echo ' -------------->>>>>>> pour la livraison du '.$ProchLiv;
				?></caption>
				<tr>
					<th 	style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC;
									color: #CCCCCC;">Enregistrer - Annuler
					</th>
					<th 	style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC">Désignation
					</th>
					<th 	style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC">Quantité choisie
					</th>
					<th 	style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC">Valeur en &euro;
					</th>
					<th 	style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC">Valeur en unité
					</th>
				</tr>
				<?php 
				$i=0;
				$j=0;
				$unite=0;?>
				<?php foreach($donnee as $cle => $element) { $i++; if($i % 2==0 && $i>8 && $i<44){ $j++;?>
				<tr>
				<?php if($j==1) { ?>
					<td style="	white-space:nowrap;
								text-align: center;
								padding: 2px 5px 2px 5px;
								border: 1px solid black;
								background-color: white;"
						rowspan="17">   <!-- cette colonne occupe toutes les 17 lignes du tableau--> 
						<button style="display: none;" onclick="document.location.href='index.php'" id="BtnAnnuler" name="BtnAnnuler" type="Button" class="BtnStd">Annuler</button><br /><br />
						<button style="display: none;" onclick="javascript:ConfirmeRecord(<?php echo $id; ?>)" id="BtnValider" name="BtnValider" type="Button" class="BtnStd">Enregistrer</button><br /><br /><br /><br />
						<button onclick="javascript:RemiseZero(<?php echo $VPunite; ?>)" name="BtnRAZ" type="Button" class="BtnStd">Mettre tout à zéro</button><br /><br />
						<button onclick="document.location.href='mailto:sodimoreau@sfr.fr?subject=AMAP produits laitiers'" name="BtnMail" type="Button" class="BtnStd">Ecrire au responsable</button><br />
						<?php echo "<br />Votre dernière modif<br />date du ".date("d-M-Y",strtotime($donnee['Date_modif']));; ?>
					</td>
				<?php } ?>
					<td style="	white-space:nowrap;
								text-align: center;
								padding: 2px 5px 2px 5px;
								border: 1px solid black;
								background-color: <?php if($j % 3==1) echo '#40a7f5'; elseif($j % 3==2) echo '#f9f580'; else echo 'white';?>"><?php echo $cle;?>
					</td>
					<td style="	white-space:nowrap;
								text-align: center;
								padding: 2px 5px 2px 5px;
								border: 1px solid black;
								background-color: <?php if($j % 3==1) echo '#40a7f5'; elseif($j % 3==2) echo '#f9f580'; else echo 'white';?>">
						<select onchange="javascript:MAJtableau('quantite_<?php echo $j; ?>', 'unite_<?php echo $j; ?>', 'prix_<?php echo $j; ?>', this.value, <?php echo $VPunite; ?>)" name="quantite_<?php echo $j; ?>" id="quantite_<?php echo $j; ?>">
							<?php if($element=='0') { ?><option value="0" selected="selected">0</option> <?php } else { ?><option value="0">0</option> <?php } ?>
							<?php if($element=='1') { ?><option value="1" selected="selected">1</option> <?php } else { ?><option value="1">1</option> <?php } ?>
							<?php if($element=='2') { ?><option value="2" selected="selected">2</option> <?php } else { ?><option value="2">2</option> <?php } ?>
							<?php if($element=='3') { ?><option value="3" selected="selected">3</option> <?php } else { ?><option value="3">3</option> <?php } ?>
							<?php if($element=='4') { ?><option value="4" selected="selected">4</option> <?php } else { ?><option value="4">4</option> <?php } ?>
							<?php if($element=='5') { ?><option value="5" selected="selected">5</option> <?php } else { ?><option value="5">5</option> <?php } ?>
							<?php if($element=='6') { ?><option value="6" selected="selected">6</option> <?php } else { ?><option value="6">6</option> <?php } ?>
							<?php if($element=='7') { ?><option value="7" selected="selected">7</option> <?php } else { ?><option value="7">7</option> <?php } ?>
							<?php if($element=='8') { ?><option value="8" selected="selected">8</option> <?php } else { ?><option value="8">8</option> <?php } ?>
							<?php if($element=='9') { ?><option value="9" selected="selected">9</option> <?php } else { ?><option value="9">9</option> <?php } ?>
							<?php if($element=='10') { ?><option value="10" selected="selected">10</option> <?php } else { ?><option value="10">10</option> <?php } ?>
						</select>
					</td>
					<td style="	white-space:nowrap;
								text-align: center;
								padding: 2px 5px 2px 5px;
								border: 1px solid black;
								background-color: <?php if($j % 3==1) echo '#40a7f5'; elseif($j % 3==2) echo '#f9f580'; else echo 'white';?>"
						id="prix_<?php echo $j; ?>"><?php if($i==30) echo $element*4; else echo $element*2; echo ' &euro;';  ?>
					</td>
					<td style="	white-space:nowrap;
								text-align: center;
								padding: 2px 5px 2px 5px;
								border: 1px solid black;
								background-color: <?php if($j % 3==1) echo '#40a7f5'; elseif($j % 3==2) echo '#f9f580'; else echo 'white';?>"
						id="unite_<?php echo $j; ?>"><?php if($i==30) {echo $element*2; $unite+=$element*2;} else {echo $element; $unite+=$element;} echo ' u'; ?>
					</td>
				</tr>
				<?php	} }?>
				<tr>
					<th id="message"
							style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC;
									color: red;
									font-weight: bold"
							colspan="2"></th>
					<th 	style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC">Total</th>
					<th 	id="total_prix"
							style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC"><?php echo $unite*2; echo ' &euro;';?>
					</th>
					<th 	id="total_unite"
							style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC"><?php echo $unite.' u'; ?>
					</th>
				</tr>
			</table>
			</form>
			<!--
			<h4 class="mot_passe_recette"><a style="cursor:pointer" href="mailto:sodimoreau@sfr.fr?subject=AMAP produits laitiers">Si problème : écrire au responsable</a></h4>
			-->
		</div>	
		<?php } ?>
		<div id="pied_page">
			<!-- <?php include_once("includes/pied_page.php") ?> -->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</p>
	</body>
</html>

<?php
}
if($ok==0) // non inscrit à l'amap
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
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
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
			<h3 class="mot_passe_recette">Vous n'êtes pas inscrit à l'AMAP produits laitiers</h3>
		</div>		
		<div id="pied_page">
			<!-- <?php include_once("includes/pied_page.php") ?> -->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</p>
	</body>
</html>
<?php } 
if($ok==-1) { //premier chargement du mot de passe
 ?>

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
			<?php include_once("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include_once("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php 
				include_once("includes/menu_gauche.php"); 
			?>
			<h3 class="mot_passe_recette">Il faut vous identifier pour accéder à ce service !!</h3>
		</div>		
		<div id="pied_page">
			<!-- <?php include_once("includes/pied_page.php") ?> -->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</p>
	</body>
</html> 
 <?php } ?>
