<?php
// On ne gère toujours qu'un contrat par amapien 
function VoirContratOeufs( $id) { 
	mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysqli_select_db(base_de_donnees); // Sélection de la base 
	$binome = getNomBinome( $base, $table, $id);
	$question="SELECT * FROM amap_oeufs WHERE id='".$id."'";  
	$reponse = mysqli_query($question) or die(mysqli_error());      
	$colonne = mysqli_num_fields($reponse);  
	$ligne = mysqli_num_rows($reponse);  

	if ( $ligne != 0){	
		$total = 0;
		$donnees = mysqli_fetch_array($reponse);	// 1 seul contrat par amapien
?>
		<h2 style="color:yellow; text-align:center">Vous êtes enregistré au contrat oeufs et poulets <br /> sous le nom : 
			<?php echo $donnees['Prenom']; echo(" ")?> <?php echo $donnees['Nom'];				
			if ( $binome ) { ?> <br /> et avec <?php echo $binome?> comme binôme <?php } ?>
			</h2> 
			
		<table class="h3">
			<caption class="h3">Contrat n°<?php echo $donnees['Contrat_numero']; ?></caption>
			<tr>
				<th >Produit</th>
				<th >Quantité</th>
				<th >Prix unitaire</th>
				<th >Nb livraison</th>
				<th >Sous total</th>
			</tr>
			<tr>
				<td class="h3">Oeufs</td>
				<td class="h3"><?php echo $donnees['Nbre_oeufs']; ?></td>
				<td class="h3"><?php echo $donnees['Prix_oeuf']; ?></td>
				<td class="h3"><?php echo $donnees['Nbre_livraison_oeufs']; ?></td>
				<td class="h3"><?php $sstotal = $donnees['Nbre_oeufs'] * $donnees['Prix_oeuf'] *  $donnees['Nbre_livraison_oeufs'];
									 $total += $sstotal;
									 echo $sstotal ?> &euro;</td>
			</tr>
				<td class="h3">Poule pondeuse</td>
				<td class="h3"><?php echo $donnees['Nbre_poule']; ?></td>
				<td class="h3"><?php echo $donnees['Prix_poule']; ?></td>
				<td class="h3">1</td>
				<td class="h3"><?php $sstotal = $donnees['Nbre_poule'] * $donnees['Prix_poule'];
									 $total += $sstotal;
									 echo $sstotal ?> &euro;</td>
			</tr>
			<td class="h3">Poulets</td>
				<td class="h3"><?php echo $donnees['Nbre_poulet']; ?></td>
				<td class="h3"><?php echo $donnees['Prix_poulet']; ?></td>
				<td class="h3"><?php echo $donnees['Nbre_livraison_poulets']; ?></td>
				<td class="h3"><?php $sstotal = $donnees['Nbre_poulet'] * $donnees['Prix_poulet']*  $donnees['Nbre_livraison_poulets'];
									 $total += $sstotal;
									 echo $sstotal ?> &euro;</td>
			</tr>
			<tr><th  style='background-color:#FFAAFF' colspan='4'>Total</th>
				<th style='background-color:#FFAAFF'><?php echo $total;?> &euro;</th></tr>
			<tr><th  style='background-color:#FFAAFF' colspan='4'>Total du contrat arrondi</th>
				<th style='background-color:#FFAAFF'><?php echo $donnees['Montant_total'];?> &euro;</th></tr>
			<tr><th  colspan='4' style="text-align:right">date de début du contrat</th>
				<td ><?php echo date("d-M-Y",strtotime($donnees['Date_debut_contrat'])); ?></td></tr>
			<tr><th  colspan='4' style="text-align:right">date de fin du contrat</th>
				<td ><?php echo date("d-M-Y",strtotime($donnees['Date_fin_contrat'])); ?></td></tr>
			<tr><th  colspan='4' style="text-align:right">date du paiement</th>
				<td ><?php echo date("d-M-Y",strtotime($donnees['Date_paiement'])); ?></td></tr>
			<tr><th  colspan='4' style="text-align:right">Nombre de chèques</th>
				<td ><?php echo $donnees['Nbre_cheque']; ?></td></tr>				
			<tr><td class="h3" colspan=5 style="color:red">Contrat verrouillé</td></tr>
		</table>
	<?php
	} else { ?>
		<h2 style="color:yellow; text-align:center">Vous n&apos;êtes pas enregistré au contrat oeufs et poulets</h2>
	<?php }
}
?>
