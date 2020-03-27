<?php
include_once("define.php");
require('../fpdf.php');
$pdf=new FPDF('L','mm','a4');
$pdf->SetTopMargin(5);
$pdf->SetAutoPageBreak(false, 5);
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysql_select_db(base_de_donnees); // Sélection de la base 

// les amapiens inscrits à ce contrat : le LEFT Join permet de récupérer le nom du binôme s'il existe

$question="SELECT g.id, g.Nom, g.Prenom, g.Tel_portable, g.Telephone, b.nom_binome FROM " ;
$question=$question.$_GET['amap'];
$question=$question." l, amap_generale g LEFT JOIN binome b ON (b.id_contrat=g.id AND b.type_amap='";
$question=$question.$_GET['amap']."') WHERE g.id= l.id ORDER BY  g.Nom";
$reponse = mysql_query($question) or die(mysql_error());
$ligne = mysql_num_rows($reponse);

$pdf->Cell(278,10,'AMAP LesGUMES Saint Sébastien - Contrat '.$_GET['amap'].'             '.$ligne.' INSCRITS',0,1,'C');

$pdf->SetFont('Arial','',10);

$smallCellWidth = 13;  //taille par défaut des cellules
$largeCellWidth = 60;  //taille par défaut des cellules
$cellWidth = 30;  //taille par défaut des cellule
$cellHeight = 8; // hauteur des lignes
$nbLigneParPage = 22;
$j=0;

while($donnees = mysql_fetch_array($reponse)) {

	if ( $donnees['id'] == '303') {
		//ATTENTION : l'amapien 303 est mis en fin de feuille car il a des problèmes de vue ...
		continue;
	}

	if(($j!=0 && $j== $nbLigneParPage)|| ((($j-$nbLigneParPage) % ($nbLigneParPage+2)) ==0)) {
	 // 37 lignes sur la première page, 39 sur les suivantes
	 $pdf->AddPage();       //changement de page
	}
 	if( $j ==0 || $j == $nbLigneParPage || (($j -$nbLigneParPage) % ($nbLigneParPage+2) ==0)) { //mettre l'entête du tableau
 	  //sur la première page, la deuxième et les suivantes ...
		$pdf->SetFillColor(220,220,220);
		$pdf->Cell(5,$cellHeight,'',1,0,'C',true);	
		$pdf->Cell($largeCellWidth,$cellHeight,'Nom et prénom',1,0,'C',true);			
		$pdf->Cell($cellWidth,$cellHeight,'Téléphone',1,0,'C',true);
                                  
		// les dates
		$questionDate="SELECT DATE_FORMAT(date, '%d/%m/%Y') AS date FROM ".$_GET['amap']."_permanences WHERE Date >='".date("Y-m-d")."' ";
		$reponseDate = mysql_query($questionDate) or die(mysql_error());
		
		$nbdate = mysql_num_rows($reponseDate);
		if ( $nbdate > 6) $nbdate = 6;
		for ($i = 0; $i < $nbdate; $i++) {
		  $dates = mysql_fetch_array($reponseDate);
		  $pdf->Cell($cellWidth,$cellHeight,$dates['date'],1,0,'C',true);
		}
		
			$pdf->Ln($cellHeight);
	}
	$j++;
	
	
	// couleur de fond pour les lignes 
	if($j % 3==1) $pdf->SetFillColor(255,255,204);
	elseif ($j % 3==2) $pdf->SetFillColor(204,255,255);
	else $pdf->SetFillColor(255,255,255);

	$pdf->Cell(5,$cellHeight,$j,1,0,'C',true);	

	// calcul du nom à afficher 
	$binome = $donnees['nom_binome'];
	if (strlen($binome) >0) {
		//si il y a un binome on met le nom des 2 binômes sans les prénoms
		$nom = $donnees['Nom'].'--'.$binome;
	} else { //sinon on met le nom et le prénom 
		$nom = $donnees['Nom'].' '.$donnees['Prenom'];
	}
	//on tronque pour éviter les dépassements...
	if (strlen($nom) > 22 ) {
		$nom = substr($nom,0,22);
		$nom = $nom.'.';
	}
	
	$pdf->Cell($largeCellWidth,$cellHeight,$nom,1,0,'C',true);
	$tel = $donnees['Tel_portable'];
	if ( $tel == '' ) {
      $tel =  $donnees['Telephone'];
	}
	$pdf->Cell($cellWidth,$cellHeight,$tel.' ',1,0,'C',true);

	// cellule d'emargement par dates
	for ($i = 0; $i < $nbdate; $i++) {
	  $pdf->Cell($cellWidth,$cellHeight,' ',1,0,'C',true);
	}

	$pdf->Ln($cellHeight);

}

mysql_close();

$pdf->Output('Emargements'.$_GET['amap'].'_'.date("d-M-Y").'.pdf','D');
?>




