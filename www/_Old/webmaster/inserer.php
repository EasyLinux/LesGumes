<?php
include_once("define.php"); 
mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysqli_select_db(base_de_donnees); // Sélection de la base 
$question="SELECT * FROM amap_generale WHERE id=".$_GET['id'];
$reponse=mysqli_query( $question) or die(mysqli_error());
$adherent=mysqli_fetch_array($reponse);

$values="";

switch ($_GET['amap']) {
	case 'amap_legumes':
		$insert = " Nombre_panier, Nbre_livraison";
		$values = $_GET['nbpanier'].",".$_GET['nblivraison'];
		// suppression de l'amapien de la liste d'attente, s'il y était ...
		$question="DELETE FROM amap_legumes_liste_attente WHERE id =".$_GET['id'];
		mysqli_query( $question) or die(mysqli_error());
		break;
	case 'amap_produits_laitiers':
		$question="INSERT INTO amap_produits_laitiers_cde (id, Nom, Prenom, Unite ) VALUES (";
		$question.=$adherent[0].",'".$adherent[1]."','".$adherent[2]."',".$_GET['nbunite'].")";
		mysqli_query( $question) or die(mysqli_error());

		$insert = "Nbre_unite, Nbre_livraison";
		$values = $_GET['nbunite'].",".$_GET['nblivraison'];
		break;	
	case 'amap_chevre':
		$nbLiv= $_GET['nbLiv1'];		//doivent être identique pour tous les grands et fromage blanc
		$prixGrand = $_GET['prix1'];	//doivent être identique pour tous les grands
		$nbGrand= $_GET['nb1'];	//FRAIS
		$nbGrand= $nbGrand + $_GET['nb2']; //AFFINE
		$nbGrand= $nbGrand + $_GET['nb3'];	//SEC
		$nbGrand= $nbGrand +  $_GET['nb4'];		//SESAME
		$nbGrand= $nbGrand +  $_GET['nb5'];		//PROVENCE
		$nbGrand= $nbGrand +  $_GET['nb6'];	//ECHALOTTES
		$nbGrand= $nbGrand +  $_GET['nb7'];	// CENDRE
		$nbPetitFromageBlanc= $_GET['nb8'];
		$prixPetitFromageBlanc = $_GET['prix8'];	
		$nbGrandFromageBlanc= $_GET['nb9'];
		$prixGrandFromageBlanc = $_GET['prix9'];		
		$supplement= $_GET['nb10'];	
		
		$insert = "Nbre_Grand, Prix_Grand, Nbre_Petit_Fromage_Blanc, Prix_Petit_Fromage_Blanc,Nbre_Grand_Fromage_Blanc, Prix_Grand_Fromage_Blanc, supplement, Nbre_livraison";
		$values = $nbGrand.",".$prixGrand.",".$nbPetitFromageBlanc.",".$prixPetitFromageBlanc.",".$nbGrandFromageBlanc.",".$prixGrandFromageBlanc.",".$supplement.",".$nbLiv;
		break;	
	case 'amap_cerises':
		$insert="INSERT INTO ".$_GET['amap']." (id, Nom, Prenom, Nbre_sac_1kg, Nbre_livraison, Contrat_verrouille) ";
		$values.="VALUES (".$adherent[0].",'".$adherent[1]."','".$adherent[2]."',".$_GET['nbsac'].",".$_GET['nblivraison'];
		break;		
	case 'amap_pommes':
		$insert = "Nbre_pltx_pom_doux, Nbre_pltx_pom_acide, Nbre_pltx_pom_alterne, Nbre_jus_pom_nature, Nbre_jus_pom_citron, Nbre_jus_pom_cannelle_puis_poire, Nbre_livraison";
		$values = $_GET['nbdoux'].",".$_GET['nbacide'].",".$_GET['nbalterne'].",".$_GET['nbjusnat'].",".$_GET['nbjuscit'].",".$_GET['nbjuscanpoire'].",".$_GET['nblivraison'];
		break;		
	case 'amap_oeufs':
		$nbOeufs= $_GET['nb1'];
		$prixOeuf = $_GET['prix1'];
		$nbPoulePondeuse= $_GET['nb2'];
		$prixPoulePondeuse = $_GET['prix2'];
		$nbPoulets = 0 + $_GET['nb3'] +  $_GET['nb4'];   // on choisit soit des poulets par mois ou par quinzaine
		$prixPoulet = $_GET['prix3']; // prix identique pour les poulets par mois et par quinzaine
		$supplementVolaille= $_GET['nb5'];
		$montantTotal=$_GET['MontantTotal'];

		$nbLivOeufs = 0;
		if ($nbOeufs != 0) { $nbLivOeufs = $_GET['nbLiv1']; }
		$nbLivPoulet = 0 ;
		// on choisit soit des poulets par mois ou par quinzaine, mais pas les 2
		if ( $nbPoulets != 0) {
			if ( 0 == $_GET['nb3']) { 
				$nbLivPoulet = $_GET['nbLiv4'];
			} elseif  ( 0 == $_GET['nb4']) {
				$nbLivPoulet = $_GET['nbLiv3'];
			}
		}

		$insert = "Nbre_oeufs, Prix_oeuf, nbre_Poule, Prix_Poule, Nbre_Poulet, Prix_poulet, Nbre_livraison_oeufs, Nbre_livraison_poulets, Montant_total";
		$values = $nbOeufs.",".$prixOeuf.",".$nbPoulePondeuse.",".$prixPoulePondeuse.",".$nbPoulets.",".$prixPoulet.",".	$nbLivOeufs.",".$nbLivPoulet.",".$montantTotal;

		break;
	case 'amap_champignons':	
		$nbLiv = 0;
		if ( $_GET['nb1'] > 0) {
			$nbLiv = $_GET['nbLiv1'];}
		// uniquement quand il y avait des livraiosn quinzaines ou des mensuelles :  if ( $_GET['nb2'] >0) { $nbLiv = $nbLiv + $_GET['nbLiv2'];  }
		$insert = "Poids_par_quinzaine,Poids_par_mois, Nbre_livraison,Prix";
		$values = $_GET['nb1'].",0,".$nbLiv.",".$_GET['prixUnitaire'];
		break;	
    		
	 case 'amap_pain':
		$insert = "";
		$values = "";
		break;	
   case 'amap_agrumes':
		$insert = "Nbre_demi_cagette,Nbre_livraison";
		$values = $_GET['nbunite'].",".$_GET['nblivraison'];
		break;
   case 'amap_farines_huiles':
		$insert = "Valeur";
		$values = $_GET['valeur'];
		break;	
	case 'amap_tisanes':
		$insert = "t1_unitaire, t1_petit, t1_grand, t1_sirop, t2_unitaire, t2_petit, t2_grand, t2_sirop, t3_unitaire, t3_petit, t3_grand, t3_sirop, t4_unitaire, t4_petit, t4_grand, t4_sirop, Prix";
		$values1 = $_GET['t1u'].",".$_GET['t1p'].",".$_GET['t1g'].",".$_GET['t1s'].",";
		$values2 = $_GET['t2u'].",".$_GET['t2p'].",".$_GET['t2g'].",".$_GET['t2s'].",";
		$values3 = $_GET['t3u'].",".$_GET['t3p'].",".$_GET['t3g'].",".$_GET['t3s'].",";
		$values4 = $_GET['t4u'].",".$_GET['t4p'].",".$_GET['t4g'].",".$_GET['t4s'].",";
		
		// A FAIRE : récupérer les prix des produits dans la table produits_info
		$questionPrices="SELECT * FROM amap_tisanes_produits_info ORDER BY id";
		$reponsePrices=mysqli_query( $questionPrices) or die(mysqli_error());
		while ($prices = mysqli_fetch_array($reponsePrices) ) {
			$myId= $prices["id"];
			switch ($myId) {
				case 1 : $price1 = $prices['Prix']; break;
				case 2 : $price2 = $prices['Prix']; break;
				case 3 : $price3 = $prices['Prix']; break;
				case 4 : $price4 = $prices['Prix']; break;
			}
		}
		$prix = $price1 * ($_GET['t1u'] + $_GET['t2u'] + $_GET['t3u'] + $_GET['t4u'] ) + $price2 *($_GET['t1p'] + $_GET['t2p'] + $_GET['t3p'] + $_GET['t4p']) +
				$price3 * ((int)$_GET['t1g'] + $_GET['t2g'] + $_GET['t3g'] + $_GET['t4g'] ) + $price4 *($_GET['t1s'] + $_GET['t2s'] + $_GET['t3s'] + $_GET['t4s']); 
		$values = $values1.$values2.$values3.$values4.$prix;		
		
		break;	
	case 'amap_kiwis': $values="1"; $insert="Nombre_panier"; break;
	case 'amap_tommes': $values="1"; $insert="Nbre_quart_de_tomme"; break;
}

$insert1= "INSERT INTO ".$_GET['amap']." (id, Nom, Prenom,";
$values1 ="VALUES (".$adherent[0].",'".$adherent[1]."','".$adherent[2]."',";

if( $insert !="") {
	$insert1= $insert1.$insert.",";
	$values1 =$values1.$values.",";
}
$insert1 = $insert1. "Contrat_verrouille, Date_debut_contrat,Date_fin_contrat,Date_paiement, Nbre_cheque) ";
$values1 = $values1."1,'".$_GET['datedeb']."','".$_GET['datefin']."','".$_GET['datepaiement']."',".$_GET['nbrecheque'].")";
$question = $insert1. $values1;
//echo  $question; // mettre /header($page) en commentaire
mysqli_query( $question) or die(mysqli_error());
mysqli_close();
$page="Location: webmaster_infos.php?nom_amap=";
$page.=$_GET['amap'];
header($page);

?>