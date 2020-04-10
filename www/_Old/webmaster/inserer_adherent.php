<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style_webmaster.css" />
<script>
   function isNumber(id)
  {
     var x=document.getElementById(id);
     if ( isNaN(x.value)) {
        alert ("Montant erroné");
        return false;
       }
       return true;
  }
 
   function arrondir(valeur){      
      return Math.round( valeur * 100) /100;
   }

 
  function ChangeNbLiv( id, idMin, idMax, nbLigne ){
	//on récupère la valeur à duppliquer sur les autres lignes

	var objLiv= document.getElementById("nbLiv"+id);	
    var nbLiv = parseInt(objLiv.value);
    for (i=idMin; i <= idMax; i++) {
		// mise à jour des champs nbLiv et des totaux par ligne pour toutes les lignes de 
		// idMin à idMax qui changent et pour la ligne courante
		var objLivCible= document.getElementById( "nbLiv"+i);
		var objQuantite= document.getElementById( "nb"+i);
		var objElemTotal= document.getElementById("total"+i); 
		var objPrixUnitaire= document.getElementById("prix"+i);		
		if ( i== id || parseInt(objLivCible.value) != nbLiv) {
			objLivCible.value= nbLiv;   
			var quantite = parseInt(objQuantite.value);
			var prixUnitaire = parseFloat(objPrixUnitaire.innerHTML);
			objElemTotal.innerHTML= arrondir(prixUnitaire*nbLiv*quantite);   		
		}
	}
	   
	// total des prix par ligne à mettre à jour dans le champs d'id "total"
     total =0;
     for (i=1; i <= nbLigne; i++) {
           var str = 'total'+ i;
          var totalLigne = document.getElementById(str).innerHTML;
          total += parseFloat(totalLigne);
     }
     document.getElementById("total").innerHTML = total;
  }
  
   function ajouteSupplement( idSupplement, idTotal, nbLigne){
    var idSupplement= document.getElementById(idSupplement);
    var objElemTotal= document.getElementById(idTotal); 
    
    var supplement = arrondir(parseFloat(idSupplement.value));
	idSupplement.value= supplement;
    objElemTotal.innerHTML= supplement;   
    
     // total des prix par ligne à mettre à jour dans le champs d'id "total"
     total =0;
     for (i=1; i <= nbLigne; i++) {
           var str = 'total'+ i;
          var totalLigne = document.getElementById(str).innerHTML;
          total += parseFloat(totalLigne);
     }
     document.getElementById("total").innerHTML = total;
  }
 
  function calculPrixLigne( idPrix, idQuantite, idNbLiv, idTotal, nbLigne){
    var objQuantite= document.getElementById(idQuantite);
    var objLiv= document.getElementById(idNbLiv);
    var objElemTotal= document.getElementById(idTotal); 
    var objPrixUnitaire= document.getElementById(idPrix);
    
    var quantite = parseInt(objQuantite.value);
    var nbLiv = parseInt(objLiv.value);
    var prixUnitaire = parseFloat(objPrixUnitaire.innerHTML);
    objElemTotal.innerHTML= arrondir(prixUnitaire*nbLiv*quantite);   
    
     // total des prix par ligne à mettre à jour dans le champs d'id "total"
     total =0;
     for (i=1; i <= nbLigne; i++) {
           var str = 'total'+ i;
          var totalLigne = document.getElementById(str).innerHTML;
          total += parseFloat(totalLigne);
     }
     document.getElementById("total").innerHTML = total;
  }
</script>

</head>

<body>
	<?php
	include_once("define.php"); 
	mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysqli_select_db(base_de_donnees); // Sélection de la base 
	$question="SELECT * FROM ".$_GET['amap']." WHERE id=".$_GET['id'];
	$reponse=mysqli_query( $question);
	if(mysqli_num_rows($reponse)==0) {
		$question="SELECT Nom, Prenom, e_mail FROM amap_generale WHERE id=".$_GET['id'];
		$reponse=mysqli_query( $question) or die(mysqli_error());
		$donnees=mysqli_fetch_array($reponse);
		$flag=0;
		
		// données globales aux contrats dans liste_amap
		$questionContrat= "SELECT * FROM liste_amap WHERE  Table_amap='".$_GET['amap']."'";
		$reponse2 = mysqli_query( $questionContrat) or die(mysqli_error());
		$infoContrat = mysqli_fetch_array( $reponse2);
		$datedeb= $infoContrat['Date_deb'];
		$datefin= $infoContrat['Date_fin'];
		$nbLivMax = $infoContrat['Nb_livraison'];
		// bornage pour éviter de planter si la base est mal renseignée
		if ( $nbLivMax < 1 )  $nbLivMax = 1;

		$max =  5;      // faut-il mettre un max par produits ?
		if ( $nbLivMax < 1 )  $nbLivMax = 1;    // par sécurité si la base est mal remplie

		// données globales aux contrats dans liste_amap
		$nomTableProduit = $infoContrat['Table_produit'];
		$questionProduits= "SELECT * FROM ".$nomTableProduit." Order by id";

		}
		else { $flag=1;}

		if ($flag==0) { 
		?>
	
	<form action="inserer.php" method="get" >
	<input type="hidden" name="amap" value="<?php echo $_GET['amap']; ?>" />
	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />

	<table>
		<tr><th> 
			<?php echo $donnees["Prenom"].' '.$donnees["Nom"]; ?>
		</th> </tr>
		<tr><td>  
			<table> 
			<tr>
				<th> Début du contrat </th>
				<th> Fin du contrat </th>
				<th> Paiement </th>
				<th> Nbre chèques </th>  
			</tr>
			<tr>   
				<td> <input type="date" id="datedeb" name="datedeb" value="<?php echo $datedeb;?>"/>  </td>
				<td> <input type="date" id="datefin" name="datefin" value="<?php echo $datefin;?>"/>  </td> 
				<td> <input type="date" id="datepaiement"name="datepaiement" value="<?php echo $datedeb;?>"/>  </td>
				<td> <input type="number" id="nbrecheque" name="nbrecheque" min="0" max="12" value="1"/>   </td>
			</tr>
			</table>
		</td></tr>

		<!-- les lignes suivantes dépendent de l'amap en question ... -->
		<?php switch ($_GET['amap']) {
		case 'amap_legumes': ?>
			<tr><td>				
				<label for="nbpanier">Nombre de panier par distribution: </label>
				<input type="number" id="nbpanier" name="nbpanier" min="1" max="<?php echo $max; ?>" value="1"/>
			</td></tr>
			<tr><td>
				<label for="nblivraison">Nombre de livraisons : </label>
				<input type="number" id="nblivraison" name="nblivraison" min="1" max="<?php echo $nbLivMax; ?>" value="<?php echo $nbLivMax; ?>"/>
			</td></tr>	
			<?php break;

		case 'amap_chevre':?>
			<tr><td>
			<table border='2'> 
				<tr>
					<th>Produits</th>
					<th>Prix unitaire</th>
					<th>Quantité/livraison</th>
					<th>Nbre de livraison</th>          
					<th>Prix (euros)</th>
				</tr>
							  
				<?php   
				//il faut refaire la requête pour parcourir tous les produits
				$produits = mysqli_query( $questionProduits);
				$nbLigne = mysqli_num_rows($produits);
				$totalContrat =0;
				$idMin = 1; $idMax= 9; // les lignes dont l'id est compris entre idMin et idMax doivent avoir le même nombre de livraison 
				while ($produitChevre = mysqli_fetch_array($produits) ) {	
					$id = $produitChevre['id'];
					$produit = $produitChevre['Nom_produit'];
					$maxParLivraison = $produitChevre['Max_par_livraison'];
					$prixUnitaire = $produitChevre['Prix'];						   
					$idQuantiteProduit="nb".$id;
					$idNbLivraison= "nbLiv".$id;
					$idPrix = "prix".$id;
					$idTotalLigne = "total".$id;
					$totalLigne = 0;
					$totalContrat = 0;
					
					?>
					<tr>
						<td> <label id="<?php echo $produit; ?>"> <?php echo $produit; ?></label></td>
						<?php if ($id >= $idMin && $id <= $idMax) { ?>
						<td>  <center>  
						  <label id="<?php echo $idPrix; ?>"> <?php echo $prixUnitaire; ?></label>
						  <input type="hidden" name="<?php echo $idPrix; ?>" value="<?php echo $prixUnitaire; ?>"/>
						</center></td>
						<td> <center>
						<input type="number" id="<?php echo $idQuantiteProduit;?>" name="<?php echo $idQuantiteProduit; ?>"
							   min="0" max="<?php echo $maxParLivraison; ?>" 
							  onchange='javascript:calculPrixLigne( "<?php echo $idPrix;?>", "<?php echo $idQuantiteProduit;?>", "<?php echo $idNbLivraison;?>", "<?php echo $idTotalLigne;?>",<?php echo $nbLigne;?>)'
							   value="0"/>               
						</center> </td>
						<td> <center> 
						<input type="number" id="<?php echo $idNbLivraison; ?>" name="<?php echo $idNbLivraison; ?>"
							   min="0" max="<?php echo $nbLivMax; ?>" 
							   onchange='javascript:ChangeNbLiv( <?php echo $id;?>, <?php echo $idMin;?>, <?php echo $idMax;?>, <?php echo $nbLigne;?> )'
							   value="<?php echo $nbLivMax; ?>"/> 
						</center> </td>
						<?php } else { // il faut gérer à part le supplément chèvre : un nombre décimal sous forme de montant ##,##
						?>
						<td> </td>
						<td>  <center> 
						<input type="text" id="<?php echo $idQuantiteProduit;?>" name="<?php echo $idQuantiteProduit; ?>"
							  onchange='javascript:ajouteSupplement( "<?php echo $idQuantiteProduit;?>", "<?php echo $idTotalLigne;?>",<?php echo $nbLigne;?>)'
							  value="0"/> 
						</center> </td>
						<td> </td>						
						<?php } ?>
						<td>  <center>  <label id="<?php echo $idTotalLigne; ?>"> <?php echo $totalLigne;?></label> </center></td>
					</tr>
				<?php } ?>
				<tr><td colspan="4"> Total du contrat </td>
					<td style="text-align:center"> <label id="total"> <?php echo $totalContrat;?> </label></td>
				</tr>
			</table>
			</tr></td>
			<?php break;

		case 'amap_tisanes':?>
			<tr><td>  
			<table>
				<tr><th>1er trimestre</th>
					<th>2nd trimestre</th>
					<th>3eme trimestre</th>
					<th>4eme trimestre</th>
				</tr>
				
				<tr>
					<td><label for="t1u">tisane unitaire: </label>
						<input type="number" id="t1u" name="t1u" min="0" max="10" value="0"/>
					</td>
					<td><label for="t2u">tisane unitaire: </label>
						<input type="number" id="t2u" name="t2u" min="0" max="10" value="0"/>
					</td>
					<td><label for="t3u">tisane unitaire: </label>
						<input type="number" id="t3u" name="t3u" min="0" max="10" value="0" />
					</td>
					<td><label for="t4u">tisane unitaire: </label>
						<input type="number" id="t4u" name="t4u" min="0" max="10" value="0" />
					</td>
				</tr>
				<tr>
					<td><label for="t1p">petit fromat: </label>
						<input type="number" id="t1p" name="t1p" min="0" max="10" value="0" />
					</td>
					<td><label for="t2p">petit fromat: </label>
						<input type="number" id="t2p" name="t2p" min="0" max="10" value="0" />
					</td>
					<td><label for="t3p">petit fromat: </label>
						<input type="number" id="t3p" name="t3p" min="0" max="10"  value="0"/>
					</td>
					<td><label for="t4p">petit fromat: </label>
						<input type="number" id="t4p" name="t4p" min="0" max="10"  value="0"/>
					</td>
				</tr>
				<tr>
					<td><label for="t1g">grand fromat: </label>
						<input type="number" id="t1g" name="t1g" min="0" max="10" value="0" />
					</td>
					<td><label for="t2g">grand fromat: </label>
						<input type="number" id="t2g" name="t2g" min="0" max="10" value="0" />
					</td>
					<td><label for="t3g">grand fromat: </label>
						<input type="number" id="t3g" name="t3g" min="0" max="10" value="0" />
					</td>
					<td><label for="t4g">grand fromat: </label>
						<input type="number" id="t4g" name="t4g" min="0" max="10" value="0" />
					</td>
				</tr>
				<tr>
					<td><label for="t1s">sirop: </label>
						<input type="number" id="t1s" name="t1s" min="0" max="10" value="0" />
					</td>
					<td><label for="t2s">sirop: </label>
						<input type="number" id="t2s" name="t2s" min="0" max="10" value="0" />
					</td>
					<td><label for="t3s">sirop: </label>
						<input type="number" id="t3s" name="t3s" min="0" max="10" value="0" />
					</td>
					<td><label for="t4s">sirop: </label>
						<input type="number" id="t4s" name="t4s" min="0" max="10"  value="0"/>
					</td>
				</tr>
           </table>
		   </td></tr>
		<?php break;
			 
		case 'amap_farines_huiles':?>  
			<tr><td>
				<label for="nbunite">Valeur du contrat : </label>
				<input type="number" id="montant" name="montant" min="1" max="200"  />
			</td></tr>
			<?php break; 
            
		case 'amap_produits_laitiers':?>
			<tr><td>
				<label for="nbunite">Nombre d'unité : </label>
				<input type="number" id="nbunite" name="nbunite" min="3" max="20" value="5" />
			</td></tr>
			<tr><td> 	
				<label for="nblivraison">Nombre de livraisons : </label>
				<input type="number" id="nblivraison" name="nblivraison" min="1" max="<?php echo $nbLivMax; ?>" value="<?php echo $nbLivMax; ?>" />
			</td></tr>
			<?php break;

		case 'amap_cerises': ?>
			<tr><td>
				<label for="nbsac">Nombre de sac 1kg/livraison : </label>
				<input type="number" id="nbsac" name="nbsac" min="1" max="20" value="1" />
			</td></tr>
			<tr><td> 
				<label for="nblivraison">Nombre de livraisons : </label>
				<input type="number" id="nblivraison" name="nblivraison" min="1" max="<?php echo $nbLivMax; ?>" value="<?php echo $nbLivMax; ?>" />
			<?php break;	
          
		case 'amap_pommes': ?>
			<tr><td>
				<label for="nbdoux">Nombre plateau pommes doux/livraison : </label>
				<input type="number" id="nbdoux" name="nbdoux" min="0" max="<?php echo $max;?>" value="1" />
			</td></tr>
            <tr><td> 
				<label for="nbacide">Nombre plateau pommes acide/livraison : </label>
				<input type="number" id="nbacide" name="nbacide" min="0" max="<?php echo $max;?>" value="0"/>
			</td></tr>
            <tr><td> 
				<label for="nbdoux">Nombre plateau pommes alterné/livraison : </label>
				<input type="number" id="nbalterne" name="nbalterne" min="0" max="<?php echo $max;?>" value="0"/>
            </td></tr>
            <tr><td>
				<label for="nbjusnat">Nombre jus pommes natures/livraison : </label>
				<input type="number" id="nbjusnat" name="nbjusnat" min="0" max="15" value="0"/>
			</td></tr>
            <tr><td>
				<label for="nbjuscit">Nombre jus pommes citron/livraison : </label>
				<input type="number" id="nbjuscit" name="nbjuscit" min="0" max="5" value="0"/>
			</td></tr>
            <tr><td>
				<label for="nbjuscanpoire">Nombre jus pommes cannelle puis poire/livraison : </label>
				<input type="number" id="nbjuscanpoire" name="nbjuscanpoire" min="0" max="5" value="0"/>
			</td></tr>
            <tr><td> 
				<label for="nblivraison">Nombre de livraisons : </label>
				<input type="number" id="nblivraison" name="nblivraison" min="1" max="<?php echo $nbLivMax; ?>" value="<?php echo $nbLivMax; ?>"/>
			<?php break;	     
          
		case 'amap_oeufs': ?>
			<tr><td>
			<table border='2'> 
				<tr>
					<th>Produits</th>
					<th>Prix unitaire</th>
					<th>Quantité/livraison</th>
					<th>Nbre de livraison</th>          
					<th>Prix (euros)</th>
				</tr>
							  
				<?php   
				//il faut refaire la requête pour parcourir tous les produits
				$produits = mysqli_query( $questionProduits);
				$nbLigne = mysqli_num_rows($produits);
				$totalContrat =0;
				
				while ($produitPoulet = mysqli_fetch_array($produits) ) {	
					$id = $produitPoulet['id'];
					$produit = $produitPoulet['Nom_produit'];
					$nbLivMax = $produitPoulet['Nbre_livraison'];
					$maxParLivraison = $produitPoulet['Max_par_livraison'];
					$prixUnitaire = $produitPoulet['Prix'];
					// bornage pour éviter de planter si la base est mal renseignée
					if ( $nbLivMax < 1 )  $nbLivMax = 1;
						   
					$idQuantiteProduit="nb".$id;
					$idNbLivraison= "nbLiv".$id;
					$idPrix = "prix".$id;
					$idTotalLigne = "total".$id;
					$defaut = 0; $totalLigne = 0;
					switch ( $id) { 
						case 1: $defaut = 6; break;// oeufs
						case 2: $defaut = 1; break;// poule pondeuse
						case 3: $defaut = 1; // cas 1 poulet par mois 
					}
					$totalLigne = $prixUnitaire*$defaut*$nbLivMax;
					$totalContrat += $totalLigne;
					
					?>
					<tr>
						<td> <label id="<?php echo $produit; ?>"> <?php echo $produit; ?></label></td>
						<td>  <center>  
						  <label id="<?php echo $idPrix; ?>"> <?php echo $prixUnitaire; ?></label>
						  <input type="hidden" name="<?php echo $idPrix; ?>" value="<?php echo $prixUnitaire; ?>"/>
						</center></td>
						<td> <center> 
						<input type="number" id="<?php echo $idQuantiteProduit;?>" name="<?php echo $idQuantiteProduit; ?>"
							   min="0" max="<?php echo $maxParLivraison; ?>" 
							  onchange='javascript:calculPrixLigne( "<?php echo $idPrix;?>", "<?php echo $idQuantiteProduit;?>", "<?php echo $idNbLivraison;?>", "<?php echo $idTotalLigne;?>",<?php echo $nbLigne;?>)'
							   value="<?php echo $defaut?>"/>               
						</center> </td>
						<td> <center> 
						<input type="number" id="<?php echo $idNbLivraison; ?>" name="<?php echo $idNbLivraison; ?>"
							   min="0" max="<?php echo $nbLivMax; ?>" 
							   onchange='javascript:calculPrixLigne( "<?php echo $idPrix;?>", "<?php echo $idQuantiteProduit;?>", "<?php echo $idNbLivraison;?>", "<?php echo $idTotalLigne;?>",<?php echo $nbLigne;?>)'
							   value="<?php echo $nbLivMax; ?>"/> 
						</center> </td>  					      
						<td>  <center>  <label id="<?php echo $idTotalLigne; ?>"> <?php echo $totalLigne;?></label> </center></td>
					</tr>
				<?php } ?>
				<tr><td colspan="3"> Total du contrat </td>
					<td style="text-align:center"> <label id="total"> <?php echo $totalContrat;?> </label></td>
					<td style="text-align:center"> <input type="number" step="any" id="MontantTotal" name="MontantTotal" value="<?php echo ceil($totalContrat);?>" /></td>
				</tr>
			</table>
			</tr></td>
			<?php break;	     
		             	             
		case 'amap_champignons': ?>
			<tr><td>
			<table border='2'> 
				<tr>
					<th>Type de contrat</th>
					<th>Quantité par livraison</th>
					<th>Nbre de livraison</th>
				</tr>
			  
				<?php   
                $produits = mysqli_query( $questionProduits);
                while ($produitChampignon = mysqli_fetch_array($produits) ) 
                {	
                  $id = $produitChampignon['id'];
                  $produit = $produitChampignon['Nom_produit'];            
                  $idQuantiteProduit="nb".$id;
                  $idNbLivraison= "nbLiv".$id;    
                  $prixUnitaire= $produitChampignon['Prix'];
                            
                ?>
				<tr>
				<td> <label for="<?php echo $produit;?>"> <?php echo $produit;?></label></td>
				<td> <center> 
					<select id="<?php echo $idQuantiteProduit; ?>" name="<?php echo $idQuantiteProduit; ?>">
					<option value="0" selected="selected" >0</option>
					<option value="0.25" >250 g</option>
					<option value="0.50">500 g</option>
					<option value="0.75">750 g</option>
					<option value="1">1 kg</option>
					<option value="1.25">1,250 kg</option>
					<option value="1.5">1,5 kg</option>
					<option value="1.75">1,750 kg</option>
					<option value="2">2 kg</option>
					</select> </center>
				</td>
				<td> <center> 
					<select id="<?php echo $idNbLivraison; ?>" name="<?php echo $idNbLivraison; ?>">
					<?php   $i =0;
					while ( $i <= $nbLivMax )  {	  
						if ( $i != $nbLivMax) { ?>
							<option value=" <?php echo $i;?>"><?php echo $i;?></option>
						<?php }  else { ?>
							<option value="<?php echo $i;?>" selected="selected"><?php echo $i;?></option>
						 <?php  }
						$i++;
					}
					?>	
					</select> </center>
				</td> </tr>
				<?php }  ?>
				<input type="hidden" name="prixUnitaire" id="prixUnitaire" value="<?php echo $prixUnitaire;?>" />
			</table>
			</tr></td>
			<?php break;	     
		        	
          
		case 'amap_agrumes':?>
			<tr><td>
				<label for="nbunite">Nombre de 1/2 cagette: </label>
				<input type="number" id="nbunite" name="nbunite" min="0" max="2" value="1"/>
			</td></tr>
         	<tr><td>
				<label for="nblivraison">Nombre de livraisons : </label>
				<input type="number" id="nblivraison" name="nblivraison" min="1" max="<?php echo $nbLivMax; ?>" value="<?php echo $nbLivMax; ?>"/>
			</td></tr>
			<?php break;			
		} ?>   
       					
		<tr><td>
			<input type="submit" value="Ajouter à <?php echo $_GET['amap']; ?>"/>
			<input onclick="document.location.href='webmaster_infos.php?nom_amap=<?php echo $_GET['amap']; ?>'" type="Button" value="Annuler" /> 
		</td></tr>  
	</table>
	<?php } 
	else {?>
		<p>	Cet adhérent est déjà inscrit à l'<?php echo $_GET['amap']; ?><br />
		<input onclick="document.location.href='webmaster_infos.php?nom_amap=<?php echo $_GET['amap']; ?>'" type="Button" value="OK" />
		</p>
	<?php } 
	mysqli_close();  ?>
</body>
</html>
