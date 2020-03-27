<?php
include_once("define.php");
require('../fpdf.php');
$pdf=new FPDF('L','mm','a4');
$pdf->SetTopMargin(5);
$pdf->SetAutoPageBreak(false, 5);
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);

mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
mysql_select_db(base_de_donnees); // S�lection de la base 

// les amapiens inscrits � ce contrat : le LEFT Join permet de r�cup�rer le nom du bin�me s'il existe

$question="SELECT g.id, g.Nom, g.Prenom, g.Tel_portable, g.Telephone, b.nom_binome FROM " ;
$question=$question.$_GET['amap'];
$question=$question." l, amap_generale g LEFT JOIN binome b ON (b.id_contrat=g.id AND b.type_amap='";
$question=$question.$_GET['amap']."') WHERE g.id= l.id ORDER BY  g.Nom";
$reponse = mysql_query($question) or die(mysql_error());
$ligne = mysql_num_rows($reponse);

$pdf->Cell(278,10,'AMAP LesGUMES Saint S�bastien - Contrat '.$_GET['amap'].'             '.$ligne.' INSCRITS',0,1,'C');

$pdf->SetFont('Arial','',10);

$smallCellWidth = 13;  //taille par d�faut des cellules
$largeCellWidth = 60;  //taille par d�faut des cellules
$cellWidth = 30;  //taille par d�faut des cellule
$cellHeight = 8; // hauteur des lignes
$nbLigneParPage = 22;
$j=0;

while($donnees = mysql_fetch_array($reponse)) {

	if ( $donnees['id'] == '303') {
		//ATTENTION : l'amapien 303 est mis en fin de feuille car il a des probl�mes de vue ...
		continue;
	}

	if(($j!=0 && $j== $nbLigneParPage)|| ((($j-$nbLigneParPage) % ($nbLigneParPage+2)) ==0)) {
	 // 37 lignes sur la premi�re page, 39 sur les suivantes
	 $pdf->AddPage();       //changement de page
	}
 	if( $j ==0 || $j == $nbLigneParPage || (($j -$nbLigneParPage) % ($nbLigneParPage+2) ==0)) { //mettre l'ent�te du tableau
 	  //sur la premi�re page, la deuxi�me et les suivantes ...
		$pdf->SetFillColor(220,220,220);
		$pdf->Cell(5,$cellHeight,'',1,0,'C',true);	
		$pdf->Cell($largeCellWidth,$cellHeight,'Nom et pr�nom',1,0,'C',true);			
		$pdf->Cell($cellWidth,$cellHeight,'T�l�phone',1,0,'C',true);
                                  
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

	// calcul du nom � afficher 
	$binome = $donnees['nom_binome'];
	if (strlen($binome) >0) {
		//si il y a un binome on met le nom des 2 bin�mes sans les pr�noms
		$nom = $donnees['Nom'].'--'.$binome;
	} else { //sinon on met le nom et le pr�nom 
		$nom = $donnees['Nom'].' '.$donnees['Prenom'];
	}
	//on tronque pour �viter les d�passements...
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




