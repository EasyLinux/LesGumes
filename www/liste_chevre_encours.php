<?php
/* en arrivant du menu de l'index ordre='passe' et il faut demander le mot de passe */
/* ensuite ordre!='passe' et on ne demande plus le mot de passe */
include_once("webmaster/define.php");
$ok=-1;
if (isset($_POST['motpasse'])) { 
	$ok=0; 
	$mot_test=$_POST['motpasse']; 
} else { 
	$mot_test='';
}
if($mot_test=="Ferreira")
	$ok=1;
if($ok==1)  {
// Si le mot de passe est bon
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
		$question="SELECT * FROM amap_chevre_permanences WHERE Distribution=1 ORDER BY Date";
		$reponse1 = mysqli_query($question) or die(mysqli_error());;
		$question="SELECT Date_livraison FROM amap_chevre_cde_en_cours";
		$reponse2 = mysqli_query($question) or die(mysqli_error()); ;
		$TableDateLiv=mysqli_fetch_array($reponse2);
		$DateLivEnCours=strtotime($TableDateLiv[0]);
		$auj=strtotime(date("Y-m-d",time()));
		$flag=0; //la date dans cde_en_cours est sup à la date d'aujourd'hui
//mise à jour de la table_cde_en_cours par recopie de la table_cde
//cette mise à jour ne se fait que si     
//		[Date de la table cde_en_cours] < DateAujourd'hui
//      et si      [datelimite] <= [date_aujourd'hui] <= [dateProchaineLivraison]

// POUR LA MISE EN ROUTE, AVANT LA 1RE LIVRAISON, METTRE UNE DATE POUR date de livraison DANS amap_brebis_cde_en_cours QUI SOIT
//      ANTERIEURE A LA DATE DU JOUR. AINSI LE PRODUCTEUR POURRA VIVUALISER LA TABLE amap_brebis_cde POUR INFORMATION.
// QUAND LA DATE DE LA 1RE LIVRAISON - 9j SERA ATTEINTE LA TABLE amap_brebis_cde_en_cours (QUI CONTIENT N'IMPORTE QUOI)
//		SE METTRA A JOUR ET LE CYCLE NORMAL	DE FONCTIONNEMENT SE METTRA EN ROUTE

//cette partie de programme se trouve aussi dans le menu d'accès à la commande perso de chaque amapien
//si bien que c'est le premier accès d'un amapien ou le premier acces du producteur après la date limite qui provoque
//la mise à jour de la table cde_en_cours 
		if($DateLivEnCours==NULL || $auj>$DateLivEnCours ) { 
			$flag=-1;//impossible d'imprimer les amapiens peuvent encore modifier leur choix
			while($DateLiv = mysqli_fetch_array($reponse1)) {
			$temp=strtotime($DateLiv['Date']);
			 $limite=$temp-JOURS_MARGE_CHEVRE*24*60*60;
  			 if($auj>=$limite && $auj<=$temp) { 
  					$flag=1;
  					$LaDate=$DateLiv['Date'];
  					$ProchLiv=date("d-M-Y",strtotime($DateLiv['Date']));
  					break;
  			}
      }
		}
		if($flag==0) $ProchLiv=date("d-M-Y",strtotime($TableDateLiv[0]));
		if($flag==1) {
			$question="TRUNCATE TABLE amap_chevre_cde_en_cours";
			$reponse=mysqli_query($question) or die(mysqli_error());;
			$question="INSERT INTO amap_chevre_cde_en_cours SELECT * FROM amap_chevre_cde";
			$reponse=mysqli_query($question) or die(mysqli_error());;
			$question="UPDATE amap_chevre_cde_en_cours SET Date_livraison='".$LaDate."'";
			$reponse=mysqli_query($question) or die(mysqli_error());;
		}
    // on récupère le nombre d'unité de chaque produit dans l'ordre des ID des produits
    $resultUnit=mysqli_query("SELECT Unite FROM amap_chevre_produits ORDER BY Id") or die(mysqli_error());
    while ( $unite = mysqli_fetch_array($resultUnit)) {
     $unites[] =  $unite[0];
    };
    
 		if($flag !=-1 ) {?>		
			<button onclick="document.location.href='ImprimePDF_ProdChevre.php'" name="BtnImprime" type="button" class="BtnStd">Enregistrer pour imprimer</button>
		<?php 
      $tableAlire= 'amap_chevre_cde_en_cours';
    } 
		else { ?>
			<h3 class="mot_passe_recette">
			 ATTENTION : Les amapiens peuvent encore modifier leur choix jusqu'à la date de livraison moins 9 jours !!
       Ce récapitulatif n' est pas définitif
      </h3>
		<?php
      $tableAlire= 'amap_chevre_cde';    
    } 

     ?>
		<button onclick="document.location.href='index.php'" name="BtnRetour" type="button" class="BtnStd">Retour</button><br />
	</div>

  <div>
    	<?php
       	$question="SELECT * FROM ".$tableAlire." ORDER BY Nom"; 
      	$reponse = mysqli_query($question) or die(mysqli_error());;
      	$ligne = mysqli_num_rows($reponse);
        
        $nom= $donnees['Nom'].' '.$donnees['Prenom'];
      	if (strlen($nom) > 22 ) {
          $nom = substr($nom,0,22);
          $nom = $nom.'.';
        }
      	?>
    		<table  style="text-align: center; border-collapse: collapse; margin: 5px auto;">
    			<caption style="
    				margin: 5px auto;
    				background-color: #DDDDFF;
    				border: 2px ridge white;
    				text-align: center;
    				padding: 0px 0px 0px 10px;
    				color: red;
    				font-weight: bold">Commandes de fromage de chèvre enregistrées pour la livraison du <?php echo $ProchLiv; ?>
    			</caption>
           <!-- 2 lignes de titre -->
    			<tr>
    				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Nom</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Unité</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">&nbsp;&nbsp;Emargement&nbsp;&nbsp;</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-left-width: 2px; border-right-width: 2px;" colspan="5">Petit</th>
    	   		<th style="border: 1px solid black; background-color: #DDDDDD; border-left-width: 2px; border-right-width: 2px;" colspan="5">Grand</th>
    	   		<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2" colspan="1">Pyramide cendrée</th>
        		<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2" colspan="1">Fromage Blanc</th>
            <th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Total</th>
    			</tr>
    			<tr>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-left-width: 2px;">frais</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">affiné</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">sec</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">sésame</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">provence</th>
      			<th style="border: 1px solid black; background-color: #DDDDDD; border-left-width: 2px;">frais</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">affiné</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">sec</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">sésame</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">provence</th>
     			</tr>
          
          <!-- les lignes des commandes -->
          <?php 
              $totalUniteLivraison =0;
              $j=0;
              while($donnees = mysqli_fetch_array($reponse)) {  
                $j++; ?>
                <tr style="background-color: <?php if($j % 3==1) echo '#40a7f5'; elseif($j % 3==2) echo '#f9f580'; else echo 'white';?>">
                  <td style="border: 1px solid black;"><?php echo $donnees['Nom'].' '.$donnees['Prenom'];?></td>
                  <td style="border: 1px solid black;"><?php echo $donnees['Unite'];?></td>
                  <td style="border: 1px solid black;"></td>
            
            			<?php
                  $totalUniteLigne =0;        
                  $currentField = 0;
                   
                  foreach ($donnees as $key => $value) { 
                    if ( intval($key) != 0)
                           continue; //on passe  les key qui ne sont pas des champs
                    if (  $key == "id" || $key == "Unite" || $key == "Date_livraison" || $key == "Date_modif" || $key == "Nom" || $key == 'Prenom')
                          continue;  ?>
                         
                    <td style="border: 1px solid black;"> 
                        <?php 
                          if ($value != 0)  {
                            $totalUniteLigne += ($value * $unites[$currentField]);
                            echo $value;
                          }
                          $currentField++; ?>
                    </td>
                  <?php } 
                  if ($donnees['Unite'] != $totalUniteLigne) { ?>
                  <td style="border: 1px solid black; color: red"><?php echo $totalUniteLigne;?></td>   
                  <?php } else { ?>
                  <td style="border: 1px solid black"><?php echo $totalUniteLigne;?></td>  
                  <?php } ?>  
          			</tr>
      		 	<?php 
                $totalUniteLivraison += $totalUniteLigne;
              } ?>
    
          <!-- une ligne de total  -->
          <tr>  
       			<tr>
      				<th style="border: 1px solid black; background-color: #DDDDDD;">Nombre d'unités</th>
      				<th style="border: 1px solid black; background-color: #DDDDDD;">
      				<?php	
              $erreur = 0;
       				$question = "SELECT SUM( Unite ) AS total FROM ".$tableAlire.";";
        				$result=mysqli_fetch_array(mysqli_query($question));
                if ( $totalUniteLivraison != $result[0] ) {$erreur=1;}
        			?>
      				</th>
      				<th style="border: 1px solid black; background-color: #DDDDDD;"></th>
      				
              <?php
              // nouveau parcours des commandes pour récupérer les noms des produits
              $reponse = mysqli_query("SELECT * FROM ".$tableAlire." ORDER BY Nom");
              $donnees = mysqli_fetch_array($reponse);
              foreach ($donnees as $key => $value) { 
                    if ( intval($key) != 0)
                           continue; //on passe  les key qui ne sont pas des champs
                    if (  $key == "id" || $key == "Unite" || $key == "Date_livraison" || $key == "Date_modif" || $key == "Nom" || $key == 'Prenom')
                          continue;  ?>
                         
                    <th style="border: 1px solid black; background-color: #DDDDDD;">
                      <?php 
                          $question = "SELECT SUM( ".$key." ) AS total FROM ". $tableAlire;
        				          $result=mysqli_fetch_array(mysqli_query($question));
         				          echo $result[0];
                      ?>
                    </th>
              <?php } ?>
              <th style="border: 1px solid black; background-color: #DDDDDD;">
                     <?php  echo $totalUniteLivraison; ?>
              </th>
      			</tr>		
    
          <!-- 2 lignes de titre -->
          <tr>
    				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Nom</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Unité</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">&nbsp;&nbsp;Emargement&nbsp;&nbsp;</th>
     				<th style="border: 1px solid black; background-color: #DDDDDD; border-left-width: 2px;">frais</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">affiné</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">sec</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">sésame</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">provence</th>
      			<th style="border: 1px solid black; background-color: #DDDDDD; border-left-width: 2px;">frais</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">affiné</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">sec</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">sésame</th>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-right-width: 2px;">provence</th>
            <th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2" colspan="1">Pyramide four</th>
        		<th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2" colspan="1">Fromage Blanc</th>
            <th style="border: 1px solid black; background-color: #DDDDDD;" rowspan="2">Total</th>
    			</tr>
      		<tr>
    				<th style="border: 1px solid black; background-color: #DDDDDD; border-left-width: 2px; border-right-width: 2px;" colspan="5">Petit</th>
    	   		<th style="border: 1px solid black; background-color: #DDDDDD; border-left-width: 2px; border-right-width: 2px;" colspan="5">Grand</th>
     			</tr>
     		</table>
        
        <?php if ( $erreur==1) { ?>
        <h3 style="color: red;"> 
          ATTENTION : Le nombre d&apos;unité  de la commande ne correspond pas au nombre d&apos;unité attendu. Contactez le référent pour plus d&apos;information.
        </h3>
        <?php  } ?>
   </div>
  </body>
  </html>
     
<?php 
	mysqli_close();
} 
else { // le mot de passe n'est pas bon : On affiche la zone de texte pour rentrer de nouveau le mot de passe.
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
			<form class="mot_passe_recette" onsubmit="return verifkey(this.motpasse);" method="post" action="liste_chevre_encours.php" >
				<p class="mot_passe_recette">
					<?php if($ok==0) {  ?>
              Mot de passe incorrect!!<br />
          <?php } ?>
					<label for="motpasse">Mot de passe</label> : <input type="password" name="motpasse" id="motpasse" size="50" maxlength="45" tabindex="10"/>
					<input type="submit" tabindex="20"/> <input type="reset" tabindex="30"/>
				</p>
			</form>

		</div>		
	</body>
</html>
<?php } 
?>
