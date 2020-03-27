<?php
  //page valable pour toutes les amaps sauf les produits laitiers
  //  'amap' doit contenir le nom de la table de cette amap   - champ Table_amap de la table liste_amap
  //  'sens' mis à 'v' pour un affichage de la table des produits vertical, pour tout autre valeur ou indéfini il est horizontal
 
  include_once("webmaster/define.php");
  include_once("espace_producteurs/mes_fonctions.php");
  include_once("mailToReferent.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>
	<body>
		<div id="en_tete">
			<?php include_once("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include_once("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php
			 include_once("includes/menu_gauche.php"); 
				
      // données par amap 	
        
      $tableAMAP =$_GET['amap'];       
      mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
			mysql_select_db(base_de_donnees); // Sélection de la base 
      $question = "SELECT * FROM liste_amap WHERE Table_amap= '".$tableAMAP."'";
      $reponse = mysql_query($question) or die(mysql_error()); // Requête SQL
      $donnees = mysql_fetch_array($reponse);  
      $nomAMAP   = $donnees['Nom_amap'];
      $dateDeb = $donnees['Date_deb'];
      $dateFin = $donnees['Date_fin'];
      $etat = $donnees['Etat_inscription'];  //valeurs possibles : ouvert, fermé ou attente
      $heureDebLiv =$donnees['Heure_deb'];
      $heureFinLiv =$donnees['Heure_fin']; 
      $tableProduits =$donnees['Table_produit'];
      $contrat = $donnees['Contrat'];
      $sens =  'horizon' ;       // affichage du tableau en horizontal (par défaut), si trop de champ mettre le tableau en vertical 
      if ( $_GET['sens'] == "v")    $sens =  'vertical' ;
      $conservation = false ;     // vrai si la table de produit contient un champ Conservation à afficher)  
      if ( $tableAMAP == 'amap_brebis' )  {
            $conservation = true;
       }
  
      if ( $tableAMAP == 'amap_produits_laitiers' || $tableAMAP == 'amap_brebis' ||  $tableAMAP == 'amap_tisanes' || $tableAMAP == 'amap_chevre' ||  $tableAMAP == 'amap_oeufs')  { 
            // une autre table pour produits_info pour regrouper les produits de même type
            $tableProduits =  $tableProduits.'_info';
      }
      ?>
       
      
     <!-- tableau des produits -->
	<h3 style="color:yellow; text-align:center; text-decoration:underline"><?php echo $nomAMAP ?> BIO</h3>
	<center>
      <?php if ($sens=="horizon" ){  
        // tableau des produits présenté horizontalement par défaut, mais si trop de produits prendre la présentation verticale
      ?>
        
      		<table class="h3">
    				<caption class="h3">Tableau des produits</caption>    
     
    				<tr class="h3">
              <?php
                $reponse = mysql_query("SELECT Nom_produit FROM ".$tableProduits." ORDER BY id") or die(mysql_error()); // Requête SQL
              ?>
    
    					<th>Désignation</th>            
                <?php while ( $donnees = mysql_fetch_array($reponse) ) {	?>
    						  <td class="h3"><?php echo $donnees[0]; ?></td>
    					<?php } ?>
    				</tr>
            
    		    <?php
    				$reponse = mysql_query("SELECT Quantite FROM ".$tableProduits." ORDER BY id") or die(mysql_error()); // Requête SQL
   				?>
    				<tr class="h3">
    					<th>Quantité</th>
    				  <?php while ($donnees = mysql_fetch_array($reponse) ) {	?>
    						<td class="h3"><?php echo $donnees[0]; ?></td>
    					<?php } ?>
    				</tr>
    			  <?php
    				$reponse = mysql_query("SELECT Prix FROM ".$tableProduits." ORDER BY id") or die(mysql_error()); // Requête SQL
    				?>
    				<tr class="h3">
    					<th>Prix unitaire</th>
    				  <?php while ($donnees = mysql_fetch_array($reponse) ) {	?>
    						<td class="h3"><?php echo $donnees[0]; ?></td>
    					<?php } ?>
      			</tr>	
      		</table>
         <?php }    // fin du if ($sens =horizon)
         
         else { // tableau vertical 
              $question =  "SELECT Nom_produit, Quantite, Prix";
              if ( $conservation )  $question .= ", Conservation ";
              $question .= " FROM ".$tableProduits . " ORDER BY id";
              $reponse = mysql_query( $question ) or die(mysql_error()); // Requête SQL         
    		 ?>
            <table class="h3">
    				<caption class="h3">Tableau des produits</caption>    
     
    				<tr class="h3">
              <th>Désignation</th> 
              <th>Quantité</th> 
              <th>Prix unitaire</th>
              <?php if ( $conservation ) { ?> <th> Conservation</th>   <?php } ?>
            </tr>
            <?php while ($donnees = mysql_fetch_array($reponse) ) {	?>
              <tr>
              	<td ><?php echo $donnees[0]; ?></td>
                <td ><?php echo $donnees[1]; ?></td>
                <td ><?php echo $donnees[2]; ?></td>
                <?php if ( $conservation ) { ?> <td> <?php echo $donnees[3]; ?></th>    <?php } ?>
              </tr>   
            <?php }  //  fin du while     ?>
            </table>
         <?php  } // fin du else ($sens =horizon) 
         if ( $tableAMAP == "amap_farines_huiles" )  {                 ?>
          <h4 style="color:white; text-align:center">Consulter le tableau des produits joint en annexe au contrat </h4>
         <?php 
         } 
           mysql_close(); 
         ?>   
    	
     
      
<!-- fin tableau des produits -->
<!-- debut tableau dates lieu horaire -->
			<table class="h3">
				<caption class="h3">Dates, lieu et horaires des livraisons</caption>
				<tr class="h3">
					<td class="h3">Les vendredis de <?php echo $heureDebLiv; ?> à <?php echo $heureFinLiv; ?><br />
          du <?php echo date("d/m/Y",strtotime($dateDeb)); ?> au  <?php echo date("d/m/Y",strtotime($dateFin)); ?> inclus<br />         
          à la Maison des associations, centre René Couillaud S<sup>t</sup> Sébastien/Loire. <br />
          <a href="planning.php">Voir le planning des livraisons</a>.
          </td>
				</tr>
			</table>
<!-- fin tableau dates horaire lieu -->
      </center>


			<br />
	    <?php if ($etat== 'ouvert') { 
	      // ajout de texte pour l'amap cerises
       if ( $tableAMAP == 'amap_cerises') { ?>
          <h4 style="color:yellow; text-align:center">
          Chaque amapien devra assurer au moins une cueillette sur le site le jeudi en fin d'après midi.
    		  Marie-Paule vous propose un contrat adapté où seul sera encaissé ce qui aura pu être cueilli !<br />
    			Vous faites <strong style="color:yellow">deux chèques</strong> <br />
    			- un chèque correspondant aux trois premières livraisons,<br />
    			- un autre chèque correspondant à la dernière livraison qui ne sera pas encaissé si elle est annulée.
          </h4>
       <?php }
	    else if ( $tableAMAP == 'amap_oeufs') { ?>
     	   <h4 style="color:yellow; text-align:center">Si vous prenez des oeufs, en fin de contrat vous recevrez une pondeuse de un an prête à cuire (ou à congeler) -> couscous, coq au vin, poule au pot...
            Les poulets sont estimés à 15,00 euros, un ajustement sera effectué en fin de contrat en fonction du poids des poulets consommés.
            Les volailles seront proposés de façon exceptionnelle en cours de contrat.  </h4>    	   
      <?php };           
       if  (  $tableAMAP == 'amap_sel') { ?>    
          <h4 style="color:white; text-align:center">
          Pour souscrire un contrat, contactez le coordinateur (bouton ci-dessous) <br />
          Puis <a href="<?php echo $contrat?>">télécharger</a> le contrat en double exemplaire et remettez les au coordinateur le jour de sa permanence. 
         	</h4>
        <?php } else { ?> 
          <h4 style="color:white; text-align:center">
          <h4 style="color:white; text-align:center">
          Vous pouvez rejoindre cette AMAP à tout moment. Le contrat se fera au prorata des distributions restantes. 
          <br /><br />Pour souscrire un contrat, contactez le coordinateur (bouton ci-dessous) <br />
 			    <br /><br />N'oubliez pas de <a href="<?php echo $contrat?>">télécharger</a> le contrat en double exemplaire et <br />
          de vous présenter avec le contrat et les chèques de réglement le jour de votre première distribution. 
         	</h4>
      <?php }} else if ($etat=='attente' ) {   ?> 
          <h4 style="color:white; text-align:center">
          Les inscriptions pour cette AMAP sont closes actuellement. <br />
          Vous pouvez vous inscrire sur la liste d'attente en cliquant ici : <i><a class="h2" href="liste_attente_legumes.php">S'inscrire à la liste d'attente <?php echo $nomAMAP ?></a></i><br />
          et contacter le coordinateur pour plus d'information (bouton ci-dessous).
          <br /><br /><a href="<?php echo $contrat?>">Télécharger</a> le contrat en cours.         
          </h4>
      <?php } else { //inscriptions closes actuellement ?>
          <h4 style="color:white; text-align:center">
          Les inscriptions pour cette AMAP sont closes actuellement. <br />
          Pour plus d'information, contactez le coordinateur (bouton ci-dessous).
         <br /><br /><a href="<?php echo $contrat?>">Télécharger</a> le contrat en cours.
        </h4>
      <?php } ?>
      	
			</p>
			
				<?php 
          $string = "Ecrire à ";
          $mailto = "";
    	    MailToReferent( $tableAMAP, $string, $mailto);
        ?>	
        
  		<h4 >
		<a style="text-align:center; color:yellow" href="<?php echo $mailto?><?php echo ($string) ?> " >
                <img src="/images/mail.jpg" alt="Envoyer un mail au référent" title="Cliquer ici pour envoyer un mail au référent"/></a><br />
		<a style="text-align:center; color:yellow" href="<?php echo $mailto?><?php echo ($string) ?> " >
                Ecrire au référent du contrat : <?php echo $mailto?></a>
		
        
        <!-- a style="cursor:pointer" title="liste attente" href="liste_attente_legumes.php">Liste Attente Légumes</a-->
       </h4>
		</div>	
		
		<div id="pied_page">
			<!-- <?php include_once("includes/pied_page.php") ?> -->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</p>
	</body>
</html>
