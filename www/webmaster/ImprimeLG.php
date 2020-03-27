<?php
include_once("define.php");
require('../fpdf.php');
$pdf=new FPDF('L','mm','A4');
$pdf->AddPage();
$pdf->SetTopMargin(0.0);
$pdf->SetFont('Arial','B',14);

mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysql_select_db(base_de_donnees); // Sélection de la base 
$question="SELECT id, Nom, Prenom, e_mail, Telephone, Tel_portable, Date_inscription FROM amap_generale ORDER BY Nom";
$reponse = mysql_query($question);
$ligne = mysql_num_rows($reponse);
mysql_close();

$pdf->Cell(278,10,'AMAP SAINT SEBASTIEN --------- LISTE DES ADHERENTS --------- '.date("d-M-Y").' --------- '.$ligne.' INSCRITS',0,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->SetFillColor(220,220,220);
$pdf->Cell(8,5,'Id',1,0,'C',true);
$pdf->Cell(80,5,'Nom',1,0,'C',true);
$pdf->Cell(70,5,'Courriel',1,0,'C',true);
$pdf->Cell(30,5,'Telephone-1',1,0,'C',true);
$pdf->Cell(30,5,'Telephone-2',1,0,'C',true);
$pdf->Cell(40,5,'Date inscription',1,0,'C',true);
$pdf->Ln(5);

while($donnees = mysql_fetch_array($reponse)) {
	$j++;
	if($j % 31==0) {
		$pdf->AddPage();
		$pdf->SetTopMargin(0.0);
		$pdf->SetFont('Arial','',12);
		$pdf->SetFillColor(220,220,220);
		$pdf->Cell(278,10,' ',0,1,'C');
		$pdf->Cell(8,5,'Id',1,0,'C',true);
		$pdf->Cell(80,5,'Nom',1,0,'C',true);
		$pdf->Cell(70,5,'Courriel',1,0,'C',true);
		$pdf->Cell(30,5,'Telephone-1',1,0,'C',true);
		$pdf->Cell(30,5,'Telephone-2',1,0,'C',true);
		$pdf->Cell(40,5,'Date inscription',1,0,'C',true);
		$pdf->Ln(5);
	}
	$pdf->SetFont('Arial','',10);
	if($j % 3==1) $pdf->SetFillColor(64,167,245);
	elseif ($j % 3==2) $pdf->SetFillColor(159,245,128);
	else $pdf->SetFillColor(255,255,255);
	$pdf->Cell(8,5,$donnees['id'],1,0,'C',true);
	$pdf->Cell(80,5,$donnees['Nom'].' '.$donnees['Prenom'],1,0,'C',true);
	$pdf->Cell(70,5,$donnees['e_mail'],1,0,'C',true);
	$pdf->Cell(30,5,$donnees['Telephone'],1,0,'C',true);
	$pdf->Cell(30,5,$donnees['Tel_portable'],1,0,'C',true);
	$pdf->Cell(40,5,date("d-M-Y",strtotime($donnees['Date_inscription'])),1,0,'C',true);
	$pdf->Ln(5);
}
$pdf->Output('ListeAdherent-du-'.date("d-M-Y").'.pdf','D');
?>




