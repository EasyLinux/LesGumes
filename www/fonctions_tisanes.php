<?php

define('JOURS_MARGE_TISANE',4);

function computeProducts($dateProchaineCommande ) {
	
	// recherche des produits de Leila command�s
	$questionProd="SELECT distinct cde.Id_Produit, cde.Type_Produit, prod.Nom_produit, prod.Prix FROM amap_tisanes_cde cde "
			."LEFT JOIN amap_tisanes_produits prod on cde.Id_Produit=prod.id  WHERE cde.Date='".$dateProchaineCommande."' ORDER BY Type_Produit, Id_Produit";
	//echo $questionProd;
	$reponseProd = mysql_query($questionProd) or die(mysql_error());
	$nombreProd = mysql_num_rows($reponseProd);

	$tabIndice = array();
	$i=0;
	while($donnees = mysql_fetch_array($reponseProd)) { 
		$nom =  $donnees["Nom_produit"] == '' ? "ton choix" :  $donnees["Nom_produit"];
		$idProduit = $donnees['Id_Produit'];
		if ( $idProduit == '') {
			$idProduit = - ((int) $donnees['Type_Produit']); // on code des id n�gatifs pour les choix de Leila, diff�rents pour chaque type de produit
		}	
		$tabNomProduit[++$i] = $nom; 			
		$tabIndice[$i] = $idProduit; 
		$tabTypeProduit[(int) $donnees['Type_Produit']] ++;
	}
	
	$tab['nombreProd'] = $nombreProd;
	$tab['tabNomProduit'] = $tabNomProduit;
	$tab['tabIndice'] = $tabIndice;
	$tab['tabTypeProduit'] = $tabTypeProduit ;
	return $tab;
}
function computeCommandes($dateProchaineCommande) {
	// recherche des produits command�s par les amapiens
	$question="SELECT * FROM amap_tisanes_cde where Date='".$dateProchaineCommande."' ORDER BY Nom, Id_Personne, Id_Produit";
	//echo $question;
	$reponse = mysql_query($question) or die(mysql_error());
	
	// Parcours de la liste des commandes pour alimenter le tableau $tabCommandes
	// tableau des commandes : Tableau � 2 dimensions Id-personne * id produit et en valeur la quantit�
	// Pour Moreau (id 174) ayant command� les produits d'ID "2" et "54" en 1 et 2 exemplaires, on aura : 
	// "174" => ("2"=>1, "54"=>2)
	while($donnees = mysql_fetch_array($reponse)) { 
		$idPerso =$donnees['Id_Personne'];
		$idProduit = $donnees['Id_Produit'];
		if ( $idProduit == '') {
			$idProduit = - ((int) $donnees['Type_Produit']);
		}
		$tabCommandes[$idPerso][(int)$idProduit] ++;
		$noms[$idPerso] = $donnees['Nom'];
		// tableau pour la quantit� total de chaque produit
		$totalColonne[$idProduit] ++;
	}
	$tab['tabCommandes'] = $tabCommandes;
	$tab['noms'] = $noms;
	$tab['totalColonne'] = $totalColonne;
	
	return $tab;
}
?>