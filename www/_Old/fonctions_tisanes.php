<?php

define('JOURS_MARGE_TISANE',4);

function computeProducts($dateProchaineCommande ) {
	
	// recherche des produits de Leila commandés
	$questionProd="SELECT distinct cde.Id_Produit, cde.Type_Produit, prod.Nom_produit, prod.Prix FROM amap_tisanes_cde cde "
			."LEFT JOIN amap_tisanes_produits prod on cde.Id_Produit=prod.id  WHERE cde.Date='".$dateProchaineCommande."' ORDER BY Type_Produit, Id_Produit";
	//echo $questionProd;
	$reponseProd = mysqli_query($questionProd) or die(mysqli_error());
	$nombreProd = mysqli_num_rows($reponseProd);

	$tabIndice = array();
	$i=0;
	while($donnees = mysqli_fetch_array($reponseProd)) { 
		$nom =  $donnees["Nom_produit"] == '' ? "ton choix" :  $donnees["Nom_produit"];
		$idProduit = $donnees['Id_Produit'];
		if ( $idProduit == '') {
			$idProduit = - ((int) $donnees['Type_Produit']); // on code des id négatifs pour les choix de Leila, différents pour chaque type de produit
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
	// recherche des produits commandés par les amapiens
	$question="SELECT * FROM amap_tisanes_cde where Date='".$dateProchaineCommande."' ORDER BY Nom, Id_Personne, Id_Produit";
	//echo $question;
	$reponse = mysqli_query($question) or die(mysqli_error());
	
	// Parcours de la liste des commandes pour alimenter le tableau $tabCommandes
	// tableau des commandes : Tableau à 2 dimensions Id-personne * id produit et en valeur la quantité
	// Pour Moreau (id 174) ayant commandé les produits d'ID "2" et "54" en 1 et 2 exemplaires, on aura : 
	// "174" => ("2"=>1, "54"=>2)
	while($donnees = mysqli_fetch_array($reponse)) { 
		$idPerso =$donnees['Id_Personne'];
		$idProduit = $donnees['Id_Produit'];
		if ( $idProduit == '') {
			$idProduit = - ((int) $donnees['Type_Produit']);
		}
		$tabCommandes[$idPerso][(int)$idProduit] ++;
		$noms[$idPerso] = $donnees['Nom'];
		// tableau pour la quantité total de chaque produit
		$totalColonne[$idProduit] ++;
	}
	$tab['tabCommandes'] = $tabCommandes;
	$tab['noms'] = $noms;
	$tab['totalColonne'] = $totalColonne;
	
	return $tab;
}
?>