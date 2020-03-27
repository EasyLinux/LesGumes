<?php

//Principe de fonctionnement
//la table "amap_chevre_cde_en_cours" doit �tre cr��e en dupliquant le contenu de la table "amap_chevre_cde"
//dans cette table le champ "Date_modif" doit �tre remplac� par Date_livraison et initialis� � NULL

include_once("webmaster/define.php");

$ok=-1;/* identification non faite */
if (isset($_COOKIE['identification_amap'])) // Si la variable existe
{
	$ok=0;/* identification faite mais non inscrit � l'amap */
	mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
	mysql_select_db(base_de_donnees); // S�lection de la base 
	$id=$_COOKIE['identification_amap'];
	$question="SELECT * FROM amap_chevre_cde WHERE id='".$id."'";
	$reponse = mysql_query($question) or die(mysql_error());
  
  // champs de la table  amap_chevre_cde
  $fields = mysql_list_fields (base_de_donnees, 'amap_chevre_cde');
  
	if( mysql_num_rows($reponse)>0) {
		$ok=1;/* inscrit � l'amap modification possible */
		$commande = mysql_fetch_array($reponse);
    $firstField = 5;  // on passe id, Nom, Prenom, Unit� et dateModif
    $nbFields =  mysql_num_fields($reponse);  // nombre de champs de la table
    $nbProduits = $nbFields - $firstField; // nombre de produits
		$uniteCommande=$commande['Unite'];   
 
     //on recup�re le prix d'une unit� en base dans la table amap_produits_laitiers  - prix du contrat en cours identique pour tous les contrats !
  	$questionPrix="SELECT Prix_unite FROM amap_chevre WHERE id='".$commande['id']."'";
  	$reponsePrix = mysql_query($questionPrix) or die(mysql_error());
    $reponsePrix = mysql_fetch_array($reponsePrix) ;
    $prixUnite = $reponsePrix[0];
   
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

  objElemTotalUnite.innerHTML= "0 u";
  objElemTotalPrix.innerHTML= "0 &euro;";
  
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

function MAJtableau( idUnite, idPrix, quantite, nbUnite){
  var prixUnite = <?php echo $prixUnite; ?>;
  var uniteCommande = <?php echo $uniteCommande; ?>;

 	var objElemUnite=document.getElementById(idUnite);
	var objElemPrix=document.getElementById(idPrix);  
  var objElemTotalUnite=document.getElementById('total_unite');
	var objElemTotalPrix=document.getElementById('total_prix');
  var oldUnite = objElemUnite.innerHTML;
  var newUnite = quantite*nbUnite; 
   
 	objElemPrix.innerHTML= arrondir(prixUnite*newUnite).toString(); 
 	objElemUnite.innerHTML= newUnite.toString(); 
  var oldTotalUnite = parseInt(objElemTotalUnite.innerHTML);
  var newTotalUnite=  oldTotalUnite -  oldUnite +  newUnite;
  objElemTotalUnite.innerHTML = (newTotalUnite.toString()) + ' u';
  objElemTotalPrix.innerHTML =  arrondir(newTotalUnite*prixUnite).toString() + " &euro;";
  
  updateButtonsAndMessage(uniteCommande, newTotalUnite) ;
}

function updateButtonsAndMessage(uniteCommande, newTotalUnite) {
  // mise � jour des boutons et du message
  var strMsg="";
  var objElemMessage=document.getElementById('message');
  var objElemBtnValider=document.getElementById('BtnValider');
  var objElemBtnAnnuler=document.getElementById('BtnAnnuler');

  objElemBtnValider.style.display='none';
 
  if ( uniteCommande > newTotalUnite ) {
    strMsg +=  "Il faut ajouter ";
    strMsg +=   (uniteCommande -  newTotalUnite).toString()
    strMsg += " unit�(s)";    
  }
  else if ( uniteCommande < newTotalUnite )  {
    strMsg = "Il faut enlever ";
    strMsg +=   ( newTotalUnite-  uniteCommande).toString()
    strMsg += " unit�(s)";    
   }
  else  { // faire apparaitre les boutons de validation 
  	objElemBtnValider.style.display='inline';
  }
  objElemMessage.innerHTML= strMsg; 
  
}

function ConfirmeRecord(strID) {  
	var objForm = document.MForm ;
	var j=0;
	
	if (window.confirm("Ces produits seront automatiquement reconduits d'une livraison � l'autre\n\ntant que vous ne modifiez pas ce choix par cette m�me proc�dure !")) {
	
  	var objForm2 = createForm("ordermanager", "post", "maj_livraison_chevre.php");
		objForm2.appendChild(createHiddenInput("ID", strID));
    // ATTENTION A RESPECTER L'ORDRE  DES CHAMPS DE LA TABLE amap_chevre_cde
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
			
//mise � jour de la table_cde_en_cours par recopie de la table_cde
//cette mise � jour ne se fait que si     
//		[Date de la table cde_en_cours] < DateAujourd'hui
//      et si      [datelimite] <= [date_aujourd'hui] <= [dateProchaineLivraison]

// POUR LA MISE EN ROUTE, AVANT LA 1RE LIVRAISON, METTRE UNE DATE POUR date de livraison DANS amap_brebis_cde_en_cours QUI SOIT
//      ANTERIEURE A LA DATE DU JOUR. AINSI LE PRODUCTEUR POURRA VIVUALISER LA TABLE amap_brebis_cde POUR INFORMATION.
// QUAND LA DATE DE LA 1RE LIVRAISON - 9j SERA ATTEINTE LA TABLE amap_brebis_cde_en_cours (QUI CONTIENT N'IMPORTE QUOI)
//		SE METTRA A JOUR ET LE CYCLE NORMAL	DE FONCTIONNEMENT SE METTRA EN ROUTE


//cette partie de programme se trouve aussi dans l'acc�s � la liste des produits r�serv�e au producteur
//si bien que c'est le premier acc�s d'un amapien ou le premier acces du producteur apr�s la date limite qui provoque
//la mise � jour de la table cde_en_cours 
		mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
		mysql_select_db(base_de_donnees); // S�lection de la base 
		$question="SELECT * FROM amap_chevre_permanences WHERE Distribution=1 ORDER BY Date";
		$tabPermanence = mysql_query($question);
		$question="SELECT Date_livraison FROM amap_chevre_cde_en_cours";
		$reponse2 = mysql_query($question) or die(mysql_error());
		$TableDateLiv=mysql_fetch_array($reponse2);
		$DateLivEnCours=strtotime($TableDateLiv[0]);
		$auj=time();
		$flag=0; //la date dans cde_en_cours est sup � la date d'aujourd'hui
		//on �crase la table cde_en_cours que si il existe une livraison plus loin que la date d'aujourd'hui et � moins de 9 jours
		if(  $DateLivEnCours==NULL || $auj>$DateLivEnCours ) { 
			$flag=-1;//impossible imprimer les amapiens peuvent encore modifier leur choix
			while($DateLiv = mysql_fetch_array($tabPermanence)){
				$temp=strtotime($DateLiv['Date']);
				$limite=$temp-JOURS_MARGE_CHEVRE*24*60*60;
				if($auj>=$limite && $auj<=$temp) { 
					$flag=1;
					$LaDate=$DateLiv['Date'];
					break;
				}
			}
		}
		if($flag==1) {
			$question="TRUNCATE TABLE amap_chevre_cde_en_cours";
			$reponse=mysql_query($question) or die(mysql_error());;
			$question="INSERT INTO amap_chevre_cde_en_cours SELECT * FROM amap_chevre_cde";
			$reponse=mysql_query($question);
			$question="UPDATE amap_chevre_cde_en_cours SET Date_livraison='".$LaDate."'";
			$reponse=mysql_query($question) or die(mysql_error());;
		}

//fin mise � jour table_cde_en_cours
		
    	
			$question="SELECT * FROM amap_chevre_permanences order BY Date";
			$tabPermanence = mysql_query($question) or die(mysql_error());;
			$question="SELECT Date_livraison FROM amap_chevre_cde_en_cours";
			$reponse2 = mysql_query($question) or die(mysql_error());;
			$TableDateLiv=mysql_fetch_array($reponse2);
			$DateLivEnCours=strtotime($TableDateLiv[0]);
			$auj=time();
			$flag=0;
      
			//il faut trouver une date de livraison telle que aujourd'hui < � cette date - JOURS_MARGE_CHEVRE jours
			while($DateLiv = mysql_fetch_array($tabPermanence)) if($DateLiv['Distribution']=='1'){
				$temp=strtotime($DateLiv['Date']);
				$limite=$temp-JOURS_MARGE_CHEVRE*24*60*60;
				if($auj<$limite) {
          $flag=1;
          $ProchLiv=date("d-M-Y",strtotime($DateLiv['Date']));
          break;
        }
			}
      
      //  les prix des produits class�s par id
      $result=mysql_query("SELECT Unite FROM amap_chevre_produits ORDER BY Id") or die(mysql_error());;
      $nbProduit = mysql_num_rows($result);    
      for( $i=0; $i <$nbProduit; $i++) {
        $inter = mysql_fetch_array( $result);
        $uniteProduits[$i] =$inter["Unite"];
      }      
        
			mysql_close();?>
		</div>
    
		<?php if($flag==0) echo "  Vous n'avez plus la possibilit� de modifier le dernier enregistrement.";
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
	   			echo 'Commande de fromages de ch�vre pour la livraison du '.$ProchLiv;
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
									background-color: #CCCCCC">D�signation
					</th>
					<th 	style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC">Quantit� choisie
					</th>
          
				  <th 	style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC">Total en unit�
					</th>
          <th 	style="	text-align: center;
									padding: 2px 5px 2px 5px;
									border: 2px ridge white;
									background-color: #CCCCCC">Valeur en &euro;
					</th>
          
					
				</tr>
        
        <!-- premi�re colonne sur 1 cellule -->
        <tr>
          	<td style="	white-space:nowrap;
								text-align: center;
								padding: 2px 5px 2px 5px;
								border: 1px solid black;
								background-color: white;"
						rowspan="30">
					 <?php 
                echo 'votre panier est de '.$uniteCommande.' unit�s<br />soit '; echo $prixUnite*$commande['Unite']; echo ' &euro;<br /><br />';     ?>

						<button style="display: none;" onclick="javascript:ConfirmeRecord(<?php echo $id; ?>)" id="BtnValider" name="BtnValider" type="Button" class="BtnStd">Enregistrer</button><br /><br /><br /><br />
						<button onclick="document.location.href='index.php'" id="BtnAnnuler" name="BtnAnnuler" type="Button" class="BtnStd">Annuler</button><br /><br />
						<button onclick="javascript:RemiseZero(<?php echo '4';?>)" name="BtnRAZ" type="Button" class="BtnStd">Mettre tout � z�ro</button><br /><br />
						<button onclick="document.location.href='mailto:sodimoreau@sfr.fr?subject=AMAP chevre'" name="BtnMail" type="Button" class="BtnStd">Ecrire au responsable</button><br />
						<?php 
              echo "<br />Votre derni�re modif<br />date du ".date("d-M-Y",strtotime($commande['Date_modif']));?>
					</td>
          
				<?php

        // it�ration sur les produits  : l'ordre des champs de la table amap_chevre_cde doit correspondre � 
        // l'ordre d�finit par le champ Id de la table amap_chevre_produit
        // On se servira de cela pour retrouver les prix des produits ...
        $j=-1;  
        $uniteCde = 0;             
		for ($i=$firstField; $i< $firstField + $nbProduits; $i++) {
            $j++; 
			if ($j==10) continue;
            if ( $j<5) { $color= '#40a7f5'; }
            else if ( $j <10) { $color= '#f9f580';}
            else if ( $j <11) { $color= '#f9f580';}
            else  { $color= '#40a7f5';}
             
            $element = $commande[$i]; 
               
            if ( $j!=0)  { ?>  <tr>  <?php } 
              // le premier <tr> est fait avant le for ... 
            ?>  
				
            
            <td style="	white-space:nowrap;
            			text-align: center;
            			padding: 2px 5px 2px 5px;
            			border: 1px solid black;
            			background-color: <?php echo $color; ?>">
            	    <?php    $str = mysql_field_name($fields,$i);
                           $str = str_replace( "_", " ", $str);
                           echo  $str; ?>  
            </td>
            <td style="	white-space:nowrap;
    								text-align: center;
    								padding: 2px 5px 2px 5px;
    								border: 1px solid black;
    								background-color:<?php echo $color; ?>">
    						<select onchange="javascript:MAJtableau('unite_<?php echo $j; ?>', 'prix_<?php echo $j; ?>', this.value, <?php  echo $uniteProduits[$j]?>)" name="quantite_<?php echo $j; ?>" id="quantite_<?php echo $j; ?>">
    							<?php if($element=='0') { ?><option value="0" selected="selected">0</option> <?php } else { ?><option value="0">0</option> <?php } ?>
    							<?php if($element=='1') { ?><option value="1" selected="selected">1</option> <?php } else { ?><option value="1">1</option> <?php } ?>
    							<?php if($element=='2') { ?><option value="2" selected="selected">2</option> <?php } else { ?><option value="2">2</option> <?php } ?>
    							<?php if($element=='3') { ?><option value="3" selected="selected">3</option> <?php } else { ?><option value="3">3</option> <?php } ?>
    							<?php if($element=='4') { ?><option value="4" selected="selected">4</option> <?php } else { ?><option value="4">4</option> <?php } ?>
    							<?php if($element=='5') { ?><option value="5" selected="selected">5</option> <?php } else { ?><option value="5">5</option> <?php } ?>
    							<?php if($element=='6') { ?><option value="6" selected="selected">6</option> <?php } else { ?><option value="6">6</option> <?php } ?>
    							<?php if($element=='7') { ?><option value="7" selected="selected">7</option> <?php } else { ?><option value="7">7</option> <?php } ?>
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
        
        <!-- derni�re ligne de total -->
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
			</table>
			</form>
		
		</div>	
		<?php } ?> 
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
if($ok==0) // non inscrit � l'amap
{
//***********************************************************************
// On affiche la zone de texte pour rentrer de nouveau le mot de passe.
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
			<h3 class="mot_passe_recette">Vous n'�tes pas inscrit � l'AMAP fromage de ch�vre</h3>
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
 <?php } ?>
