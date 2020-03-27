<?php
/* en arrivant du menu de l'index ordre='passe' et il faut demander le mot de passe */
/* ensuite ordre!='passe' et on ne demande plus le mot de passe */
include_once("webmaster/define.php");

$ok=-1;
if (isset($_POST['motpasse'])) { $ok=0; $mot_test=$_POST['motpasse']; } else $mot_test='';
if($mot_test=="ruble")$ok=1;
if($ok==1)
// Si le mot de passe est bon
{
//***********************************************************************
// On a le droit
//***********************************************************************
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>
	<body>
	<div style="text-align: center;">
		<?php
		include_once("webmaster/define.php");
		mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
		mysqli_select_db(base_de_donnees); // Sélection de la base 
		$question="SELECT * FROM amap_produits_laitiers_permanences WHERE Distribution=1 ORDER BY Date";
		$reponse1 = mysqli_query($question)  or die(mysqli_error());
		$question="SELECT Date_livraison FROM amap_produits_laitiers_cde_en_cours";
		$reponse2 = mysqli_query($question) or die(mysqli_error());
		$TableDateLiv=mysqli_fetch_array($reponse2);
		$DateLivEnCours=strtotime($TableDateLiv[0]);
		$auj=strtotime(date("Y-m-d",time()));
//mise à jour de la table_cde_en_cours par recopie de la table_cde
//cette mise à jour ne se fait que si     
//		[Date de la table cde_en_cours] < DateAujourd'hui ou [Date de la table cde_en_cours] est nulle
//      et si      [datelimite] <= [date_aujourd'hui] <= [dateProchaineLivraison]

		if ( mysqli_num_rows($reponse1) ==0) { 
			$flag= -2;
		} else {
			$flag=0; 
			//cette partie de programme se trouve aussi dans le menu d'accès à la commande perso de chaque amapien
			//si bien que c'est le premier accès d'un amapien ou le premier acces du producteur après la date limite qui provoque
			//la mise à jour de la table cde_en_cours 
			if($TableDateLiv[0]==NULL || $auj>$DateLivEnCours ) { 
				$flag=-1;//impossible d'imprimer les amapiens peuvent encore modifier leur choix
				while($DateLiv = mysqli_fetch_array($reponse1)) {
					// on recherche la date de la prochaine livraison
					$temp=strtotime($DateLiv['Date']);
					$limite=$temp-JOURS_MARGE_PDT_LAITIER*24*60*60;
					if($auj>=$limite && $auj<=$temp) { 
						$flag=1;
						$LaDate=$DateLiv['Date'];
						$ProchLiv=date("d-M-Y",strtotime($DateLiv['Date']));
						break;
					}
				}
			}
		}
		if($flag==0) $ProchLiv=date("d-M-Y",strtotime($TableDateLiv[0]));
		if($flag==1) {
			$question="TRUNCATE TABLE amap_produits_laitiers_cde_en_cours";
			$reponse=mysqli_query($question) or die(mysqli_error());;
			$question="INSERT INTO amap_produits_laitiers_cde_en_cours SELECT * FROM amap_produits_laitiers_cde";
			$reponse=mysqli_query($question) or die(mysqli_error());;
			$question="UPDATE amap_produits_laitiers_cde_en_cours SET Date_livraison='".$LaDate."'";
			$reponse=mysqli_query($question) or die(mysqli_error());;
		}
		mysqli_close();
		
		if ($flag==-1) { ?>
			<h3 class="mot_passe_recette">
			Récapitulatif inaccessible.<br />Les amapiens peuvent encore modifier leur choix jusqu'à la date de livraison moins 9 jours!!</h3>
		<?php } elseif ($flag==-2) { ?>
			<h3 class="mot_passe_recette">
			Plus de date de distribution de prévue.<br />voir avec le référent ou l'administateur de la base!!</h3>
		<?php } else {?>		
			<button onclick="document.location.href='ImprimePDF_ProdLait_V2.php'" name="BtnImprime" type="button" class="BtnStd">Enregistrer pour imprimer</button>
		<?php } ?>
		
			<button onclick="document.location.href='index.php'" name="BtnRetour" type="button" class="BtnStd">Retour</button><br />
			
	</div>
	<?php if ($flag!=-1) { ?>
	<div>
	<?php
	mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysqli_select_db(base_de_donnees); // Sélection de la base 
	$question="SELECT * FROM amap_produits_laitiers_cde_en_cours ORDER BY Nom"; 
	$reponse = mysqli_query($question);
	$ligne = mysqli_num_rows($reponse);
	$totgene = 0;
	mysqli_close();
	?>
		<table  style="text-align: center; border-collapse: collapse; margin: 5px auto;">
			<caption style="
				margin: 5px auto;
				background-color: #DDDDFF;
				border: 2px ridge white;
				text-align: center;
				padding: 0px 0px 0px 10px;
				color: red;
				font-weight: bold">Les produits laitiers enregistrés pour la livraison du <?php echo $ProchLiv; ?>
			</caption>
			<tr>
				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Nom</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Unité</th>
				<th style="border: 1px solid black; background-color: #DDDDDD; border-left-width: 2px; border-right-width: 2px;" colspan="2">Beurre</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Crème</th>
				<th style="border: 1px solid black; background-color: #DDDDDD; border-left-width: 2px; border-right-width: 2px;" colspan="8">Yaourts</th>
				<th style="border: 1px solid black; background-color: #DDDDDD; border-left-width: 2px; border-right-width: 2px;" colspan="2">Frg frais</th>
				<th style="border: 1px solid black; background-color: #DDDDDD; border-left-width: 2px; border-right-width: 2px;" colspan="2">Bléruchon</th>
				<th style="border: 1px solid black; background-color: #DDDDDD; border-left-width: 2px; border-right-width: 2px;" colspan="3">Fromage blanc 500g</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Lait 2L</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Lait ribot<br />1,5L</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Lait 1L<br />2 Yaourts</th>
        <th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Total</th>
			</tr>
			<tr>
				<th style="border: 1px solid black; background-color: #DDDDDD; border-left-width: 2px;">S</th>
				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">D</th>
				<!--<th style="border: 1px solid black; background-color: #DDDDDD;">Crème</th>-->
				<th style="border: 1px solid black; background-color: #DDDDDD; border-left-width: 2px;">5nat</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">4Pch</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">4Abr</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">4Van</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">4Cit</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">4Framb</th>   <!-- ajout DMO 7/10/13 -->
				<th style="border: 1px solid black; background-color: #DDDDDD;">4Fraise</th>
				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">4Mixte</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">nature</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">herbes</th>
				<th style="border: 1px solid black; background-color: #DDDDDD; border-left-width: 2px;">petit</th>
				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">grand</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">Faisselle</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">lissé</th>
				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">maigre</th>
				<!--
				<th style="border: 1px solid black; background-color: #DDDDDD;">lait</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">Lait rib</th>
				-->
			</tr>
			<?php $j=0;$totgne=0;
			while($donnees = mysqli_fetch_array($reponse)) { $j++;$totunit=0;?>
			<tr style="background-color: <?php if($j % 3==1) echo '#40a7f5'; elseif($j % 3==2) echo '#f9f580'; else echo 'white';?>">
				<td style="border: 1px solid black;"><?php echo $donnees['Nom'].' '.$donnees['Prenom'];?></td>
				<td style="border: 1px solid black;"><?php echo $donnees['Unite'];?></td>
				<td style="border: 1px solid black; border-left-width: 2px;"><?php $totunit+=$donnees['Beurre_160g_sale']; if($donnees['Beurre_160g_sale']!=0) echo $donnees['Beurre_160g_sale'];?></td>
				<td style="border: 1px solid black; border-right-width: 2px;"><?php $totunit+=$donnees['Beurre_160g_doux']; if($donnees['Beurre_160g_doux']!=0) echo $donnees['Beurre_160g_doux'];?></td>
				<td style="border: 1px solid black;"><?php $totunit+=$donnees['Creme_250g']; if($donnees['Creme_250g']!=0) echo $donnees['Creme_250g'];?></td>
				<td style="border: 1px solid black; border-left-width: 2px;"><?php $totunit+=$donnees['Yaourt_nature_5x125g']; if($donnees['Yaourt_nature_5x125g']!=0) echo $donnees['Yaourt_nature_5x125g']; else echo "&nbsp;&nbsp;&nbsp;" ;?>&nbsp;&nbsp;<?php $totunit+=$donnees['Yaourt_nature_vrac']; if($donnees['Yaourt_nature_vrac']!=0) { echo $donnees['Yaourt_nature_vrac']; echo 'p';} else echo "&nbsp;&nbsp;&nbsp;&nbsp;"?></td>
				<td style="border: 1px solid black;"><?php $totunit+=$donnees['Yaourt_aromatise_4x125g_peche']; if($donnees['Yaourt_aromatise_4x125g_peche']!=0) echo $donnees['Yaourt_aromatise_4x125g_peche'];else echo "&nbsp;&nbsp;&nbsp;" ;?>&nbsp;&nbsp;<?php $totunit+=$donnees['Yaourt_aromatise_vrac_peche']; if($donnees['Yaourt_aromatise_vrac_peche']!=0) { echo $donnees['Yaourt_aromatise_vrac_peche']; echo 'p';} else echo "&nbsp;&nbsp;&nbsp;&nbsp;";?></td>
				<td style="border: 1px solid black;"><?php $totunit+=$donnees['Yaourt_aromatise_4x125g_abricot']; if($donnees['Yaourt_aromatise_4x125g_abricot']!=0) echo $donnees['Yaourt_aromatise_4x125g_abricot']; else echo "&nbsp;&nbsp;&nbsp;" ?>&nbsp;&nbsp;<?php $totunit+=$donnees['Yaourt_aromatise_vrac_abricot']; if($donnees['Yaourt_aromatise_vrac_abricot']!=0) { echo $donnees['Yaourt_aromatise_vrac_abricot']; echo 'p';} else echo "&nbsp;&nbsp;&nbsp;&nbsp;";?></td>
				<td style="border: 1px solid black;"><?php $totunit+=$donnees['Yaourt_aromatise_4x125g_vanille']; if($donnees['Yaourt_aromatise_4x125g_vanille']!=0) echo $donnees['Yaourt_aromatise_4x125g_vanille']; else echo "&nbsp;&nbsp;&nbsp;" ?>&nbsp;&nbsp;<?php $totunit+=$donnees['Yaourt_aromatise_vrac_vanille']; if($donnees['Yaourt_aromatise_vrac_vanille']!=0) { echo $donnees['Yaourt_aromatise_vrac_vanille']; echo 'p';} else echo "&nbsp;&nbsp;&nbsp;&nbsp;";?></td>
				<td style="border: 1px solid black;"><?php $totunit+=$donnees['Yaourt_aromatise_4x125g_citron']; if($donnees['Yaourt_aromatise_4x125g_citron']!=0) echo $donnees['Yaourt_aromatise_4x125g_citron']; else echo "&nbsp;&nbsp;&nbsp;" ;?>&nbsp;&nbsp;<?php $totunit+=$donnees['Yaourt_aromatise_vrac_citron']; if($donnees['Yaourt_aromatise_vrac_citron']!=0) { echo $donnees['Yaourt_aromatise_vrac_citron']; echo 'p';} else echo "&nbsp;&nbsp;&nbsp;&nbsp;"?></td>
				<td style="border: 1px solid black;"><?php $totunit+=$donnees['Yaourt_aromatise_4x125g_framboise']; if($donnees['Yaourt_aromatise_4x125g_framboise']!=0) echo $donnees['Yaourt_aromatise_4x125g_framboise']; else echo "&nbsp;&nbsp;&nbsp;" ?>&nbsp;&nbsp;<?php $totunit+=$donnees['Yaourt_aromatise_vrac_framboise']; if($donnees['Yaourt_aromatise_vrac_framboise']!=0) { echo $donnees['Yaourt_aromatise_vrac_framboise']; echo 'p';} else echo "&nbsp;&nbsp;&nbsp;&nbsp;"?></td>
				<td style="border: 1px solid black;"><?php $totunit+=$donnees['Yaourt_aromatise_4x125g_fraise']; if($donnees['Yaourt_aromatise_4x125g_fraise']!=0) echo $donnees['Yaourt_aromatise_4x125g_fraise']; else echo "&nbsp;&nbsp;&nbsp;" ?>&nbsp;&nbsp;<?php $totunit+=$donnees['Yaourt_aromatise_vrac_fraise']; if($donnees['Yaourt_aromatise_vrac_fraise']!=0) { echo $donnees['Yaourt_aromatise_vrac_fraise']; echo 'p';} else echo "&nbsp;&nbsp;&nbsp;&nbsp;"?></td>
				<td style="border: 1px solid black;"><?php $totunit+=$donnees['Yaourt_aromatise_4x125g_mixte']; if($donnees['Yaourt_aromatise_4x125g_mixte']!=0) echo $donnees['Yaourt_aromatise_4x125g_mixte'];?></td>
				<td style="border: 1px solid black; border-left-width: 2px;"><?php $totunit+=$donnees['Fromage_frais_nature']; if($donnees['Fromage_frais_nature']!=0) echo $donnees['Fromage_frais_nature'];?></td>
				<td style="border: 1px solid black;"><?php $totunit+=$donnees['Fromage_frais_herbes_150g']; if($donnees['Fromage_frais_herbes_150g']!=0) echo $donnees['Fromage_frais_herbes_150g'];?></td>
				<td style="border: 1px solid black; border-left-width: 2px;"><?php $totunit+=$donnees['Bleruchon_100_a_150g']; if($donnees['Bleruchon_100_a_150g']!=0) echo $donnees['Bleruchon_100_a_150g'];?></td>
				<td style="border: 1px solid black;"><?php $totunit+=2*$donnees['Bleruchon_225_a_275g']; if($donnees['Bleruchon_225_a_275g']!=0) echo $donnees['Bleruchon_225_a_275g'];?></td>
				<td style="border: 1px solid black; border-left-width: 2px;"><?php $totunit+=$donnees['Faisselle_500g']; if($donnees['Faisselle_500g']!=0) echo $donnees['Faisselle_500g'];?></td>
				<td style="border: 1px solid black;"><?php $totunit+=$donnees['Fromage_blanc_500g']; if($donnees['Fromage_blanc_500g']!=0) echo $donnees['Fromage_blanc_500g'];?></td>
				<td style="border: 1px solid black;"><?php $totunit+=$donnees['Fromage_blanc_maigre_500g']; if($donnees['Fromage_blanc_maigre_500g']!=0) echo $donnees['Fromage_blanc_maigre_500g'];?></td>
				<td style="border: 1px solid black; border-left-width: 2px;"><?php $totunit+=$donnees['Lait_cru_2L']; if($donnees['Lait_cru_2L']!=0) echo $donnees['Lait_cru_2L'];?></td>
				<td style="border: 1px solid black;"><?php $totunit+=$donnees['Lait_ribot_1L5']; if($donnees['Lait_ribot_1L5']!=0) echo $donnees['Lait_ribot_1L5'];?></td>
				<td style="border: 1px solid black;"><?php $totunit+=$donnees['Lait_cru_1L_Yaourt_nature_2x125g']; if($donnees['Lait_cru_1L_Yaourt_nature_2x125g']!=0) echo $donnees['Lait_cru_1L_Yaourt_nature_2x125g'];?></td>
				<td style="border: 1px solid black;"><?php echo $totunit;?></td>
				<?php $totgene+=$totunit; ?>
			</tr>
			<?php } 
			mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
			mysqli_select_db(base_de_donnees); // Sélection de la base 
			?>
			<tr>
				<th style="border: 1px solid black; background-color: #DDDDDD;">Nombre d'unités</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Unite ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Beurre_160g_sale ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Beurre_160g_doux ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Creme_250g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Yaourt_nature_5x125g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				echo "&nbsp;&nbsp;";
				$question = "SELECT SUM( Yaourt_nature_vrac ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				echo 'p'
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Yaourt_aromatise_4x125g_peche ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				echo "&nbsp;&nbsp;";
				$question = "SELECT SUM( Yaourt_aromatise_vrac_peche ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				echo 'p'
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Yaourt_aromatise_4x125g_abricot ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				echo "&nbsp;&nbsp;";
				$question = "SELECT SUM( Yaourt_aromatise_vrac_abricot ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				echo 'p'
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Yaourt_aromatise_4x125g_vanille ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				echo "&nbsp;&nbsp;";
				$question = "SELECT SUM( Yaourt_aromatise_vrac_vanille ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				echo 'p'
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Yaourt_aromatise_4x125g_citron ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				echo "&nbsp;&nbsp;";
				$question = "SELECT SUM( Yaourt_aromatise_vrac_citron ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				echo 'p'
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Yaourt_aromatise_4x125g_framboise ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				echo "&nbsp;&nbsp;";
				$question = "SELECT SUM( Yaourt_aromatise_vrac_framboise ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				echo 'p'
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">     
				<?php		
				$question = "SELECT SUM( Yaourt_aromatise_4x125g_fraise ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				echo "&nbsp;&nbsp;";
				$question = "SELECT SUM( Yaourt_aromatise_vrac_fraise ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				echo 'p'
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">     
				<?php		
				$question = "SELECT SUM( Yaourt_aromatise_4x125g_mixte ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Fromage_frais_nature ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Fromage_frais_herbes_150g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Bleruchon_100_a_150g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Bleruchon_225_a_275g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo '2x'.$donnees[0];
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Faisselle_500g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Fromage_blanc_500g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Fromage_blanc_maigre_500g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Lait_cru_2L ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				?>
				</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Lait_ribot_1L5 ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				?>
				</th>
				
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php		
				$question = "SELECT SUM( Lait_cru_1L_Yaourt_nature_2x125g ) AS total FROM amap_produits_laitiers_cde_en_cours ;";
				$donnees=mysqli_fetch_array(mysqli_query($question));
				echo $donnees[0];
				?>
				<th style="border: 1px solid black; background-color: #DDDDDD;">
				<?php echo $totgene; ?>
				</tr>
			</tr>
			<?php mysqli_close(); ?>
			<tr>
				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2" colspan="2"></th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">S</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">D</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Crème</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">5 nat</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">4Pch</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">4Ab</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">4V</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">4C</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">4Framb</th>     <!-- ajout DMO 7/10/13 -->
				<th style="border: 1px solid black; background-color: #DDDDDD;">4Fraise</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">4Mixte</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">nature</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">herbes</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">petit</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">grand</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">Faisselle</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">lissé</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;">maigre</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">lait 2L</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Lait ribot<br />1,5L</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Lait 1L<br />2 Yaourts</th>
        <th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Total</th>
			</tr>
			<tr>
				<!--<th style="border: 1px solid black; background-color: #DDDDDD;"></th>-->
				<th style="border: 1px solid black; background-color: #DDDDDD;" colspan="2">Beurre</th>
				<!--<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Crème</th>-->
				<th style="border: 1px solid black; background-color: #DDDDDD;" colspan="8">Yaourts</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;" colspan="2">Frg frais</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;" colspan="2">Bléruchon</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;" colspan="3">Frg blanc 500g</th>
				<!--
				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Lait</th>
				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Lait rib</th>
				-->
			</tr>
		</table>
	</div>
	<?php } ?>
	</body>
</html>



<?php
} 
else // le mot de passe n'est pas bon
{
//***********************************************************************
// On affiche la zone de texte pour rentrer de nouveau le mot de passe.
//***********************************************************************
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>

<script type="text/javascript">
<!--
function verifkey(boite) {
	if(boite.value=='') {
		alert('Entrer un mot de passe!');
		boite.focus();
		return false;
	}
	return true;
}
-->
</script>
	
	<body onload="document.getElementById('motpasse').focus();">
		<div id="page_principale">
			<form class="mot_passe_recette" onsubmit="return verifkey(this.motpasse);" method="post" action="liste_produits_lp.php" >
				<p class="mot_passe_recette">
					<?php if($ok==0) { ?>Mot de passe incorrect!!<br /><?php } ?>
					<label for="motpasse">Mot de passe</label> : <input type="password" name="motpasse" id="motpasse" size="50" maxlength="45" tabindex="10"/>
					<input type="submit" tabindex="20"/> <input type="reset" tabindex="30"/>
				</p>
			</form>

		</div>		
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</p>
	</body>
</html>
<?php } ?>