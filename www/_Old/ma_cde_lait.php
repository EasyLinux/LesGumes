<?php

//Principe de fonctionnement
//la table "amap_produits_laitiers_cde_en_cours" doit être créée en dupliquant le contenu de la table "amap_produits_laitiers_cde"
//dans cette table le champ "Date_modif" doit être remplacé par Date_livraison et initialisé à NULL


include_once("webmaster/define.php");
$ok=-1;/* identification non faite */
if (isset($_COOKIE['identification_amap'])) // Si la variable existe
{
	$ok=0;/* identification faite mais non inscrit à l'amap */
	mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysqli_select_db(base_de_donnees); // Sélection de la base 
	$id=$_COOKIE['identification_amap'];
	$question="SELECT * FROM amap_produits_laitiers_cde WHERE id='".$id."'";
	$reponse = mysqli_query($question) or die(mysqli_error());
  
  // champs de la table  amap_produits_laitiers_cde
  $fields = mysqli_list_fields (base_de_donnees, 'amap_produits_laitiers_cde');
  
	if( mysqli_num_rows($reponse)>0) {
		$ok=1;/* inscrit à l'amap modification possible */
		$commande = mysqli_fetch_array($reponse);
    $firstField = 5;  // on passe id, Nom, Prenom, Unité et dateModif
    $nbFields =  mysqli_num_fields($reponse);  // nombre de champs de la table
    $nbProduits = $nbFields - $firstField; // nombre de produits
		$uniteCommande=$commande['Unite'];   
   
      //on recupère le prix d'une unité en base dans la table amap_produits_laitiers  - prix du contrat en cours identique pour tous les contrats !
  	$questionPrix="SELECT Prix_unite FROM amap_produits_laitiers WHERE id='".$id."'";
    	$reponsePrix = mysqli_query($questionPrix) or die(mysqli_error());
    $reponsePrix = mysqli_fetch_array($reponsePrix) ;
    $prixUnite = $reponsePrix[0];
   
   
	}
	mysqli_close(); // Déconnexion de MySQL
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
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>
	<body>
	
<script language="javascript">

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
  var nbProduits = <?php echo $nbProduits; ?>;
  var uniteCommande = <?php echo $uniteCommande; ?>;

	var objForm = document.MForm ;
	var objElemTotalUnite=document.getElementById('total_unite');
	var objElemTotalPrix=document.getElementById('total_prix');
	var objElemTotalUniteBeurre=document.getElementById('total_unite_beurre');

  objElemTotalUnite.innerHTML= "0 u";
  objElemTotalPrix.innerHTML= "0 &euro;";
  objElemTotalUniteBeurre.innerHTML= "0 u";
  
	for ( var i=0; i< objForm.elements.length;i++) {
		var e = objForm.elements[i] ;
		if (e.type=='select-one') {
			e.selectedIndex="0";
		}
	}		
	
	for(var i=0; i< nbProduits; i++) {
		var objElemPrix=document.getElementById('prix_'+i);
		var objElemUnite=document.getElementById('unite_'+i);
		objElemUnite.innerHTML='0';
		objElemPrix.innerHTML='0';
	}
  
  updateButtonsAndMessage(uniteCommande, 0) ;
}

function arrondir(valeur){
  return Math.round( valeur * 100) /100;
}

function MAJtableau( idDesc, idUnite, idPrix, quantite, nbUnite){
	var prixUnite = <?php echo $prixUnite; ?>;
	var uniteCommande = <?php echo $uniteCommande; ?>;
	var objElemUnite=document.getElementById(idUnite);
	var objElemPrix=document.getElementById(idPrix);  
	var objElemDesc=document.getElementById(idDesc);
	var objElemTotalUnite=document.getElementById('total_unite');
	var objElemTotalPrix=document.getElementById('total_prix');
	var objElemTotalUniteBeurre=document.getElementById('total_unite_beurre');
	var oldUnite = objElemUnite.innerHTML;
	var newUnite = quantite*nbUnite; 

	objElemPrix.innerHTML= arrondir(prixUnite*newUnite).toString(); 
	objElemUnite.innerHTML= newUnite.toString(); 
	var oldTotalUnite = parseInt(objElemTotalUnite.innerHTML);
	var newTotalUnite=  oldTotalUnite -  oldUnite +  newUnite;
	objElemTotalUnite.innerHTML = (newTotalUnite.toString()) + ' u';
	objElemTotalPrix.innerHTML =  arrondir(newTotalUnite*prixUnite).toString() + " &euro;";
	var newTotalUniteBeurre=0;
	//	alert("*"+objElemDesc.innerHTML.toString().trim()+"*"); 
	var oldTotalUniteBeurre = parseInt(objElemTotalUniteBeurre.innerHTML);
	if ((objElemDesc.innerHTML.toString().trim()=="Beurre 160g sale") || (objElemDesc.innerHTML.toString().trim()=="Beurre 160g doux")) {
		newTotalUniteBeurre=  oldTotalUniteBeurre -  oldUnite +  newUnite;
		objElemTotalUniteBeurre.innerHTML = (newTotalUniteBeurre.toString()) + ' u';
	} else { newTotalUniteBeurre = oldTotalUniteBeurre; }

	updateButtonsAndMessage(uniteCommande, newTotalUnite, newTotalUniteBeurre) ;
}

function updateButtonsAndMessage(uniteCommande, newTotalUnite, newTotalUniteBeurre) {
  // mise à jour des boutons et du message
  var strMsg="";
  var strMsgBeurre="";
  var objElemMessage=document.getElementById('message');
  var objElemMessageBeurre=document.getElementById('message_beurre');
  var objElemBtnValider=document.getElementById('BtnValider');
  var objElemBtnAnnuler=document.getElementById('BtnAnnuler');
  var isOK = false;
  
  objElemBtnValider.style.display='none';
 
  if ( uniteCommande > newTotalUnite ) {
    strMsg +=  "Il faut ajouter ";
    strMsg +=   (uniteCommande -  newTotalUnite).toString()
    strMsg += " unité(s)";    
  }
  else if ( uniteCommande < newTotalUnite )  {
    strMsg = "Il faut enlever ";
    strMsg +=   ( newTotalUnite-  uniteCommande).toString()
    strMsg += " unité(s)";    
   }
  else  { // faire apparaitre les boutons de validation 
  	isOK = true;
  }
  if (newTotalUniteBeurre >0) {
	  // règle : pas d'unité de beurre pour 3u. 1u pour 4u, puis moitié des unités au delà
	  var max = 0;
	  if (uniteCommande<=3) { max = 0; }
	  else if (uniteCommande==4) { max = 1; }
	  else { max = Math.trunc(uniteCommande/2); }
	  if (newTotalUniteBeurre>max) {
		strMsgBeurre = "Il faut enlever ";
		strMsgBeurre +=   (newTotalUniteBeurre- max).toString()
		strMsgBeurre += " unité(s) de beurre";  
		isOK = false;		
	}
  }
  if (isOK) {
	objElemBtnValider.style.display='inline';
  }
  objElemMessage.innerHTML= strMsg; 
  objElemMessageBeurre.innerHTML = strMsgBeurre;   
}

function ConfirmeRecord(strID) {  
	var objForm = document.MForm ;
	var j=0;
	
	if (window.confirm("Ces produits seront automatiquement reconduits d'une livraison à l'autre\n\ntant que vous ne modifiez pas ce choix par cette même procédure !")) {
	
  	var objForm2 = createForm("ordermanager", "post", "maj_livraison_prod_lait.php");
		objForm2.appendChild(createHiddenInput("ID", strID));
    // ATTENTION A RESPECTER L'ORDRE  DES CHAMPS DE LA TABLE amap_produits_laitiers_cde
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

// POUR LA MISE EN ROUTE, AVANT LA 1RE LIVRAISON, METTRE UNE DATE POUR date de livraison DANS amap_brebis_cde_en_cours QUI SOIT
//      ANTERIEURE A LA DATE DU JOUR. AINSI LE PRODUCTEUR POURRA VIVUALISER LA TABLE amap_brebis_cde POUR INFORMATION.
// QUAND LA DATE DE LA 1RE LIVRAISON - 9j SERA ATTEINTE LA TABLE amap_brebis_cde_en_cours (QUI CONTIENT N'IMPORTE QUOI)
//		SE METTRA A JOUR ET LE CYCLE NORMAL	DE FONCTIONNEMENT SE METTRA EN ROUTE


//cette partie de programme se trouve aussi dans l'accès à la liste des produits réservée au producteur
//si bien que c'est le premier accès d'un amapien ou le premier acces du producteur après la date limite qui provoque
//la mise à jour de la table cde_en_cours 
		mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
		mysqli_select_db(base_de_donnees); // Sélection de la base 
		$question="SELECT * FROM amap_produits_laitiers_permanences WHERE Distribution=1 ORDER BY Date";
		$tabPermanence = mysqli_query($question);
		$question="SELECT Date_livraison FROM amap_produits_laitiers_cde_en_cours";
		$reponse2 = mysqli_query($question) or die(mysqli_error());
		$TableDateLiv=mysqli_fetch_array($reponse2);
		$DateLivEnCours=strtotime($TableDateLiv[0]);
 		$auj=time();  
          
		$flag=0; //la date dans cde_en_cours est sup à la date d'aujourd'hui
		//on écrase la table cde_en_cours que si il existe une livraison plus loin que la date d'aujourd'hui et à moins de 9 jours
		if( $DateLivEnCours==NULL || $auj>$DateLivEnCours ) { 
 			$flag=-1;//impossible imprimer les amapiens peuvent encore modifier leur choix
			while($DateLiv = mysqli_fetch_array($tabPermanence)){
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
			$reponse=mysqli_query($question) or die(mysqli_error());
			$question="INSERT INTO amap_produits_laitiers_cde_en_cours SELECT * FROM amap_produits_laitiers_cde";
			$reponse=mysqli_query($question);
			$question="UPDATE amap_produits_laitiers_cde_en_cours SET Date_livraison='".$LaDate."'";
			$reponse=mysqli_query($question) or die(mysqli_error());
		}

//fin mise à jour table_cde_en_cours
		
    	
			$question="SELECT * FROM amap_produits_laitiers_permanences order BY Date";
			$tabPermanence = mysqli_query($question) or die(mysqli_error());;
			$question="SELECT Date_livraison FROM amap_produits_laitiers_cde_en_cours";
			$reponse2 = mysqli_query($question) or die(mysqli_error());;
			$TableDateLiv=mysqli_fetch_array($reponse2);
			$DateLivEnCours=strtotime($TableDateLiv[0]);
			$auj=time();
			$flag=0;
      
			//il faut trouver une date de livraison telle que aujourd'hui < à cette date - JOURS_MARGE_PDT_LAITIER jours
			while($DateLiv = mysqli_fetch_array($tabPermanence)) if($DateLiv['Distribution']=='1'){
				$temp=strtotime($DateLiv['Date']);
				$limite=$temp-JOURS_MARGE_PDT_LAITIER*24*60*60;
				if($auj<$limite) {$flag=1;$ProchLiv=date("d-M-Y",strtotime($DateLiv['Date']));break;}
			}
      
      //  les prix des produits classés par id
      $result=mysqli_query("SELECT Unite FROM amap_produits_laitiers_produits ORDER BY Id") or die(mysqli_error());;
      $nbProduit = mysqli_num_rows($result);    
      for( $i=0; $i <$nbProduit; $i++) {
        $inter = mysqli_fetch_array( $result);
        $uniteProduits[$i] =$inter["Unite"];
      }      
        
			mysqli_close();?>
		</div>
    
		<?php if($flag==0) echo "  Vous n'avez plus la possibilité de modifier le dernier enregistrement.";
		else { ?>
		<div id="page_principale">
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
	   			echo 'Commande de la ferme de Rublé pour la livraison du '.$ProchLiv;
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
									background-color: #CCCCCC">Total en unité
					</th>
          <th 	style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC">Valeur en &euro;
					</th>
          
					
				</tr>
        
        <!-- première colonne sur 1 cellule -->
        <tr>
          	<td style="	white-space:nowrap;
								text-align: center;
								padding: 2px 5px 2px 5px;
								border: 1px solid black;
								background-color: white;"
						rowspan="30">
					 <?php 
                echo 'votre panier est de '.$uniteCommande.' unités<br />soit '; echo $prixUnite*$commande['Unite']; echo ' &euro;<br /><br />';     ?>

						<button style="display: none;" onclick="javascript:ConfirmeRecord(<?php echo $id; ?>)" id="BtnValider" name="BtnValider" type="Button" class="BtnStd">Enregistrer</button><br /><br /><br /><br />
						<button onclick="document.location.href='index.php'" id="BtnAnnuler" name="BtnAnnuler" type="Button" class="BtnStd">Annuler</button><br /><br />
						<button onclick="javascript:RemiseZero(<?php echo '4';?>)" name="BtnRAZ" type="Button" class="BtnStd">Mettre tout à zéro</button><br /><br />
						<button onclick="document.location.href='mailto:sodimoreau@free.fr?subject=AMAP Produits laitiers'" name="BtnMail" type="Button" class="BtnStd">Ecrire au responsable</button><br />
						<?php 
              echo "<br />Votre dernière modif<br />date du ".date("d-M-Y",strtotime($commande['Date_modif']));?>
					</td>
          
				<?php

        // itération sur les produits  : l'ordre des champs de la table amap_produits_laitiers_cde doit correspondre à 
        // l'ordre définit par le champ Id de la table amap_produits_laitiers_produit
        // On se servira de cela pour retrouver les prix des produits ...
        $j=-1;  
        $uniteCde = 0; 
		$uniteBeurre = 0; 
		
		for ($i=$firstField; $i< $firstField + $nbProduits; $i++) {
            $j++; 
			
			if ( ! isset( $color ) ) $color = '#40a7f5';
			
            if ( $color== '#f9f580') 
              { $color= '#40a7f5';}
            else {$color= '#f9f580';}
             
            $element = $commande[$i]; 
               
            if ( $j!=0)  { ?>  <tr>  <?php } 
              // le premier <tr> est fait avant le for ... 
            ?>  
				
            
            <td style="	white-space:nowrap;
            			text-align: center;
            			padding: 2px 5px 2px 5px;
            			border: 1px solid black;
            			background-color: <?php echo $color; ?>"
						id="desc_<?php echo $j; ?>">
            	    <?php    $str = mysqli_field_name($fields,$i);
                           $str = str_replace( "_", " ", $str);
                           echo  $str; 
					?>  
            </td>
            <td style="	white-space:nowrap;
    								text-align: center;
    								padding: 2px 5px 2px 5px;
    								border: 1px solid black;
    								background-color:<?php echo $color; ?>">
    						<select onchange="javascript:MAJtableau('desc_<?php echo $j; ?>', 'unite_<?php echo $j; ?>', 'prix_<?php echo $j; ?>', this.value, <?php  echo $uniteProduits[$j]?>)" name="quantite_<?php echo $j; ?>" id="quantite_<?php echo $j; ?>">
    							<?php if($element=='0') { ?><option value="0" selected="selected">0</option> <?php } else { ?><option value="0">0</option> <?php } ?>
    							<?php if($element=='1') { ?><option value="1" selected="selected">1</option> <?php } else { ?><option value="1">1</option> <?php } ?>
    							<?php if($element=='2') { ?><option value="2" selected="selected">2</option> <?php } else { ?><option value="2">2</option> <?php } ?>
    							<?php if($element=='3') { ?><option value="3" selected="selected">3</option> <?php } else { ?><option value="3">3</option> <?php } ?>
    							<?php if($element=='4') { ?><option value="4" selected="selected">4</option> <?php } else { ?><option value="4">4</option> <?php } ?>
    							<?php if($element=='5') { ?><option value="5" selected="selected">5</option> <?php } else { ?><option value="5">5</option> <?php } ?>
    						</select>
    					</td>
              <td style="	white-space:nowrap;
  								text-align: center;
  								padding: 2px 5px 2px 5px;
  								border: 1px solid black;
  								background-color: <?php echo $color; ?>"
  						    id="unite_<?php echo $j; ?>">
    						<?php
                  $uniteLigne = $uniteProduits[$j]*$element;
    							 echo $uniteLigne;    					
                   $uniteCde +=  $uniteLigne; 
				   if (($str=="Beurre 160g sale") or ($str=="Beurre 160g doux")) {
				   $uniteBeurre +=  $uniteLigne; }

                 ?> 
  					</td>
            
    					<td style="	white-space:nowrap;
    								text-align: center;
    								padding: 2px 5px 2px 5px;
    								border: 1px solid black;
    								background-color:<?php echo $color; ?>"
    						    id="prix_<?php echo $j; ?>">
    						<?php                 
    							echo $uniteLigne*$prixUnite;              
     						?>
  					</td>
  				
  			  </tr>
				<?php	}?>
        
        <!-- dernière ligne de total -->
				<tr>
					<th id="message"
							style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC;
									color: red;
									font-weight: bold"
					</th>
					<th 	style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC">Total commande :
          </th>              			
          <th id="total_unite"
							style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC"><?php echo $uniteCde; ?> u
					</th>
          <th 	id="total_prix"
							style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC">
                  <?php echo $uniteCde*$prixUnite;?> &euro;
          </th>
					
				</tr>
				<tr>
					<th id="message_beurre"
							style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC;
									color: red;
									font-weight: bold"
					</th>
					<th 	style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC">dont beurre :
          </th>              			
          <th id="total_unite_beurre"
							style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC"><?php echo $uniteBeurre; ?> u
					</th>
          <th style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC"></th>
					
				</tr>
				
			</table>
			</form>
		
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
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
