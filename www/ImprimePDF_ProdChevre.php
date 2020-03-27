<?php
  include("webmaster/define.php");
  require('fpdf.php');
  $pdf=new FPDF('L','mm','A4');
  $pdf->AddPage();
  $pdf->SetTopMargin(0.0);
  
  mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
  mysql_select_db(base_de_donnees); // Sélection de la base 
  $question="SELECT Date_livraison FROM amap_chevre_cde_en_cours";
  $reponse = mysql_query($question);
  $donnees=mysql_fetch_array($reponse);
  $ProchLiv=date("d-M-Y",strtotime($donnees[0]));
  $auj=date("d-m-y",time());
  
  $sizeNom = 60;
  $sizeUnite = 11;
  $sizeEmargement = 25;
  $beginPdt =  $sizeNom +  $sizeUnite + $sizeEmargement;
  $sizeCellPetitEtGrand = 12;
  $sizeCellPyrEtFB = 20; 
  $sizeTotal = 10;
  $hauteurEntete = 5;
  $petiteFont = 8;
  $normalFont= 10;
  $grandeFont= 12;
  $erreur=0;
  
  $pdf->SetFont('Arial','B',$grandeFont);
  $pdf->Cell(278,10,'CONTRAT CHEVRE AMAP Les GUMES Saint Séb--------- LIVRAISON DU '.$ProchLiv,0,1,'C');
  
  $pdf->SetFont('Arial','',$grandeFont);
  $pdf->SetFillColor(220,220,220);
  $pdf->Cell($sizeNom,$hauteurEntete*2,'Nom',1,0,'C',true);
  $pdf->Cell($sizeUnite,$hauteurEntete*2,'Unité',1,0,'C',true);
  $pdf->Cell($sizeEmargement,$hauteurEntete*2,'Emarg.',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand * 5 ,$hauteurEntete,'Petit',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand * 5,$hauteurEntete,'Grand',1,0,'C',true);
  $pdf->Cell($sizeCellPyrEtFB ,$hauteurEntete*2,'Pyramide',1,0,'C',true);
  $pdf->Cell($sizeCellPyrEtFB  ,$hauteurEntete,'Fromage','LTR',0,'C',true);
  $pdf->Cell($sizeTotal,$hauteurEntete*2,'total',1,0,'C',true);
  $pdf->Ln(5);
  //petit
  $pdf->SetFont('Arial','',$petiteFont);
  $pdf->Cell($beginPdt,$hauteurEntete,'',0,0);
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Frais',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Affiné',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Sec',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Sésame',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Provence',1,0,'C',true);
  //grand
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Frais',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Affiné',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Sec',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Sésame',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Provence',1,0,'C',true);
  //pyramide
  $pdf->Cell($sizeCellPyrEtFB,$hauteurEntete,'',0,0);
  //Fromage Blanc
  $pdf->SetFont('Arial','',$grandeFont);
  $pdf->Cell($sizeCellPyrEtFB  ,$hauteurEntete,'Blanc','LBR',0,'C',true);
  $pdf->SetFont('Arial','',$petiteFont);
  $pdf->Ln(5);
  
  //affichage de la table cde_en_cours qui contient les produits de la prochaine livraison
  $question="SELECT * FROM amap_chevre_cde_en_cours ORDER BY Nom";
  $reponse = mysql_query($question);
  $ligne = mysql_num_rows($reponse);

  // on récupère le nombre d'unité de chaque produit dans l'ordre des ID des produits
  $resultUnit=mysql_query("SELECT Unite, Id FROM amap_chevre_produits ORDER BY Id") or die(mysql_error());
  while ( $unite = mysql_fetch_array($resultUnit)) {
    $unites[$unite['Id']] =  $unite['Unite'];
  };
    

  $totgene=0;
  while($donnees = mysql_fetch_array($reponse)) {
  	$j++;
  	$totunit=0;
  	$pdf->SetFont('Arial','',10);
    
    $nom= $donnees['Nom'].' '.$donnees['Prenom'];
  	if (strlen($nom) > 30 ) {
      $nom = substr($nom,0,30);
      $nom = $nom.'.';
    }
          
  	if($j % 3==1) $pdf->SetFillColor(64,167,245);
  	elseif ($j % 3==2) $pdf->SetFillColor(159,245,128);
  	else $pdf->SetFillColor(255,255,255);
    
  	$pdf->Cell($sizeNom,5,$nom,1,0,'C',true);
  	$pdf->Cell($sizeUnite,5,$donnees['Unite'],1,0,'C',true);
  	$pdf->Cell($sizeEmargement,5,'',1,0,'C',true);

    $cpt = 0;
    $currentField = 0;
    foreach ($donnees as $key => $value) { 
         if ( intval($key) != 0) continue; //on passe  les key qui ne sont pas des champs
         if (  $key == "id" || $key == "Date_livraison" || $key == "Nom" || $key == 'Prenom')  continue;
         if ( $key == "Unite" ) {
            $totalLigne = $value;
            continue;
         }
		 
         $currentField++; // les produits dans amap_chevre_cde_en_cours doivent être rangés dans l'ordre croissant des id de la table produit.
         $strValue= '';                  
         if ($value != 0)  {
              $totunit += $value * $unites[$currentField]; 
              $strValue = $value;
          }
          $cpt = $cpt+1;
          $sizeCell = 0;
          if ($cpt <= 10)  {     // petits et grands
              $sizeCell = $sizeCellPetitEtGrand;           
          } elseif ($cpt <= 12) { // pyramide
              $sizeCell = $sizeCellPyrEtFB;          
          } elseif ($cpt <= 13) { // FB
              $sizeCell = $sizeCellPyrEtFB;          
          } else {
              $sizeCell = $sizeTotal;          
          }
          
          $pdf->Cell( $sizeCell,$hauteurEntete, $strValue, 1, 0, 'C', true);
           
    }
    
    
   	$pdf->SetFont('Arial','B', $normalFont);
    if ( $totalLigne != $totunit) {
          // Attention : la commande ne correspond pas au nombre d'unité du contrat     -> alerter le producteur
          $pdf->SetTextColor (255,0,0);
          $erreur = 1;
    }
  	$pdf->Cell($sizeTotal, $hauteurEntete, $totunit, 1, 1, 'C', true);
    $pdf->SetTextColor (0,0,0);
  	$totgene+=$totunit;
  }
  $pdf->SetFont('Arial','B',$grandeFont);
  $pdf->SetFillColor(220,220,220);
  $pdf->Cell($sizeNom,5,"Unités de vente",1,0,'C',true);
  
   if ( $erreur==1) {
          // Attention : la commande ne correspond pas au nombre d'unité du contrat     -> alerter le producteur
          $pdf->SetTextColor (255,0,0);
     }
  
  // nouveau parcours des commandes pour récupérer les totaux par produits
  $reponse = mysql_query("SELECT * FROM amap_chevre_cde_en_cours ORDER BY Nom");
  $donnees = $donnees = mysql_fetch_array($reponse);
  $cpt=0;
  foreach ($donnees as $key => $value) { 
        if ( intval($key) != 0)
               continue; //on passe  les key qui ne sont pas des champs
        if (  $key == "id" || $key == "Date_livraison" || $key == "Nom" || $key == 'Prenom')
              continue; 
                
        $question = "SELECT SUM( ".$key." ) AS total FROM amap_chevre_cde_en_cours ;";
		    $result=mysql_fetch_array(mysql_query($question));
        
        if (  $key == "Unite")  {
           $pdf->Cell($sizeUnite,5,$result[0],1,0,'C',true);
           $pdf->Cell($sizeEmargement,5,$erreur?'ERREUR':'',1,0,'C',true);
         }
        else {
          $cpt = $cpt+1;
          $sizeCell = 0;
          if ($cpt <= 10)  {     // petits et grands
              $sizeCell = $sizeCellPetitEtGrand;           
          } elseif ($cpt <= 12) {  // pyramide
              $sizeCell = $sizeCellPyrEtFB;          
          } elseif ($cpt <= 13) { // FB
              $sizeCell = $sizeCellPyrEtFB;          
          } else {
              $sizeCell = $sizeTotal;          
          }
          
          $pdf->Cell( $sizeCell,$hauteurEntete, $result[0], 1,0,'C',true);

        } 
  }  

  $pdf->Cell($sizeTotal,$hauteurEntete,$totgene,1,0,'C',true);
   $pdf->SetTextColor (0,0,0);
    
  
  mysql_close();
  
  
  $pdf->SetFont('Arial','', $grandeFont); 
  $pdf->Ln(5);

  $pdf->SetFont('Arial','', $grandeFont);
  $pdf->SetFillColor(220,220,220);
  $pdf->Cell($sizeNom,$hauteurEntete*2,'Nom',1,0,'C',true);
  $pdf->Cell($sizeUnite,$hauteurEntete*2,'Unité',1,0,'C',true);
  $pdf->Cell($sizeEmargement,$hauteurEntete*2,'Emarg.',1,0,'C',true);

  //petit
  $pdf->SetFont('Arial','', $petiteFont);
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Frais',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Affiné',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Sec',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Sésame',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Provence',1,0,'C',true);
  //grand
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Frais',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Affiné',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Sec',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Sésame',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand,$hauteurEntete,'Provence',1,0,'C',true);
  //pyramide
  $pdf->SetFont('Arial','', $grandeFont);
  $pdf->Cell($sizeCellPyrEtFB ,$hauteurEntete*2,'Pyramide',1,0,'C',true);
  //Fromage Blanc
   $pdf->Cell($sizeCellPyrEtFB  ,$hauteurEntete,'Fromage ',1,0,'C',true);

  $pdf->Cell($sizeTotal,$hauteurEntete*2,'total',1,0,'C',true);

  $pdf->Ln(5);
  $pdf->Cell($beginPdt,$hauteurEntete,'',0,0);
  $pdf->Cell($sizeCellPetitEtGrand * 5 ,$hauteurEntete,'Petit',1,0,'C',true);
  $pdf->Cell($sizeCellPetitEtGrand * 5,$hauteurEntete,'Grand',1,0,'C',true);
   $pdf->Cell($sizeCellPyrEtFB,$hauteurEntete,'',0,0); 
  $pdf->Cell($sizeCellPyrEtFB  ,$hauteurEntete,'Blanc','LBR',0,'C',true);
  $pdf->Ln(5);

  $pdf->SetFont('Arial','',8);
  $auj=date("d-m-y",time());
  $pdf->Cell(278,5,"date d'impression : ".$auj,0,0,'C');
  
  $pdf->Output('StSeb-Livraison-du-'.$ProchLiv.'.pdf','D');
?>