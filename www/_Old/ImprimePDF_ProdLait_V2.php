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

$pdf=new FPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetTopMargin(10); // 0.0
$pdf->SetAutoPageBreak(true,10); 

mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysqli_select_db(base_de_donnees); // Sélection de la base 
$question="SELECT Date_livraison FROM amap_produits_laitiers_cde_en_cours";
$reponse = mysqli_query($question);
$donnees=mysqli_fetch_array($reponse);
$ProchLiv=date("d-M-Y",strtotime($donnees[0]));
$auj=date("d-m-y",time());

mysqli_close();


$sizeNom = 50;
$tailleMaxNom = 20;

$sizeUnite = 12;
$hautUn = 4.5; 
$hautTitre = 6;  

$tailleYaourt = 12;
$tailleCreme = 14;
$tailleBeurre = 14;
$tailleTotal = 16;

$pdf->SetFont('Arial','B',12);
$pdf->Cell(150,$hautUn*2,'AMAP SAINT SEB - LIVR. DU '.$ProchLiv.' (Beurre & Yaourts)',1,0,'C');
$pdf->SetFont('Arial','',8);
$auj=date("d-m-y",time());
$pdf->Cell(42,$hautUn*2,"              date d'impression : ".$auj,1,1,'R');
$pdf->SetFont('Arial','B',12);


$pdf->SetFont('Arial','',12);
$pdf->SetFillColor(220,220,220);
$pdf->Cell($sizeNom,$hautTitre*2,'Nom',1,0,'C',true);
$pdf->Cell($tailleBeurre,$hautTitre,'Beurre',1,0,'C',true);
$pdf->Cell($tailleCreme,$hautTitre*2,'Crème',1,0,'C',true);
$pdf->Cell($tailleCreme,$hautTitre,'5 Yao',1,0,'C',true);
$pdf->Cell($tailleYaourt*7,$hautTitre,' 4 Yaourts aromatisés',1,0,'C',true);
$pdf->Cell($tailleTotal,$hautTitre*2,'total','LTR',0,'C',true);
$pdf->Ln($hautTitre);
$pdf->Cell($sizeNom,$hautTitre,'',0,0);
$pdf->Cell($tailleBeurre/2,$hautTitre,'S',1,0,'C',true);
$pdf->Cell($tailleBeurre/2,$hautTitre,'D',1,0,'C',true);
$pdf->Cell($tailleCreme,$hautTitre,'',0,0);
$pdf->Cell($tailleCreme,$hautTitre,'nature',1,0,'C',true);
$pdf->Cell($tailleYaourt,$hautTitre,'peche',1,0,'C',true);
$pdf->Cell($tailleYaourt,$hautTitre,'abrico',1,0,'C',true);
$pdf->Cell($tailleYaourt,$hautTitre,'vanille',1,0,'C',true);
$pdf->Cell($tailleYaourt,$hautTitre,'citron',1,0,'C',true);
$pdf->Cell($tailleYaourt,$hautTitre,'framb',1,0,'C',true); 
$pdf->Cell($tailleYaourt,$hautTitre,'fraise',1,0,'C',true);
$pdf->Cell($tailleYaourt,$hautTitre,'mixte',1,0,'C',true);
$pdf->Ln($hautTitre);

//affichage de la table cde_en_cours qui contient les produits de la prochaine livraison
mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysqli_select_db(base_de_donnees); // Sélection de la base 
$question="SELECT * FROM amap_produits_laitiers_cde_en_cours ORDER BY Nom";
$reponse = mysqli_query($question);
$ligne = mysqli_num_rows($reponse);
mysqli_close();
$totgene=0;
$j=0;
while($donnees = mysqli_fetch_array($reponse)) {
	$j++;
	$totunit=0;
	$pdf->SetFont('Arial','',10);
	$pdf = setFonce($j, $pdf);

  $nom= $donnees['Nom'].' '.$donnees['Prenom'];
  if (strlen($nom) > $tailleMaxNom ) {
     $nom = substr($nom,0,$tailleMaxNom);
     $nom = $nom.'.';
  }

  $pdf->Cell($sizeNom,$hautUn,$nom,1,0,'C',true);
$pdf = setFonce($j, $pdf);
	if($donnees['Beurre_160g_sale']!=0) {$totunit+=$donnees['Beurre_160g_sale']; $pdf->Cell($tailleBeurre/2,$hautUn,$donnees['Beurre_160g_sale'],1,0,'C',true);} else $pdf->Cell($tailleBeurre/2,$hautUn,'',1,0,'C',true);
	if($donnees['Beurre_160g_doux']!=0) {$totunit+=$donnees['Beurre_160g_doux']; $pdf->Cell($tailleBeurre/2,$hautUn,$donnees['Beurre_160g_doux'],1,0,'C',true);} else $pdf->Cell($tailleBeurre/2,$hautUn,'',1,0,'C',true);
$pdf = setClair($j, $pdf);
	if($donnees['Creme_250g']!=0) {$totunit+=$donnees['Creme_250g']; $pdf->Cell($tailleCreme,$hautUn,$donnees['Creme_250g'],1,0,'C',true);} else $pdf->Cell($tailleCreme,$hautUn,'',1,0,'C',true);

	$txt='';
	$totunit+=$donnees['Yaourt_nature_5x125g'];
	if($donnees['Yaourt_nature_5x125g']!=0) $txt=$donnees['Yaourt_nature_5x125g']; else $txt="   ";
	$txt = $txt . "  ";
	$totunit+=$donnees['Yaourt_nature_vrac'];
	if($donnees['Yaourt_nature_vrac']!=0) $txt= $txt . $donnees['Yaourt_nature_vrac'].'p'; else $txt = $txt . "     ";
	$pdf->Cell($tailleCreme,$hautUn,$txt,1,0,'C',true);

$pdf = setFonce($j, $pdf);

	$txt='';
	$totunit+=$donnees['Yaourt_aromatise_4x125g_peche'];
	if($donnees['Yaourt_aromatise_4x125g_peche']!=0) $txt=$donnees['Yaourt_aromatise_4x125g_peche']; else $txt="   ";
	$txt = $txt . "  ";
	$totunit+=$donnees['Yaourt_aromatise_vrac_peche'];
	if($donnees['Yaourt_aromatise_vrac_peche']!=0) $txt= $txt . $donnees['Yaourt_aromatise_vrac_peche'].'p'; else $txt = $txt . "     ";
	$pdf->Cell($tailleYaourt,$hautUn,$txt,1,0,'C',true);

	$txt='';
	$totunit+=$donnees['Yaourt_aromatise_4x125g_abricot'];
	if($donnees['Yaourt_aromatise_4x125g_abricot']!=0) $txt=$donnees['Yaourt_aromatise_4x125g_abricot']; else $txt="   ";
	$txt = $txt . "  ";
	$totunit+=$donnees['Yaourt_aromatise_vrac_abricot'];
	if($donnees['Yaourt_aromatise_vrac_abricot']!=0) $txt= $txt . $donnees['Yaourt_aromatise_vrac_abricot'].'p'; else $txt = $txt . "     ";
	$pdf->Cell($tailleYaourt,$hautUn,$txt,1,0,'C',true);

$pdf = setClair($j, $pdf);

	$txt='';
	$totunit+=$donnees['Yaourt_aromatise_4x125g_vanille'];
	if($donnees['Yaourt_aromatise_4x125g_vanille']!=0) $txt=$donnees['Yaourt_aromatise_4x125g_vanille']; else $txt="   ";
	$txt = $txt . "  ";
	$totunit+=$donnees['Yaourt_aromatise_vrac_vanille'];
	if($donnees['Yaourt_aromatise_vrac_vanille']!=0) $txt= $txt . $donnees['Yaourt_aromatise_vrac_vanille'].'p'; else $txt = $txt . "     ";
	$pdf->Cell($tailleYaourt,$hautUn,$txt,1,0,'C',true);

	$txt='';
	$totunit+=$donnees['Yaourt_aromatise_4x125g_citron'];
	if($donnees['Yaourt_aromatise_4x125g_citron']!=0) $txt=$donnees['Yaourt_aromatise_4x125g_citron']; else $txt="   ";
	$txt = $txt . "  ";
	$totunit+=$donnees['Yaourt_aromatise_vrac_citron'];
	if($donnees['Yaourt_aromatise_vrac_citron']!=0) $txt= $txt . $donnees['Yaourt_aromatise_vrac_citron'].'p'; else $txt = $txt . "     ";
	$pdf->Cell($tailleYaourt,$hautUn,$txt,1,0,'C',true);

$pdf = setFonce($j, $pdf);

	$txt='';
	$totunit+=$donnees['Yaourt_aromatise_4x125g_framboise'];
	if($donnees['Yaourt_aromatise_4x125g_framboise']!=0) $txt=$donnees['Yaourt_aromatise_4x125g_framboise']; else $txt="   ";
	$txt = $txt . "  ";
	$totunit+=$donnees['Yaourt_aromatise_vrac_framboise'];
	if($donnees['Yaourt_aromatise_vrac_framboise']!=0) $txt= $txt . $donnees['Yaourt_aromatise_vrac_framboise'].'p'; else $txt = $txt . "     ";
	$pdf->Cell($tailleYaourt,$hautUn,$txt,1,0,'C',true);

	$txt='';
	$totunit+=$donnees['Yaourt_aromatise_4x125g_fraise'];
	if($donnees['Yaourt_aromatise_4x125g_fraise']!=0) $txt=$donnees['Yaourt_aromatise_4x125g_fraise']; else $txt="   ";
	$txt = $txt . "  ";
	$totunit+=$donnees['Yaourt_aromatise_vrac_fraise'];
	if($donnees['Yaourt_aromatise_vrac_fraise']!=0) $txt= $txt . $donnees['Yaourt_aromatise_vrac_fraise'].'p'; else $txt = $txt . "     ";
	$pdf->Cell($tailleYaourt,$hautUn,$txt,1,0,'C',true);

$pdf = setClair($j, $pdf);

	if($donnees['Yaourt_aromatise_4x125g_mixte']!=0) {$totunit+=$donnees['Yaourt_aromatise_4x125g_mixte']; $pdf->Cell($tailleYaourt,$hautUn,$donnees['Yaourt_aromatise_4x125g_mixte'],1,0,'C',true);} else $pdf->Cell($tailleYaourt,$hautUn,'',1,0,'C',true);

	$pdf->SetFont('Arial','B',10);
	$totalU = $totunit.' / '.$donnees['Unite'];
	$pdf->Cell($tailleTotal,$hautUn,$totalU,1,1,'C',true);
	$totgene+=$totunit;
}
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(220,220,220);
$pdf->Cell($sizeNom,$hautUn,"Nb unités de vente",1,0,'C',true);



mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysqli_select_db(base_de_donnees); // Sélection de la base 

$question = "SELECT SUM( Beurre_160g_sale ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
$pdf->Cell($tailleBeurre/2,$hautUn,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Beurre_160g_doux ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
$pdf->Cell($tailleBeurre/2,$hautUn,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Creme_250g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
$pdf->Cell($tailleCreme,$hautUn,$donnees[0],1,0,'C',true);

$question = "SELECT SUM( Yaourt_nature_5x125g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
	$txt= $donnees[0] . "  ";
$question = "SELECT SUM( Yaourt_nature_vrac ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
	$txt = $txt . $donnees[0] . 'p';
$pdf->Cell($tailleCreme,$hautUn,$txt,1,0,'C',true);

$question = "SELECT SUM( Yaourt_aromatise_4x125g_peche ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
	$txt= $donnees[0] . "  ";
$question = "SELECT SUM( Yaourt_aromatise_vrac_peche ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
	$txt = $txt . $donnees[0] . 'p';
$pdf->Cell($tailleYaourt,$hautUn,$txt,1,0,'C',true);

$question = "SELECT SUM( Yaourt_aromatise_4x125g_abricot ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
	$txt= $donnees[0] . "  ";
$question = "SELECT SUM( Yaourt_aromatise_vrac_abricot ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
	$txt = $txt . $donnees[0] . 'p';
$pdf->Cell($tailleYaourt,$hautUn,$txt,1,0,'C',true);

$question = "SELECT SUM( Yaourt_aromatise_4x125g_vanille ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
	$txt= $donnees[0] . "  ";
$question = "SELECT SUM( Yaourt_aromatise_vrac_vanille ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
	$txt = $txt . $donnees[0] . 'p';
$pdf->Cell($tailleYaourt,$hautUn,$txt,1,0,'C',true);

$question = "SELECT SUM( Yaourt_aromatise_4x125g_citron ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
	$txt= $donnees[0] . "  ";
$question = "SELECT SUM( Yaourt_aromatise_vrac_citron ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
	$txt = $txt . $donnees[0] . 'p';
$pdf->Cell($tailleYaourt,$hautUn,$txt,1,0,'C',true);

$question = "SELECT SUM( Yaourt_aromatise_4x125g_framboise ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
	$txt= $donnees[0] . "  ";
$question = "SELECT SUM( Yaourt_aromatise_vrac_framboise ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
	$txt = $txt . $donnees[0] . 'p';
$pdf->Cell($tailleYaourt,$hautUn,$txt,1,0,'C',true);

$question = "SELECT SUM( Yaourt_aromatise_4x125g_fraise ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
	$txt= $donnees[0] . "  ";
$question = "SELECT SUM( Yaourt_aromatise_vrac_fraise ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
	$txt = $txt . $donnees[0] . 'p';
$pdf->Cell($tailleYaourt,$hautUn,$txt,1,0,'C',true);

$question = "SELECT SUM( Yaourt_aromatise_4x125g_mixte ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
$pdf->Cell($tailleYaourt,$hautUn,$donnees[0],1,0,'C',true);

$question = "SELECT SUM( Unite ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
$totalU = $totgene.'/'.$donnees[0];
$pdf->Cell($tailleTotal,$hautUn,$totalU,1,1,'C',true);


mysqli_close();

$pdf->SetFont('Arial','',12);
$pdf->Cell($sizeNom,$hautTitre*2,'Nom',1,0,'C',true);
$pdf->Cell($tailleBeurre/2,$hautTitre,'S',1,0,'C',true);
$pdf->Cell($tailleBeurre/2,$hautTitre,'D',1,0,'C',true);
$pdf->Cell($tailleCreme,$hautTitre*2,'Crème',1,0,'C',true);
$pdf->Cell($tailleCreme,$hautTitre,'nature',1,0,'C',true);
$pdf->Cell($tailleYaourt,$hautTitre,'peche',1,0,'C',true);
$pdf->Cell($tailleYaourt,$hautTitre,'abrico',1,0,'C',true);
$pdf->Cell($tailleYaourt,$hautTitre,'vanille',1,0,'C',true);
$pdf->Cell($tailleYaourt,$hautTitre,'citron',1,0,'C',true);
$pdf->Cell($tailleYaourt,$hautTitre,'framb',1,0,'C',true); 
$pdf->Cell($tailleYaourt,$hautTitre,'fraise',1,0,'C',true);
$pdf->Cell($tailleYaourt,$hautTitre,'mixte',1,0,'C',true);
$pdf->Cell($tailleTotal,$hautTitre*2,'total',1,0,'C',true);
$pdf->Ln($hautTitre);
$pdf->Cell($sizeNom,$hautTitre,'',0,0);
$pdf->Cell($tailleBeurre,$hautTitre,'Beurre',1,0,'C',true);
$pdf->Cell($tailleCreme,$hautTitre,'',0,0);
$pdf->Cell($tailleCreme,$hautTitre,'5 Yao',1,0,'C',true);
$pdf->Cell($tailleYaourt*7,$hautTitre,'(mix=1citron+1vanille+1abricot+1fraise)',1,0,'C',true);
$pdf->Ln($hautTitre);


$pdf->AddPage();
$pdf->SetTopMargin(10); //0.0
$pdf->SetAutoPageBreak(true,10); 

mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysqli_select_db(base_de_donnees); // Sélection de la base 
$question="SELECT Date_livraison FROM amap_produits_laitiers_cde_en_cours";
$reponse = mysqli_query($question);
$donnees=mysqli_fetch_array($reponse);
$ProchLiv=date("d-M-Y",strtotime($donnees[0]));
$auj=date("d-m-y",time());

mysqli_close();

$pdf->SetFont('Arial','B',12);
$pdf->Cell(151,$hautUn*2,'AMAP SAINT SEB - LIVR. DU '.$ProchLiv.' (Fromages et laits)',1,0,'C');
$pdf->SetFont('Arial','',8);
$auj=date("d-m-y",time());
$pdf->Cell(40,$hautUn*2,"              date d'impression : ".$auj,1,1,'R');
$pdf->SetFont('Arial','B',12);


$tailleFromFrais = 13;
$tailleBleruchon = 12;
$tailleFromBlc = 14;
$tailleLait = 10;
$tailleLaitYa = 13;

$pdf->SetFont('Arial','',12);
$pdf->SetFillColor(220,220,220);
$pdf->Cell($sizeNom,$hautTitre*2,'Nom',1,0,'C',true);
$pdf->Cell($tailleFromFrais*2,$hautTitre,'Fromage frais',1,0,'C',true);
$pdf->Cell($tailleBleruchon*2,$hautTitre,'Bléruchon',1,0,'C',true);
$pdf->Cell($tailleFromBlc*3,$hautTitre,'Fromage blanc',1,0,'C',true);
$pdf->Cell($tailleLait,$hautTitre,'Lait','LTR',0,'C',true);
$pdf->Cell($tailleLait,$hautTitre,'Lait','LTR',0,'C',true);
$pdf->Cell($tailleLaitYa,$hautTitre,'Lait','LTR',0,'C',true);
$pdf->Cell($tailleTotal,$hautTitre*2,'total','LTR',0,'C',true);
$pdf->Ln($hautTitre);
$pdf->Cell($sizeNom,$hautTitre,'',0,0);
$pdf->Cell($tailleFromFrais,$hautTitre,'nature',1,0,'C',true);
$pdf->Cell($tailleFromFrais,$hautTitre,'herbes',1,0,'C',true);
$pdf->Cell($tailleBleruchon,$hautTitre,'petit',1,0,'C',true);
$pdf->Cell($tailleBleruchon,$hautTitre,'grand',1,0,'C',true);
$pdf->Cell($tailleFromBlc,$hautTitre,'faissel',1,0,'C',true);
$pdf->Cell($tailleFromBlc,$hautTitre,'lissé',1,0,'C',true);
$pdf->Cell($tailleFromBlc,$hautTitre,'maigre',1,0,'C',true);
$pdf->Cell($tailleLait,$hautTitre,'2L','LBR',0,'C',true);
$pdf->Cell($tailleLait,$hautTitre,'ribot','LBR',0,'C',true);
$pdf->Cell($tailleLaitYa,$hautTitre,'1L+2Y','LBR',0,'C',true);
$pdf->Ln($hautTitre);

//affichage de la table cde_en_cours qui contient les produits de la prochaine livraison
mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysqli_select_db(base_de_donnees); // Sélection de la base 
$question="SELECT * FROM amap_produits_laitiers_cde_en_cours ORDER BY Nom";
$reponse = mysqli_query($question);
$ligne = mysqli_num_rows($reponse);
mysqli_close();
$totgene=0;
$j=0;
while($donnees = mysqli_fetch_array($reponse)) {
	$j++;
	$totunit=0;
	$pdf->SetFont('Arial','',10);
	$pdf = setFonce($j, $pdf);

  $nom= $donnees['Nom'].' '.$donnees['Prenom'];
  if (strlen($nom) > $tailleMaxNom ) {
     $nom = substr($nom,0,$tailleMaxNom);
     $nom = $nom.'.';
  }

  $pdf->Cell($sizeNom,$hautUn,$nom,1,0,'C',true);
$pdf = setClair($j, $pdf);
	if($donnees['Fromage_frais_nature']!=0) {$totunit+=$donnees['Fromage_frais_nature']; $pdf->Cell($tailleFromFrais,$hautUn,$donnees['Fromage_frais_nature'],1,0,'C',true);} else $pdf->Cell($tailleFromFrais,$hautUn,'',1,0,'C',true);
	if($donnees['Fromage_frais_herbes_150g']!=0) {$totunit+=$donnees['Fromage_frais_herbes_150g']; $pdf->Cell($tailleFromFrais,$hautUn,$donnees['Fromage_frais_herbes_150g'],1,0,'C',true);} else $pdf->Cell($tailleFromFrais,$hautUn,'',1,0,'C',true);
$pdf = setFonce($j, $pdf);
	if($donnees['Bleruchon_100_a_150g']!=0) {$totunit+=$donnees['Bleruchon_100_a_150g']; $pdf->Cell($tailleBleruchon,$hautUn,$donnees['Bleruchon_100_a_150g'],1,0,'C',true);} else $pdf->Cell($tailleBleruchon,$hautUn,'',1,0,'C',true);
	if($donnees['Bleruchon_225_a_275g']!=0) {$totunit+=2*$donnees['Bleruchon_225_a_275g']; $pdf->Cell($tailleBleruchon,$hautUn,$donnees['Bleruchon_225_a_275g'],1,0,'C',true);} else $pdf->Cell($tailleBleruchon,$hautUn,'',1,0,'C',true);
$pdf = setClair($j, $pdf);
	if($donnees['Faisselle_500g']!=0) {$totunit+=$donnees['Faisselle_500g']; $pdf->Cell($tailleFromBlc,$hautUn,$donnees['Faisselle_500g'],1,0,'C',true);} else $pdf->Cell($tailleFromBlc,$hautUn,'',1,0,'C',true);
	if($donnees['Fromage_blanc_500g']!=0) {$totunit+=$donnees['Fromage_blanc_500g']; $pdf->Cell($tailleFromBlc,$hautUn,$donnees['Fromage_blanc_500g'],1,0,'C',true);} else $pdf->Cell($tailleFromBlc,$hautUn,'',1,0,'C',true);
$pdf = setFonce($j, $pdf);
	if($donnees['Fromage_blanc_maigre_500g']!=0) {$totunit+=$donnees['Fromage_blanc_maigre_500g']; $pdf->Cell($tailleFromBlc,$hautUn,$donnees['Fromage_blanc_maigre_500g'],1,0,'C',true);} else $pdf->Cell($tailleFromBlc,$hautUn,'',1,0,'C',true);
	if($donnees['Lait_cru_2L']!=0) {$totunit+=$donnees['Lait_cru_2L']; $pdf->Cell($tailleLait,$hautUn,$donnees['Lait_cru_2L'],1,0,'C',true);} else $pdf->Cell($tailleLait,$hautUn,'',1,0,'C',true);
$pdf = setClair($j, $pdf);
	if($donnees['Lait_ribot_1L5']!=0) {$totunit+=$donnees['Lait_ribot_1L5']; $pdf->Cell($tailleLait,$hautUn,$donnees['Lait_ribot_1L5'],1,0,'C',true);} else $pdf->Cell($tailleLait,$hautUn,'',1,0,'C',true);
	if($donnees['Lait_cru_1L_Yaourt_nature_2x125g']!=0) {$totunit+=$donnees['Lait_cru_1L_Yaourt_nature_2x125g']; $pdf->Cell($tailleLaitYa,$hautUn,$donnees['Lait_cru_1L_Yaourt_nature_2x125g'],1,0,'C',true);} else $pdf->Cell($tailleLaitYa,$hautUn,'',1,0,'C',true);
$pdf = setFonce($j, $pdf);
	$pdf->SetFont('Arial','B',10);

	$totalU = $totunit.' / '.$donnees['Unite'];
	$pdf->Cell($tailleTotal,$hautUn,$totalU,1,1,'C',true);

	$totgene+=$totunit;
}
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(220,220,220);
$pdf->Cell($sizeNom,$hautUn,"Nb unités de vente",1,0,'C',true);



mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysqli_select_db(base_de_donnees); // Sélection de la base 

$question = "SELECT SUM( Fromage_frais_nature ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
$pdf->Cell($tailleFromFrais,$hautUn,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Fromage_frais_herbes_150g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
$pdf->Cell($tailleFromFrais,$hautUn,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Bleruchon_100_a_150g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
$pdf->Cell($tailleBleruchon,$hautUn,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Bleruchon_225_a_275g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
$pdf->Cell($tailleBleruchon,$hautUn,'2x'.$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Faisselle_500g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
$pdf->Cell($tailleFromBlc,$hautUn,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Fromage_blanc_500g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
$pdf->Cell($tailleFromBlc,$hautUn,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Fromage_blanc_maigre_500g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
$pdf->Cell($tailleFromBlc,$hautUn,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Lait_cru_2L ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
$pdf->Cell($tailleLait,$hautUn,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Lait_ribot_1L5 ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
$pdf->Cell($tailleLait,$hautUn,$donnees[0],1,0,'C',true);
$question = "SELECT SUM( Lait_cru_1L_Yaourt_nature_2x125g  ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
$pdf->Cell($tailleLaitYa,$hautUn,$donnees[0],1,0,'C',true);

$question = "SELECT SUM( Unite ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
$donnees=mysqli_fetch_array(mysqli_query($question));
$totalU = $totgene.'/'.$donnees[0];
$pdf->Cell($tailleTotal,$hautUn,$totalU,1,1,'C',true);

mysqli_close();

$pdf->SetFont('Arial','',12);
$pdf->Cell($sizeNom,$hautTitre*2,'Nom',1,0,'C',true);
$pdf->Cell($tailleFromFrais,$hautTitre,'nature',1,0,'C',true);
$pdf->Cell($tailleFromFrais,$hautTitre,'herbe',1,0,'C',true);
$pdf->Cell($tailleBleruchon,$hautTitre,'petit',1,0,'C',true);
$pdf->Cell($tailleBleruchon,$hautTitre,'grand',1,0,'C',true);
$pdf->Cell($tailleFromBlc,$hautTitre,'faissel',1,0,'C',true);
$pdf->Cell($tailleFromBlc,$hautTitre,'lissé',1,0,'C',true);
$pdf->Cell($tailleFromBlc,$hautTitre,'maigre',1,0,'C',true);
$pdf->Cell($tailleLait,$hautTitre,'Lait','LTR',0,'C',true);
$pdf->Cell($tailleLait,$hautTitre,'Lait','LTR',0,'C',true);
$pdf->Cell($tailleLaitYa,$hautTitre,'Lait','LTR',0,'C',true);
$pdf->Cell($tailleTotal,$hautTitre*2,'total',1,0,'C',true);
$pdf->Ln($hautTitre);
$pdf->Cell($sizeNom,$hautTitre,'',0,0);
$pdf->Cell($tailleFromFrais*2,$hautTitre,'Fromage frais',1,0,'C',true);
$pdf->Cell($tailleBleruchon*2,$hautTitre,'Bléruchon',1,0,'C',true);
$pdf->Cell($tailleFromBlc*3,$hautTitre,'Fromage blanc',1,0,'C',true);
$pdf->Cell($tailleLait,$hautTitre,'2L','LBR',0,'C',true);
$pdf->Cell($tailleLait,$hautTitre,'ribot','LBR',0,'C',true);
$pdf->Cell($tailleLaitYa,$hautTitre,'1L+2Y','LBR',0,'C',true);
//$pdf->Ln($hautTitre);


$pdf->Output('StSeb-Livraison-du-'.$ProchLiv.'.pdf','D');
?>