<?php
// On ne g�re toujours qu'un contrat par amapien 
function VoirContratChevre( $id) { 
	mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
	mysql_select_db(base_de_donnees); // S�lection de la base 
	$binome = getNomBinome( $base, $table, $id);
	$question="SELECT * FROM amap_chevre WHERE id='".$id."'";  
	$reponse = mysql_query($question) or die(mysql_error());      
	$colonne = mysql_num_fields($reponse);  
	$ligne = mysql_num_rows($reponse);  

	if ( $ligne != 0){	
		$total = 0;
		$donnees = mysql_fetch_array($reponse);	// 1 seul contrat par amapien
?>
		<h2 style="color:yellow; text-align:center">Vous �tes adh�rent au contrat ch�vre <br /> sous le nom : 
			<?php echo $donnees['Prenom']; echo(" ")?> <?php echo $donnees['Nom'];				
			if ( $binome ) { ?> <br /> et avec <?php echo $binome?> comme bin�me <?php } ?>
			</h2> 
			
		<table class="h3">
			<caption class="h3">Contrat n�<?php echo $donnees['Contrat_numero']; ?></caption>
			<tr>
				<th >Produit</th>
				<th >Quantit�</th>
				<th >Prix unitaire</th>
				<th >Nb livraison</th>
				<th >Sous total</th>
			</tr>
			<tr>
				<td class="h3">Grand</td>
				<td class="h3"><?php echo $donnees['Nbre_Grand']; ?></td>
				<td class="h3"><?php echo $donnees['Prix_Grand']; ?></td>
				<td class="h3"><?php echo $donnees['Nbre_livraison']; ?></td>
				<td class="h3"><?php $sstotal = $donnees['Nbre_Grand'] * $donnees['Prix_Grand'] *  $donnees['Nbre_livraison'];
									 $total += $sstotal;
									 echo $sstotal ?> &euro;</td>
			</tr>
			<tr>
				<td class="h3">Petit Fromage Blanc</td>
				<td class="h3"><?php echo $donnees['Nbre_Petit_Fromage_Blanc']; ?></td>
				<td class="h3"><?php echo $donnees['Prix_Petit_Fromage_Blanc']; ?></td>
				<td class="h3"><?php echo $donnees['Nbre_livraison']; ?></td>
				<td class="h3"><?php $sstotal = $donnees['Nbre_Petit_Fromage_Blanc'] * $donnees['Prix_Petit_Fromage_Blanc'] *  $donnees['Nbre_livraison'];
									 $total += $sstotal;
									 echo $sstotal ?> &euro;</td>
			</tr>
			<tr>
				<td class="h3">Grand Fromage Blanc</td>
				<td class="h3"><?php echo $donnees['Nbre_Grand_Fromage_Blanc']; ?></td>
				<td class="h3"><?php echo $donnees['Prix_Grand_Fromage_Blanc']; ?></td>
				<td class="h3"><?php echo $donnees['Nbre_livraison']; ?></td>
				<td class="h3"><?php $sstotal = $donnees['Nbre_Grand_Fromage_Blanc'] * $donnees['Prix_Grand_Fromage_Blanc'] *  $donnees['Nbre_livraison'];
									 $total += $sstotal;
									 echo $sstotal ?> &euro;</td>
			</tr>
			<tr>	
				<td class="h3" colspan='4'>Suppl�ment</td>
				<td class="h3"><?php $sstotal = $donnees['Supplement'];
									 $total += $sstotal;
									 echo $sstotal?> &euro;</td>
			</tr>
			<tr><th  style='background-color:#FFAAFF' colspan='4'>Total du contrat</th>
				<th style='background-color:#FFAAFF'><?php echo $total;?> &euro;</th></tr>
			<tr><th  colspan='4' style="text-align:right">date de d�but du contrat</th>
				<td ><?php echo date("d-M-Y",strtotime($donnees['Date_debut_contrat'])); ?></td></tr>
			<tr><th  colspan='4' style="text-align:right">date de fin du contrat</th>
				<td ><?php echo date("d-M-Y",strtotime($donnees['Date_fin_contrat'])); ?></td></tr>
			<tr><th  colspan='4' style="text-align:right">date du paiement</th>
				<td ><?php echo date("d-M-Y",strtotime($donnees['Date_paiement'])); ?></td></tr>
			<tr><th  colspan='4' style="text-align:right">Nbre de ch�que</th>
				<td ><?php echo $donnees['Nbre_cheque']; ?></td></tr>				
			<tr><td class="h3" colspan=5 style="color:red">Contrat verrouill�</td></tr>
		</table>
	<?php
	} else { ?>
		<h2 style="color:yellow; text-align:center">Vous n&apos;�tes pas adh�rent du contrat ch�vre</h2>
	<?php }
}
?>