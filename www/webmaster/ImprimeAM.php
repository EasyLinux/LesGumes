<?php
include_once("define.php");
require('../fpdf.php');
                   

$pdf=new FPDF('L','mm','a4');
$pdf->AddPage();
$pdf->SetTopMargin(0.0);
$pdf->SetAutoPageBreak(true,5);
$pdf->SetFont('Arial','B',10);

mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysql_select_db(base_de_donnees); // Sélection de la base 

$tableLegume = $_GET['amap'];
$question="SELECT ".$tableLegume.".*, amap_generale.e_mail, amap_generale.Telephone, amap_generale.Tel_portable FROM ".$tableLegume.", amap_generale WHERE ".$tableLegume.".id= amap_generale.id ORDER BY Nom";
	
$reponse = mysql_query($question) or die(mysql_error());
$ligne = mysql_num_rows($reponse);
mysql_close();

$pdf->Cell(250,5,'ST SEBASTIEN '.$_GET['amap'].' --------- LISTE DES ADHERENTS --------- '.date("d-M-Y").' --------- '.$ligne.' INSCRITS',0,1,'C');
$pdf->SetFont('Arial','',10);
$pdf->SetFillColor(220,220,220);

$smallCellWidth = 15;  //taille par défaut des cellule
$largeCellWidth = 50;  //taille par défaut des cellule
$cellWidth = 30;  //taille par défaut des cellule

$sautLigne=5;
$nbligneParPage= 36;

// initialisation des compteurs
$total=0;  $nb=0;  
$total2=0;  $nb2=0;
$total3=0;  $nb3=0;
switch ($_GET['amap']) {
  case 'amap_pommes':
  	$nbdoux =0;
  	$nbacide =0;
  	$nbalterne =0;
  	$nbjusnature =0;
  	$nbjuscitron =0;
  	$nbjuscanelle =0;
 	case 'amap_pain':
		$nbNormal1kg =0;
		$nbNormal1kgMoule =0;
		$nbNormal500g =0;
		$nbGraine1kgmoule =0;
		$nbGraine500g =0;  
		$nbSansGluten500g;
    
}

while($donnees = mysql_fetch_array($reponse)) {

	if($j==0 || $j % $nbligneParPage==0) {
		// début de page
		if ( $j !=0 ) { //changement de page
			  $pdf->AddPage();
			  $pdf->Ln(5);		  
		}
			$pdf->SetTopMargin(0.0);
			$pdf->SetFillColor(220,220,220);
		if ( $_GET['amap'] == 'amap_pain' || $_GET['amap'] =='amap_pommes') {
		  $sautLigne=10;    // 2 lignes de titres
		  $nbligneParPage= 33; // pour garder de la place pour les 2 lignes de titre
		}
		$pdf->Cell($largeCellWidth,$sautLigne,'Nom',1,0,'C',true);
		$pdf->Cell($largeCellWidth,$sautLigne,'Mail',1,0,'C',true);
		$pdf->Cell($cellWidth,$sautLigne,'Téléphone',1,0,'C',true);
		$pdf->Cell($smallCellWidth,$sautLigne,'livr',1,0,'C',true);
		$sautLigne=5;
		switch ($_GET['amap']) {
			case 'amap_legumes':
				$pdf->Cell($smallCellWidth,$sautLigne,'Qte',1,0,'C',true);
			break;
			case 'amap_oeufs':
				$pdf->Cell($smallCellWidth,$sautLigne,'Nb oeufs',1,0,'C',true);
				$pdf->Cell($cellWidth,$sautLigne,'Nb Poulets',1,0,'C',true);
				$pdf->Cell($smallCellWidth,$sautLigne,'Montant',1,0,'C',true);
				break;
			case 'amap_cerises':
				$pdf->Cell($cellWidth,$sautLigne,'Nb de kg/livraison',1,0,'C',true);
				break;
			case 'amap_pommes':
				$pdf->Cell($smallCellWidth*3,5,'plateau',1,0,'C',true);
				$pdf->Cell($smallCellWidth*3,5,'jus',1,0,'C',true);
				$pdf->Ln(5);
				$pdf->Cell($largeCellWidth*2+$cellWidth+$smallCellWidth,5,'',0,0);
				$pdf->Cell($smallCellWidth,5,'doux',1,0,'C',true);
				$pdf->Cell($smallCellWidth,5,'acide',1,0,'C',true);
				$pdf->Cell($smallCellWidth,5,'alterné',1,0,'C',true);
				$pdf->Cell($smallCellWidth,5,'nature',1,0,'C',true);
				$pdf->Cell($smallCellWidth,5,'citron',1,0,'C',true);
				$pdf->Cell($smallCellWidth,5,'canelle',1,0,'C',true);
  			break;
			case 'amap_produits_laitiers':
				$pdf->Cell($cellWidth,$sautLigne,"Nb d'unité",1,0,'C',true);
				break;
			case 'amap_pain':
				$pdf->Cell($smallCellWidth*3,$sautLigne,"Standard",1,0,'C',true);
				$pdf->Cell($smallCellWidth*3,$sautLigne,"Graines",1,0,'C',true);
				$pdf->Ln(5);
				$pdf->Cell($largeCellWidth*2+$cellWidth+$smallCellWidth,$sautLigne,'',0,0);
				$pdf->Cell($smallCellWidth-2,$sautLigne,"1kg",1,0,'C',true);
				$pdf->Cell($smallCellWidth+4,$sautLigne,"1kg moulé",1,0,'C',true);
				$pdf->Cell($smallCellWidth-2,$sautLigne,"500g",1,0,'C',true);
				$pdf->Cell($smallCellWidth+4,$sautLigne,"1kg moulé",1,0,'C',true);
				$pdf->Cell($smallCellWidth-2,$sautLigne,"500g",1,0,'C',true);
				$pdf->Cell($smallCellWidth-2,$sautLigne,"500g",1,0,'C',true);
				break;
			case 'amap_agrumes':
				$pdf->Cell($cellWidth,$sautLigne,"1/2 cagette",1,0,'C',true);
				break;
			case 'amap_chevre':
				$pdf->Cell($cellWidth,$sautLigne,"Nb d'unité",1,0,'C',true);
				break;
			case 'amap_champignons':
				// $pdf->Cell($cellWidth,$sautLigne,"Par mois",1,0,'C',true);
				$pdf->Cell($cellWidth,$sautLigne,"Par quinzaine",1,0,'C',true);
				break;
		}
		
		if ( $_GET['amap'] != 'amap_pain' && $_GET['amap'] !='amap_pommes') {
			$pdf->Cell($cellWidth,$sautLigne,'Début',1,0,'C',true);
			$pdf->Cell($cellWidth,$sautLigne,'Fin',1,0,'C',true);
			$pdf->Cell($smallCellWidth,$sautLigne,'Chèque',1,0,'C',true);
		}
			$pdf->Ln($sautLigne);
	}
	$j++;
	
	$nom= $donnees['Nom'].' '.$donnees['Prenom'];
	if (strlen($nom) > 22 ) {
		$nom = substr($nom,0,22);
		$nom = $nom.'.';
	}
  
	$telephone= $donnees['Tel_portable'];
	if (strlen($telephone) < 10 ) {
		$telephone =  $donnees['Telephone'];
	}
  
	if($j % 3==1) $pdf->SetFillColor(64,167,245);
	elseif ($j % 3==2) $pdf->SetFillColor(159,245,128);
	else $pdf->SetFillColor(255,255,255);

	$pdf->Cell($largeCellWidth,$sautLigne,$nom,1,0,'C',true);
	$pdf->Cell($largeCellWidth,$sautLigne,$donnees['e_mail'],1,0,'C',true);
	$pdf->Cell($cellWidth,$sautLigne,$telephone,1,0,'C',true);
	if ( $_GET['amap'] == 'amap_oeufs')
		$pdf->Cell($smallCellWidth,$sautLigne,$donnees['Nbre_livraison_oeufs'],1,0,'C',true);
	else {
		$pdf->Cell($smallCellWidth,$sautLigne,$donnees['Nbre_livraison'],1,0,'C',true);
	}

	switch ($_GET['amap']) {
		case 'amap_legumes':
			$pdf->CellNumber($smallCellWidth,$sautLigne,$donnees['Nombre_panier'],1,0,'C',true);
 			$nb = $donnees['Nombre_panier'];         
			break;	
		case 'amap_oeufs':
			$nb  = $donnees['Nbre_oeufs'];
			$pdf->CellNumber($smallCellWidth,$sautLigne,$nb,1,0,'C',true);
			$pdf->CellNumber($cellWidth,$sautLigne,$donnees['Nbre_poulet'],1,0,'C',true);
			$nb2= $donnees['Nbre_poulet'];
			$pdf->CellNumber($smallCellWidth,$sautLigne,$donnees['Montant_total'],1,0,'C',true);
			$nb3= $donnees['Montant_total'];
			break;	
		case 'amap_cerises':                                     
			$pdf->CellNumber($cellWidth,$sautLigne,$donnees['Nbre_sac_1kg'],1,0,'C',true);
	   	$nb= $donnees['Nbre_sac_1kg'];
			break;	
		case 'amap_pommes':
			$pdf->CellNumber($smallCellWidth,$sautLigne,$donnees['Nbre_pltx_pom_doux'],1,0,'C',true);
			$pdf->CellNumber($smallCellWidth,$sautLigne,$donnees['Nbre_pltx_pom_acide'],1,0,'C',true);
			$pdf->CellNumber($smallCellWidth,$sautLigne,$donnees['Nbre_pltx_pom_alterne'],1,0,'C',true);
			$pdf->CellNumber($smallCellWidth,$sautLigne,$donnees['Nbre_jus_pom_nature'],1,0,'C',true);
			$pdf->CellNumber($smallCellWidth,$sautLigne,$donnees['Nbre_jus_pom_citron'],1,0,'C',true);
			$pdf->CellNumber($smallCellWidth,$sautLigne,$donnees['Nbre_jus_pom_cannelle_puis_poire'],1,0,'C',true);
			$nb= $donnees['Nbre_pltx_pom_doux']+$donnees['Nbre_pltx_pom_acide']+$donnees['Nbre_pltx_pom_alterne'];
			$nb2= $donnees['Nbre_jus_pom_nature']+$donnees['Nbre_jus_pom_citron']+$donnees['Nbre_jus_pom_cannelle_puis_poire'];
			$nbdoux += $donnees['Nbre_pltx_pom_doux'];
			$nbacide += $donnees['Nbre_pltx_pom_acide'];
			$nbalterne += $donnees['Nbre_pltx_pom_alterne'];
			$nbjusnature += $donnees['Nbre_jus_pom_nature'];
			$nbjuscitron += $donnees['Nbre_jus_pom_citron'];
			$nbjuscanelle += $donnees['Nbre_jus_pom_cannelle_puis_poire'];	
			break;	
		case 'amap_produits_laitiers':
			$pdf->CellNumber($cellWidth,$sautLigne,$donnees['Nbre_unite'],1,0,'C',true);
			$nb= $donnees['Nbre_unite'];
			break;
		case 'amap_chevre':
			$pdf->CellNumber($cellWidth,$sautLigne,$donnees['Nbre_unite'],1,0,'C',true);
			$nb= $donnees['Nbre_unite'];
			break;	
		case 'amap_pain':
			$pdf->CellNumber($smallCellWidth-2,$sautLigne,$donnees['Nbre_pain_1kg'],1,0,'C',true);
			$pdf->CellNumber($smallCellWidth+4,$sautLigne,$donnees['Nbre_pain_moule_1kg'],1,0,'C',true);
			$pdf->CellNumber($smallCellWidth-2,$sautLigne,$donnees['Nbre_pain_500g'],1,0,'C',true);
			$pdf->CellNumber($smallCellWidth+4,$sautLigne,$donnees['Nbre_pain_graines_moule_1kg'],1,0,'C',true);
			$pdf->CellNumber($smallCellWidth-2,$sautLigne,$donnees['Nbre_pain_graines_500g'],1,0,'C',true);
			$pdf->CellNumber($smallCellWidth-2,$sautLigne,$donnees['Nbre_pain_sans_gluten_500g'],1,0,'C',true);
			$nbNormal1kg += $donnees['Nbre_pain_1kg'];
			$nbNormal1kgMoule += $donnees['Nbre_pain_moule_1kg'];
			$nbNormal500g += $donnees['Nbre_pain_500g'];
			$nbGraine1kgmoule += $donnees['Nbre_pain_graines_moule_1kg'];
			$nbGraine500g += $donnees['Nbre_pain_graines_500g'];
			$nbSansGluten500g += $donnees['Nbre_pain_sans_gluten_500g'];
			$nb = $donnees['Nbre_pain_1kg']+ $donnees['Nbre_pain_moule_1kg']+$donnees['Nbre_pain_graines_moule_1kg'];
			$nb2 = $donnees['Nbre_pain_500g']+ $donnees['Nbre_pain_graines_500g'];  
			$nb3 = $donnees['Nbre_pain_sans_gluten_500g'];
			break;	
		case 'amap_agrumes':
			$pdf->CellNumber($cellWidth,$sautLigne,$donnees['Nbre_demi_cagette'],1,0,'C',true);
			$nb= $donnees['Nbre_demi_cagette'];
			break;
		case 'amap_champignons':
			//$pdf->CellNumber($cellWidth,$sautLigne,$donnees['Poids_par_mois'],1,0,'C',true);
			//$nb= $donnees['Poids_par_mois'];
			$nb=0;
			$val= $donnees['Poids_par_quinzaine']." kg";			
			$pdf->CellNumber($cellWidth,$sautLigne,$val,1,0,'C',true);
			$nb2= $donnees['Poids_par_quinzaine'];
			break;	
	}
  
	if ( $_GET['amap'] != 'amap_pain' && $_GET['amap'] !='amap_pommes') {
		$pdf->Cell($cellWidth,$sautLigne,$donnees['Date_debut_contrat'],1,0,'C',true);
		$pdf->Cell($cellWidth,$sautLigne,$donnees['Date_fin_contrat'],1,0,'C',true);
		$pdf->Cell($smallCellWidth,5,$donnees['Nbre_cheque'],1,0,'C',true);
	}

	$pdf->Ln($sautLigne);
	$total= $total+ $nb;
	$total2= $total2+ $nb2;
	$total3= $total3+ $nb3;
}

$pdf->SetFillColor(220,220,220);
$pdf->Cell($largeCellWidth*2+$cellWidth+$smallCellWidth,$sautLigne,'TOTAL',1,0,'C',true);
switch ($_GET['amap']) {
	case 'amap_oeufs' :
		$pdf->Cell($smallCellWidth,$sautLigne,$total,1,0,'C',true);
		$pdf->Cell($cellWidth,$sautLigne,$total2,1,0,'C',true);
		$pdf->Cell($smallCellWidth,$sautLigne,$total3,1,0,'C',true);
		break;
	case 'amap_champignons' :
		$pdf->Cell($cellWidth,$sautLigne,$total2.' kg',1,0,'C',true);
		break;
	case'amap_pain' :
		$pdf->Cell($smallCellWidth-2,$sautLigne,$nbNormal1kg,1,0,'C',true);
		$pdf->Cell($smallCellWidth+4,$sautLigne,$nbNormal1kgMoule,1,0,'C',true); 
		$pdf->Cell($smallCellWidth-2,$sautLigne,$nbNormal500g,1,0,'C',true);  
		$pdf->Cell($smallCellWidth+4,$sautLigne,$nbGraine1kgmoule,1,0,'C',true);  
		$pdf->Cell($smallCellWidth-2,$sautLigne,$nbGraine500g,1,0,'C',true);
		$pdf->Cell($smallCellWidth-2,$sautLigne,$nbSansGluten500g,1,0,'C',true);
		$pdf->Ln(5);  
		$pdf->Cell($smallCellWidth*2+$largeCellWidth*2+$cellWidth,5,$total2+$total+$total3,1,0,'C',true);
		break;
	case 'amap_pommes' :
		$pdf->Cell($smallCellWidth,$sautLigne,$nbdoux,1,0,'C',true);
		$pdf->Cell($smallCellWidth,$sautLigne,$nbacide,1,0,'C',true); 
		$pdf->Cell($smallCellWidth,$sautLigne,$nbalterne,1,0,'C',true);  
		$pdf->Cell($smallCellWidth,$sautLigne,$nbjusnature,1,0,'C',true);  
		$pdf->Cell($smallCellWidth,$sautLigne,$nbjuscitron,1,0,'C',true);  
		$pdf->Cell($smallCellWidth,$sautLigne,$nbjuscanelle,1,0,'C',true);
		$pdf->Ln(5);  
		$pdf->Cell($smallCellWidth+$largeCellWidth*2+$cellWidth,$sautLigne,'',1,0,'C',true);
		$pdf->Cell($smallCellWidth*3,$sautLigne,$nbdoux+$nbacide+$nbalterne,1,0,'C',true);
		$pdf->Cell($smallCellWidth*3,$sautLigne,$nbjusnature+$nbjuscitron+$nbjuscanelle,1,0,'C',true);
		break;
	default :
		$pdf->Cell($smallCellWidth,$sautLigne,$total,1,0,'C',true);
}
$pdf->Ln(5);

$pdf->Output('Liste_'.$_GET['amap'].'-du-'.date("d-M-Y").'.pdf','D');
?>




