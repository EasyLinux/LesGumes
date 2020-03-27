<?php

function setClair($j, $doc)
{
	if($j % 3==1) {
	$doc->SetFillColor(104,207,245);}
	elseif ($j % 3==2) {$doc->SetFillColor(159,245,128);}
	else {$doc->SetFillColor(255,255,255);}
	return $doc;
}

function setFonce($j, $doc)
{
	if($j % 3==1) {
	$doc->SetFillColor(64,167,245);}
	elseif ($j % 3==2) {$doc->SetFillColor(159,245,128);}
	else {$doc->SetFillColor(255,255,255);}
	return $doc;
}

include_once("webmaster/define.php");
require('fpdf.php');

$pdf=new FPDF('L','mm','A4');
$pdf->AddPage();
$pdf->SetTopMargin(0.0);
$pdf->SetAutoPageBreak(true,0); 

mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysql_select_db(base_de_donnees); // Sélection de la base 
$question="SELECT Date_livraison FROM amap_produits_laitiers_cde_en_cours";
$reponse = mysql_query($question);
$donnees=mysql_fetch_array($reponse);
$ProchLiv=date("d-M-Y",strtotime($donnees[0]));
$auj=date("d-m-y",time());

mysql_close();

$sizeNom = 45;
$sizeUnite = 11;
$sizeEmargement = 15;
$beginPdt =  $sizeNom +  $sizeUnite + $sizeEmargement;

$pdf->SetFont('Arial','B',12);
$pdf->Cell(278,10,'AMAP SAINT SEBASTIEN --------- LIVRAISON DU '.$ProchLiv,0,1,'C');

$pdf->SetFont('Arial','',12);
$pdf->SetFillColor(220,220,220);
$pdf->Cell($sizeNom,10,'Nom',1,0,'C',true);
$pdf->Cell($sizeUnite,10,'Unité',1,0,'C',true);
$pdf->Cell($sizeEmargement,10,'Emarg.',1,0,'C',true);
$pdf->Cell(14,5,'Beurre',1,0,'C',true);
$pdf->Cell(15,10,'Crème',1,0,'C',true);
$pdf->Cell(66,5,'Yaourts',1,0,'C',true);
$pdf->Cell(18,5,'Frg frais',1,0,'C',true);
$pdf->Cell(20,5,'Bléruchon',1,0,'C',true);
$pdf->Cell(30,5,'Fromage blanc',1,0,'C',true);
$pdf->Cell(10,5,'Lait','LTR',0,'C',true);
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(10,5,'Lait','LTR',0,'C',true);
//$pdf->SetTextColor (0,0,0);
$pdf->Cell(12,5,'Lait','LTR',0,'C',true);
$pdf->Cell(10,10,'total','LTR',0,'C',true);
$pdf->Ln(5);
$pdf->Cell($beginPdt,5,'',0,0);
$pdf->Cell(7,5,'S',1,0,'C',true);
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(7,5,'D',1,0,'C',true);
//$pdf->SetTextColor (0,0,0);
$pdf->Cell(15,5,'',0,0);
$pdf->Cell(11,5,'5nat',1,0,'C',true);
$pdf->Cell(11,5,'4suc',1,0,'C',true);
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(8,5,'4A',1,0,'C',true);
//$pdf->SetTextColor (0,0,0);
$pdf->Cell(8,5,'4V',1,0,'C',true);
$pdf->Cell(8,5,'4C',1,0,'C',true);
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(10,5,'4frb',1,0,'C',true); 
$pdf->SetTextColor (0,0,0);
$pdf->Cell(10,5,'4frs',1,0,'C',true);
$pdf->Cell(9,5,'nat',1,0,'C',true);
$pdf->Cell(9,5,'herb',1,0,'C',true);
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(10,5,'ptit',1,0,'C',true);
//$pdf->SetTextColor (0,0,0);
$pdf->Cell(10,5,'grd',1,0,'C',true);
$pdf->Cell(10,5,'fslle',1,0,'C',true);
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(10,5,'lissé',1,0,'C',true);
//$pdf->SetTextColor (0,0,0);
$pdf->Cell(10,5,'mgre',1,0,'C',true);
$pdf->Cell(10,5,'2L','LBR',0,'C',true);
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(10,5,'ribot','LBR',0,'C',true);
//$pdf->SetTextColor (0,0,0);
$pdf->Cell(12,5,'1L+2Y','LBR',0,'C',true);
$pdf->Ln(5);

//affichage de la table cde_en_cours qui contient les produits de la prochaine livraison
mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysql_select_db(base_de_donnees); // Sélection de la base 
$question="SELECT * FROM amap_produits_laitiers_cde_en_cours ORDER BY Nom";
$reponse = mysql_query($question);
$ligne = mysql_num_rows($reponse);
mysql_close();
$totgene=0;
while($donnees = mysql_fetch_array($reponse)) {
	$j++;
	$totunit=0;
	$pdf->SetFont('Arial','',10);
	$pdf = setFonce($j, $pdf);

  $nom= $donnees['Nom'].' '.$donnees['Prenom'];
  if (strlen($nom) > 20 ) {
     $nom = substr($nom,0,20);
     $nom = $nom.'.';
  }

  $pdf->Cell($sizeNom,5,$nom,1,0,'C',true);
$pdf = setClair($j, $pdf);
	$pdf->Cell($sizeUnite,5,$donnees['Unite'],1,0,'C',true);
	$pdf->Cell($sizeEmargement,5,'',1,0,'C',true);
$pdf = setFonce($j, $pdf);
	if($donnees['Beurre_160g_sale']!=0) {$totunit+=$donnees['Beurre_160g_sale']; $pdf->Cell(7,5,$donnees['Beurre_160g_sale'],1,0,'C',true);} else $pdf->Cell(7,5,'',1,0,'C',true);
	if($donnees['Beurre_160g_doux']!=0) {$totunit+=$donnees['Beurre_160g_doux']; $pdf->Cell(7,5,$donnees['Beurre_160g_doux'],1,0,'C',true);} else $pdf->Cell(7,5,'',1,0,'C',true);
$pdf = setClair($j, $pdf);
	if($donnees['Creme_250g']!=0) {$totunit+=$donnees['Creme_250g']; $pdf->Cell(15,5,$donnees['Creme_250g'],1,0,'C',true);} else $pdf->Cell(15,5,'',1,0,'C',true);
	if($donnees['Yaourt_nature_5x125g']!=0) {$totunit+=$donnees['Yaourt_nature_5x125g']; $pdf->Cell(11,5,$donnees['Yaourt_nature_5x125g'],1,0,'C',true);} else $pdf->Cell(11,5,'',1,0,'C',true);
$pdf = setFonce($j, $pdf);
	if($donnees['Yaourt_nature_sucre_4x125g']!=0) {$totunit+=$donnees['Yaourt_nature_sucre_4x125g']; $pdf->Cell(11,5,$donnees['Yaourt_nature_sucre_4x125g'],1,0,'C',true);} else $pdf->Cell(11,5,'',1,0,'C',true);
	if($donnees['Yaourt_aromatise_4x125g_abricot']!=0) {$totunit+=$donnees['Yaourt_aromatise_4x125g_abricot']; $pdf->Cell(8,5,$donnees['Yaourt_aromatise_4x125g_abricot'],1,0,'C',true);} else $pdf->Cell(8,5,'',1,0,'C',true);
$pdf = setClair($j, $pdf);
	if($donnees['Yaourt_aromatise_4x125g_vanille']!=0) {$totunit+=$donnees['Yaourt_aromatise_4x125g_vanille']; $pdf->Cell(8,5,$donnees['Yaourt_aromatise_4x125g_vanille'],1,0,'C',true);} else $pdf->Cell(8,5,'',1,0,'C',true);
	if($donnees['Yaourt_aromatise_4x125g_citron']!=0) {$totunit+=$donnees['Yaourt_aromatise_4x125g_citron']; $pdf->Cell(8,5,$donnees['Yaourt_aromatise_4x125g_citron'],1,0,'C',true);} else $pdf->Cell(8,5,'',1,0,'C',true);
$pdf = setFonce($j, $pdf);
	if($donnees['Yaourt_aromatise_4x125g_framboise']!=0) {$totunit+=$donnees['Yaourt_aromatise_4x125g_framboise']; $pdf->Cell(10,5,$donnees['Yaourt_aromatise_4x125g_framboise'],1,0,'C',true);} else $pdf->Cell(10,5,'',1,0,'C',true);
	if($donnees['Yaourt_aromatise_4x125g_fraise']!=0) {$totunit+=$donnees['Yaourt_aromatise_4x125g_fraise']; $pdf->Cell(10,5,$donnees['Yaourt_aromatise_4x125g_fraise'],1,0,'C',true);} else $pdf->Cell(10,5,'',1,0,'C',true);
$pdf = setClair($j, $pdf);
	if($donnees['Fromage_frais_nature']!=0) {$totunit+=$donnees['Fromage_frais_nature']; $pdf->Cell(9,5,$donnees['Fromage_frais_nature'],1,0,'C',true);} else $pdf->Cell(9,5,'',1,0,'C',true);
	if($donnees['Fromage_frais_herbes_150g']!=0) {$totunit+=$donnees['Fromage_frais_herbes_150g']; $pdf->Cell(9,5,$donnees['Fromage_frais_herbes_150g'],1,0,'C',true);} else $pdf->Cell(9,5,'',1,0,'C',true);
$pdf = setFonce($j, $pdf);
	if($donnees['Bleruchon_100_a_150g']!=0) {$totunit+=$donnees['Bleruchon_100_a_150g']; $pdf->Cell(10,5,$donnees['Bleruchon_100_a_150g'],1,0,'C',true);} else $pdf->Cell(10,5,'',1,0,'C',true);
	if($donnees['Bleruchon_225_a_275g']!=0) {$totunit+=2*$donnees['Bleruchon_225_a_275g']; $pdf->Cell(10,5,$donnees['Bleruchon_225_a_275g'],1,0,'C',true);} else $pdf->Cell(10,5,'',1,0,'C',true);
$pdf = setClair($j, $pdf);
	if($donnees['Faisselle_500g']!=0) {$totunit+=$donnees['Faisselle_500g']; $pdf->Cell(10,5,$donnees['Faisselle_500g'],1,0,'C',true);} else $pdf->Cell(10,5,'',1,0,'C',true);
	if($donnees['Fromage_blanc_500g']!=0) {$totunit+=$donnees['Fromage_blanc_500g']; $pdf->Cell(10,5,$donnees['Fromage_blanc_500g'],1,0,'C',true);} else $pdf->Cell(10,5,'',1,0,'C',true);
$pdf = setFonce($j, $pdf);
	if($donnees['Fromage_blanc_maigre_500g']!=0) {$totunit+=$donnees['Fromage_blanc_maigre_500g']; $pdf->Cell(10,5,$donnees['Fromage_blanc_maigre_500g'],1,0,'C',true);} else $pdf->Cell(10,5,'',1,0,'C',true);
	if($donnees['Lait_cru_2L']!=0) {$totunit+=$donnees['Lait_cru_2L']; $pdf->Cell(10,5,$donnees['Lait_cru_2L'],1,0,'C',true);} else $pdf->Cell(10,5,'',1,0,'C',true);
$pdf = setClair($j, $pdf);
	if($donnees['Lait_ribot_1L5']!=0) {$totunit+=$donnees['Lait_ribot_1L5']; $pdf->Cell(10,5,$donnees['Lait_ribot_1L5'],1,0,'C',true);} else $pdf->Cell(10,5,'',1,0,'C',true);
	if($donnees['Lait_cru_1L_Yaourt_nature_2x125g']!=0) {$totunit+=$donnees['Lait_cru_1L_Yaourt_nature_2x125g']; $pdf->Cell(12,5,$donnees['Lait_cru_1L_Yaourt_nature_2x125g'],1,0,'C',true);} else $pdf->Cell(12,5,'',1,0,'C',true);
$pdf = setFonce($j, $pdf);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(10,5,$totunit,1,1,'C',true);
	$totgene+=$totunit;
}
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(220,220,220);
$pdf->Cell($sizeNom,5,"Nb unités de vente",1,0,'C',true);



mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysql_select_db(base_de_donnees); // Sélection de la base 

$question = "SELECT SUM( Unite ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
$pdf->Cell($sizeUnite,5,$donnees[0],1,0,'C',true);
$pdf->Cell($sizeEmargement,5,'',1,0,'C',true);
 
$question = "SELECT SUM( Beurre_160g_sale ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
$pdf->Cell(7,5,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Beurre_160g_doux ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(7,5,$donnees[0],1,0,'C',true);
//$pdf->SetTextColor (0,0,0);
$question = "SELECT SUM( Creme_250g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
$pdf->Cell(15,5,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Yaourt_nature_5x125g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
$pdf->Cell(11,5,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Yaourt_nature_sucre_4x125g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
$pdf->Cell(11,5,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Yaourt_aromatise_4x125g_abricot ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(8,5,$donnees[0],1,0,'C',true);
//$pdf->SetTextColor (0,0,0);
$question = "SELECT SUM( Yaourt_aromatise_4x125g_vanille ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
$pdf->Cell(8,5,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Yaourt_aromatise_4x125g_citron ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
$pdf->Cell(8,5,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Yaourt_aromatise_4x125g_framboise ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(10,5,$donnees[0],1,0,'C',true);
//$pdf->SetTextColor (0,0,0);
$question = "SELECT SUM( Yaourt_aromatise_4x125g_fraise ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
$pdf->Cell(10,5,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Fromage_frais_nature ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
$pdf->Cell(9,5,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Fromage_frais_herbes_150g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
$pdf->Cell(9,5,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Bleruchon_100_a_150g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(10,5,$donnees[0],1,0,'C',true);
//$pdf->SetTextColor (0,0,0);
$question = "SELECT SUM( Bleruchon_225_a_275g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
$pdf->Cell(10,5,'2x'.$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Faisselle_500g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
$pdf->Cell(10,5,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Fromage_blanc_500g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(10,5,$donnees[0],1,0,'C',true);
//$pdf->SetTextColor (0,0,0);
$question = "SELECT SUM( Fromage_blanc_maigre_500g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
$pdf->Cell(10,5,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Lait_cru_2L ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
$pdf->Cell(10,5,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Lait_ribot_1L5 ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(10,5,$donnees[0],1,0,'C',true);
//$pdf->SetTextColor (0,0,0);
$question = "SELECT SUM( Lait_cru_1L_Yaourt_nature_2x125g  ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysql_fetch_array(mysql_query($question));
$pdf->Cell(12,5,$donnees[0],1,0,'C',true);

$pdf->Cell(10,5,$totgene,1,0,'C',true);

mysql_close();

$pdf->SetFont('Arial','',12);
$pdf->Ln(5);
$pdf->Cell($beginPdt,10,'',1,0,'C',true);
$pdf->Cell(7,5,'S',1,0,'C',true);
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(7,5,'D',1,0,'C',true);
//$pdf->SetTextColor (0,0,0);
$pdf->Cell(15,10,'Crème',1,0,'C',true);
$pdf->Cell(11,5,'5nat',1,0,'C',true);
$pdf->Cell(11,5,'4suc',1,0,'C',true);
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(8,5,'4A',1,0,'C',true);
//$pdf->SetTextColor (0,0,0);
$pdf->Cell(8,5,'4V',1,0,'C',true);
$pdf->Cell(8,5,'4C',1,0,'C',true);
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(10,5,'4frb',1,0,'C',true);
//$pdf->SetTextColor (0,0,0);
$pdf->Cell(10,5,'4frs',1,0,'C',true);
$pdf->Cell(9,5,'nat',1,0,'C',true);
$pdf->Cell(9,5,'herb',1,0,'C',true);
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(10,5,'ptit',1,0,'C',true);
//$pdf->SetTextColor (0,0,0);
$pdf->Cell(10,5,'grd',1,0,'C',true);
$pdf->Cell(10,5,'fslle',1,0,'C',true);
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(10,5,'lissé',1,0,'C',true);
//$pdf->SetTextColor (0,0,0);
$pdf->Cell(10,5,'mgre',1,0,'C',true);
$pdf->Cell(10,5,'Lait','LTR',0,'C',true);
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(10,5,'Lait','LTR',0,'C',true);
//$pdf->SetTextColor (0,0,0);
$pdf->Cell(12,5,'Lait','LTR',0,'C',true);
$pdf->Cell(10,10,'total',1,0,'C',true);
$pdf->Ln(5);
$pdf->Cell($beginPdt,5,'',0,0);
$pdf->Cell(14,5,'Beurre',1,0,'C',true);
$pdf->Cell(15,5,'',0,0);
$pdf->Cell(66,5,'Yaourts',1,0,'C',true);
$pdf->Cell(18,5,'Frg frais',1,0,'C',true);
$pdf->Cell(20,5,'Bléruchon',1,0,'C',true);
$pdf->Cell(30,5,'Fromage blanc',1,0,'C',true);
$pdf->Cell(10,5,'2L','LBR',0,'C',true);
//$pdf->SetTextColor (255,0,0);
$pdf->Cell(10,5,'ribot','LBR',0,'C',true);
//$pdf->SetTextColor (0,0,0);
$pdf->Cell(12,5,'1L+2Y','LBR',0,'C',true);
$pdf->Ln(5);

$pdf->SetFont('Arial','',8);
$auj=date("d-m-y",time());
$pdf->Cell(278,5,"date d'impression : ".$auj,0,0,'C');

$pdf->Output('StSeb-Livraison-du-'.$ProchLiv.'.pdf','D');
?>