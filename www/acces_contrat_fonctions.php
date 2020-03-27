

<?php
//function VoirContratH(base, table, id) affiche le(s) contrat(s) horizontalement
//function VoirContratV(base, table, id)  	affiche le(s) contrat(s) verticalement ajout/suppression de contrats possible 	
//function VoirContratBimensuelV($base, $table, $id)


/* recherche si l'amapien d'id $id est bien enregistré dans la $table */
function InscritAuContrat( $base, $table, $id) {
	$question="SELECT * FROM ".$table." WHERE id='".$id."'";
	$reponse = mysql_query($question) or die(mysql_error());
	$ligne = mysql_num_rows($reponse);
	return $ligne > 0; 
}
?>

<?php
/* recherche si l'amapien d'id $id est avec un binome  dans la $table.
   retourne  l'id du binome inscrit au contrat si il y a bien un binome, -1 sinon */
function getIdBinome( $base, $table, $id) {
	$question="SELECT id_contrat FROM binome WHERE id_binome='".$id."' AND type_amap='".$table."'";

	$reponse = mysql_query($question) or die(mysql_error()); 
	if ( $donnees=mysql_fetch_array($reponse) ) { // c'est un binome
		$idContrat = $donnees['id_contrat'];
	} else { 
		$idContrat = -1;
	}
	
	return $idContrat;		
}
?>

<?php
/* retourne  l'id du binome inscrit au contrat avec l'amapien $id, si il y a bien un binome, null sinon */
function getNomBinome($base, $table, $id) {
	$question="SELECT B.id_binome, CONCAT_WS(' ',G.Prenom, G.Nom) as nomBinome
			FROM binome B, amap_generale G
			WHERE B.id_contrat='".$id."' AND type_amap='".$table."'
			AND G.id = B.id_binome";

	$reponse = mysql_query($question) or die(mysql_error()); 
	if ( $donnees= mysql_fetch_array($reponse) ) { // c'est un binome
		$binome = $donnees['nomBinome'];
	}
	else $binome =null;

	return $binome;		
}
?>

<?php
/* affiche le */
function VoirContratH($base, $table, $id) {
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db($base); // Sélection de la base 
	
	$question="SELECT * FROM ".$table." WHERE id='".$id."'";
	$reponse = mysql_query($question);
	/*$colonne = mysql_num_fields($reponse);*/
	$ligne = mysql_num_rows($reponse);
?>
	<table class="h3">
		<caption class="h3">Vous êtes enregistré dans : <?php echo $table; ?></caption>
		<?php
		for($j=1; $j<=$ligne; $j++) {
			$donnees = mysql_fetch_array($reponse);
			if($j==1) { /* écrire le nom et les produits*/
				$i=0;
				foreach($donnees as $cle => $element) {
					$i++;
					if($i==4 || $i==6) {
						?>
						<tr>
							<th><?php echo $cle; ?></th>
							<td class="h3"><?php echo $element; ?></td>
						</tr>
						<?php
					}
				} ?>
				<tr>
					<th></th>
					<?php
					$i=0;
					foreach($donnees as $cle => $element) {
						$i++;
						if($i % 2 == 0 && $i>8) { ?>
							<th class="h3"><?php echo $cle; ?></th>
							<?php
						} 
					} ?>
				</tr> <?php
			} ?>
			<tr>
				<?php
				$i=0;
				foreach($donnees as $element)
				{
					$i++;
					if($i % 2 ==0 && $i>8) {
						?>
						<td class="h3"><?php echo $element; ?></td>
						<?php
					} 
				} ?>
			</tr> <?php
		}
		mysql_close();
		?>
	</table>		
<?php
}
?>

<?php
/* affiche les caractéristiques du contrat de l'amapien d'id $id
	Attention on ne gère qu'un contrat par amapien 
	La version précédente, avec possibilité d'ajout est conservée dans le fichier mes_fonctions_accès_contrat.php */
	
function VoirContratV($base, $table, $id) { 
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db($base); // Sélection de la base 
	$binome = getNomBinome( $base, $table, $id);
	$question="SELECT * FROM ".$table." WHERE id='".$id."'";  
	$reponse = mysql_query($question) or die(mysql_error());      
	$colonne = mysql_num_fields($reponse);  
	$ligne = mysql_num_rows($reponse);    
?>
	
	<?php
	for($j=1; $j<=$ligne; $j++) {
		$donnees = mysql_fetch_array($reponse);
		if($j==1) { ?>
			<h2 style="color:yellow; text-align:center">Vous êtes inscrit à l&apos;<?php echo str_replace("_", " ",$table); ?><br /> sous le nom : 
			<?php echo $donnees['Prenom']; echo(" ")?> <?php echo $donnees['Nom']; 			
			if ( $binome ) { ?> <br /> et avec <?php echo $binome?> comme binôme <?php } ?>
			</h2> <?php
		}
		$i=0;
		$prix=0.0;?>
		<table class="h3">
		<?php
		$suppression = false;
		foreach($donnees as $cle => $element) {
			$i++;
			if($i%2==0 && $i>8) {
				if($i==2*$colonne-10) {?>
					<tr>
						<th style="background-color:#FFAAFF; ">sous-total :</th>
						<td class="h3" style="background-color:#FFAAFF"><?php echo sprintf('%.2f',round($prix,2))." &euro;"; ?></td>
					</tr> <?php
				}            
				if($i==2*$colonne-2) $prix*=$element;
				if($i==2*$colonne) {?>
					<tr>
						<th style="background-color:#FFAAFF">Total :</th><td class="h3" style="background-color:#FFAAFF"><?php echo sprintf('%.2f',round($prix,2))." &euro;"; ?></td>
					</tr><?php
				}
				if($i!=2*$colonne) {?>
					<tr>
						<th style="font-size:small"><?php echo str_replace("_", " ",$cle);$cle; ?></th>
						<?php
						if($i<2*$colonne-11 && ($i/2) % 2==1) {
							$valeur=$element;
							?><td class="h3"><?php echo $element; ?></td><?php
						}
						elseif($i<2*$colonne-11 && ($i/2) % 2==0) {
							$prix+=$valeur*$element;
							?><td class="h3" style="background-color:#FFFF88"><?php echo $element; ?></td><?php
						}
						elseif ($i>2*$colonne-10 && $i<2*$colonne-2 && $element) {
							?><td class="h3" style="font-size:small"><?php echo date("d-M-Y",strtotime($element)); ?></td><?php
						}
						else { ?><td class="h3"><?php echo $element; ?></td><?php } ?>
					</tr><?php
				}
				else if($element==1){ 
					?><tr><td class="h3" colspan=2 style="color:red">contrat verrouillé</td></tr><?php
				}
        else { $suppression = true;}       
			}  
		} ?>
		</table>
	<?php
	}
}
?>

<?php
function VoirContratBimensuelV($base, $table, $id ) {
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db($base); // Sélection de la base 
	$binome = getNomBinome( $base, $table, $id);
	$question="SELECT * FROM ".$table." WHERE id='".$id."'";
	$reponse = mysql_query($question);
  
  $libelle_prix = "Prix";
  $libelle_mois = "Nbre_par_mois";
  $libelle_quizaine = "Nbre_par_quinzaine";  
  $format = '%.2f';
  
  if ( $table=="amap_poulets") {
      $libelle_prix = "Prix_poulet";
  } elseif (  $table=="amap_champignons") {
      $libelle_mois = "Poids_par_mois";
      $libelle_quizaine = "Poids_par_quinzaine"; 
      $format = '%.3f'; 
  }
  $donnees = mysql_fetch_array($reponse);
	mysql_close();
?>
	<h2 style="color:yellow; text-align:center">Vous êtes inscrit à l&apos;<?php echo str_replace("_", " ",$table); ?><br /> sous le nom : 
			<?php echo $donnees['Prenom']; echo(" ")?> <?php echo $donnees['Nom']; 
			if ( $binome ) { ?> <br /> et avec <?php echo $binome?> comme binôme <?php } ?>
	</h2>
		
	<table class="h3" style="margin-left:5px; float:left">
	
    <tr>
      <th style="font-size:small">Par_mois</th>
      <td><?php $nbMois= $donnees[$libelle_mois]; 
              echo $nbMois ?></td>    
    </tr> 
    <tr>
      <th style="font-size:small">Par_quinzaine</th>
      <td><?php $nbQuinzaine= $donnees[$libelle_quizaine];
                echo $donnees["$libelle_quizaine"]; ?></td>
    </tr>
    <tr>
      <th style="font-size:small">Prix unitaire</th>
      <td><?php $prixUnitaire=$donnees[$libelle_prix];
                echo sprintf($format,$prixUnitaire)." &euro;"; ?></td>
    </tr>
   	<tr>
			<th style="background-color:#FFAAFF; ">sous-total :</th>
			<td class="h3" style="background-color:#FFAAFF">
          <?php   $prix =  ($nbQuinzaine+ $nbMois) * $prixUnitaire;
          echo sprintf('%.2f',round($prix,2))." &euro;"; ?></td>
		</tr>
		<tr>
			<th style="font-size:small">Nbre_livraison</th>
			<td >
          <?php  $nbLivraison = $donnees["Nbre_livraison"];
              $prix = $prix*$nbLivraison;
              // pour les dates : echo date("d-M-Y",strtotime($element)); 
              echo $nbLivraison; ?>
      </td>
		</tr>			
	  <tr>
				<th style="background-color:#FFAAFF">Total :</th>
        <td class="h3" style="background-color:#FFAAFF">
            <?php echo sprintf('%.2f',round($prix,2))." &euro;"; ?>
        </td>
		</tr>
		<tr>
        <td class="h3" colspan=2 style="color:red">contrat verrouillé</td>
    </tr>
  </table>
<?php		
}
?>
	


