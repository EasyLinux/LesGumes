<?php
include_once("webmaster/define.php");
include_once("fonctions_tisanes.php");
require('fpdf.php');

$pdf=new FPDF('L','mm','A4');
$pdf->AddPage();
$pdf->SetTopMargin(0.0);
$pdf->SetAutoPageBreak(true,0); 
$dateProchaineCommande = $_GET['date'];
$sizeNom = 45;
$sizeEmargement = 15;
$sizeCol = 11;
$beginPdt =  $sizeNom +  $sizeEmargement;

function entete1($pdf, $tabTypeProduit, $sizeCol) {
    $pdf->SetFont('Arial','',8);
		// Partie du tableau indiquant le type des produits et dessous les produits
		for ($cpt=1; $cpt <= 4; $cpt++) {
			$nbProd = $tabTypeProduit[$cpt];
			if ($nbProd==0) {
				// Pas de produits pour ce type : ne rien faire
			} else {
				if ($cpt==1) {
				$txt = "Unitaires";
				} elseif ($cpt==2) {
				$txt = "Petites";
				} elseif ($cpt==3) {
				$txt = "Grandes";
				} elseif ($cpt==4) {
				$txt = "Sirops";
				}  
				$pdf->Cell($sizeCol*$nbProd,5,$txt,1,0,'C',true);
			}			
		} 
		$pdf->SetFont('Arial','',12);
}

function entete2($pdf, $tabNomProduit, $nombreProd, $sizeCol) {
		$pdf->SetFont('Arial','',8);
		// Partie du tableau indiquant le type des produits et dessous les produits
		for ($cpt=1; $cpt <= $nombreProd; $cpt++) {
			$nomProd = $tabNomProduit[$cpt];
			// extraction des 7 premiers caractères
			$debut = substr($nomProd,0, 7);
			if ($debut=="petite " || $debut=="grande " ) {
				// on supprime le prefixe 'petite ' / 'grande '
				$nomProd = substr($nomProd,7);
			} else {
				$debut = substr($nomProd,0, 6);
				if ($debut=="sirop ") {
					// on supprime le prefixe 'sirop '
					$nomProd = substr($nomProd,6);
				}
			}
			// On ne garde que 8 caractères pour ne pas prendre trop de place
			$nomProd = substr($nomProd,0,8);
			$pdf->Cell($sizeCol,5,$nomProd,1,0,'C',true);
		}
		$pdf->SetFont('Arial','',12);
}


mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysql_select_db(base_de_donnees); // Sélection de la base 


$pdf->SetFont('Arial','B',12);
$pdf->Cell(278,10,'AMAP SAINT SEBASTIEN --------- LIVRAISON DU '.$dateProchaineCommande,0,1,'C');

$pdf->SetFont('Arial','',12);
$pdf->SetFillColor(220,220,220);
$pdf->Cell($sizeNom,10,'Nom',1,0,'C',true);
$pdf->Cell($sizeEmargement,10,'Emarg.',1,0,'C',true);

$tab = computeProducts($dateProchaineCommande);

$nombreProd= $tab['nombreProd'];
$tabNomProduit= $tab['tabNomProduit'];
$tabIndice= $tab['tabIndice'];
$tabTypeProduit= $tab['tabTypeProduit'];

// Dans cette version de PHP, l'appel de fonction ne fonctionne pas avec un objet de type PDF
//entete1($pdf, $tabTypeProduit, $sizeCol);
// Duplication temporaire de tout le code de la méthode
    $pdf->SetFont('Arial','',8);
		// Partie du tableau indiquant le type des produits et dessous les produits
		for ($cpt=1; $cpt <= 4; $cpt++) {
			$nbProd = $tabTypeProduit[$cpt];
			if ($nbProd==0) {
				// Pas de produits pour ce type : ne rien faire
			} else {
				if ($cpt==1) {
				$txt = "Unitaires";
				} elseif ($cpt==2) {
				$txt = "Petites";
				} elseif ($cpt==3) {
				$txt = "Grandes";
				} elseif ($cpt==4) {
				$txt = "Sirops";
				}  
				$pdf->Cell($sizeCol*$nbProd,5,$txt,1,0,'C',true);
			}			
		} 
		$pdf->SetFont('Arial','',12);
// fin duplication

$pdf->Cell($sizeEmargement,10,'Total',1,1,'C',true);
$pdf->Ln(-5);
$pdf->Cell($sizeNom,5,'',0,0);
$pdf->Cell($sizeEmargement,5,'',0,0);

// Dans cette version de PHP, l'appel de fonction ne fonctionne pas avec un objet de type PDF
//entete2($pdf, $tabNomProduit, $nombreProd, $sizeCol);
// Duplication temporaire de tout le code de la méthode

		$pdf->SetFont('Arial','',8);
		// Partie du tableau indiquant le type des produits et dessous les produits
		for ($cpt=1; $cpt <= $nombreProd; $cpt++) {
			$nomProd = $tabNomProduit[$cpt];
			// extraction des 7 premiers caractères
			$debut = substr($nomProd,0, 7);
			if ($debut=="petite " || $debut=="grande " ) {
				// on supprime le prefixe 'petite ' / 'grande '
				$nomProd = substr($nomProd,7);
			} else {
				$debut = substr($nomProd,0, 6);
				if ($debut=="sirop ") {
					// on supprime le prefixe 'sirop '
					$nomProd = substr($nomProd,6);
				}
			}
			// On ne garde que 8 caractères pour ne pas prendre trop de place
			$nomProd = substr($nomProd,0,8);
			$pdf->Cell($sizeCol,5,$nomProd,1,0,'C',true);
		}
		$pdf->SetFont('Arial','',12);
// fin duplication


$pdf->Cell($sizeEmargement,5,'',0,0);
$pdf->Ln(5);

$tab = computeCommandes($dateProchaineCommande);
$tabCommandes = $tab['tabCommandes'];
$noms = $tab['noms'];
$totalColonne = $tab['totalColonne'];

mysql_close();

// il ne reste plus qu'à parcourir le tableau pour afficher les commandes
$total1=0;
$j=0;
foreach($tabCommandes as $idPers => $value) {
	if($j % 3==1) $pdf->SetFillColor(64,167,245);
	elseif ($j % 3==2) $pdf->SetFillColor(159,245,128);
	else $pdf->SetFillColor(255,255,255);
	$j++;
	$pdf->Cell($sizeNom,5,$noms[$idPers],1,0,'C',true);
	$pdf->Cell($sizeEmargement,5,'',1,0,'C',true);

	$totalLigne =0;
	for ($cpt=1; $cpt <= $nombreProd; $cpt++) {
		$quantite = $value[$tabIndice[$cpt]];
		$totalLigne += $quantite;
		$pdf->Cell($sizeCol,5,$quantite,1,0,'C',true);
	} 
	$total1 += $totalLigne; 
	$pdf->Cell($sizeEmargement,5,$totalLigne,1,1,'C',true);
} 

if($j % 3==1) $pdf->SetFillColor(64,167,245);
elseif ($j % 3==2) $pdf->SetFillColor(159,245,128);
else $pdf->SetFillColor(255,255,255);

$pdf->Cell($sizeNom,5,'Total',1,0,'C',true);
$pdf->Cell($sizeEmargement,5,'',1,0,'C',true);

$totalLigne =0;
for ($cpt=1; $cpt <= $nombreProd; $cpt++) {
	$pdf->Cell($sizeCol,5,$totalColonne[$tabIndice[$cpt]],1,0,'C',true);
} 
$pdf->Cell($sizeEmargement,5,$total1,1,1,'C',true);


$pdf->SetFillColor(220,220,220);
$pdf->Cell($sizeNom,10,'Nom',1,0,'C',true);
$pdf->Cell($sizeEmargement,10,'Emarg.',1,0,'C',true);

// Dans cette version de PHP, l'appel de fonction ne fonctionne pas avec un objet de type PDF
//entete2($pdf, $tabNomProduit, $nombreProd, $sizeCol);
// Duplication temporaire de tout le code de la méthode

		$pdf->SetFont('Arial','',8);
		// Partie du tableau indiquant le type des produits et dessous les produits
		for ($cpt=1; $cpt <= $nombreProd; $cpt++) {
			$nomProd = $tabNomProduit[$cpt];
			// extraction des 7 premiers caractères
			$debut = substr($nomProd,0, 7);
			if ($debut=="petite " || $debut=="grande " ) {
				// on supprime le prefixe 'petite ' / 'grande '
				$nomProd = substr($nomProd,7);
			} else {
				$debut = substr($nomProd,0, 6);
				if ($debut=="sirop ") {
					// on supprime le prefixe 'sirop '
					$nomProd = substr($nomProd,6);
				}
			}
			// On ne garde que 8 caractères pour ne pas prendre trop de place
			$nomProd = substr($nomProd,0,8);
			$pdf->Cell($sizeCol,5,$nomProd,1,0,'C',true);
		}
		$pdf->SetFont('Arial','',12);
// fin duplication

$pdf->Cell($sizeEmargement,10,'Total',1,1,'C',true);
$pdf->Ln(-5);
$pdf->Cell($sizeNom,5,'',0,0);
$pdf->Cell($sizeEmargement,5,'',0,0);

// Dans cette version de PHP, l'appel de fonction ne fonctionne pas avec un objet de type PDF
//entete1($pdf, $tabTypeProduit, $sizeCol);
// Duplication temporaire de tout le code de la méthode
    $pdf->SetFont('Arial','',8);
		// Partie du tableau indiquant le type des produits et dessous les produits
		for ($cpt=1; $cpt <= 4; $cpt++) {
			$nbProd = $tabTypeProduit[$cpt];
			if ($nbProd==0) {
				// Pas de produits pour ce type : ne rien faire
			} else {
				if ($cpt==1) {
				$txt = "Unitaires";
				} elseif ($cpt==2) {
				$txt = "Petites";
				} elseif ($cpt==3) {
				$txt = "Grandes";
				} elseif ($cpt==4) {
				$txt = "Sirops";
				}  
				$pdf->Cell($sizeCol*$nbProd,5,$txt,1,0,'C',true);
			}			
		} 
		$pdf->SetFont('Arial','',12);
// fin duplication
$pdf->Ln(5);

$pdf->SetFont('Arial','',8);
$auj=date("d-m-y",time());
$pdf->Cell(278,5,"date d'impression : ".$auj,0,0,'C');

$pdf->Output('StSeb-Livraison-du-'.$ProchLiv.'.pdf','D');
?>