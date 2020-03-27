<!--
fonction AfficherTable(base,table,tri,sens,adressePage)
fonction AfficheTousLesContrats(base,table,ListeChamps,NbreChamp,ordreAffichage)
fonction ChoixDuNom(base, table, amap_exclue, label_liste_de_choix, ordre de la liste, action)
				affiche la liste générale en
				excluant ceux de l'amap_exclue dans une liste de choix
				action=action de la méthode POST
-->
<!--***********************************************************************************************************-->
<!-- debut fonction afficher table -->	
<!--***********************************************************************************************************-->
<?php

function AfficherTable($base,$table,$tri,$sens, $adressePage)
{
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db($base); // Sélection de la base 
	$question="SELECT * FROM ".$table." ORDER BY ".$tri." ".$sens;
	$reponse = mysql_query($question);
	$ligne = mysql_num_rows($reponse);
	$donnees = mysql_fetch_array($reponse);
	$i=0;
?>
	<h2> <?php echo $table.' - nombre d\'adhérents : '.$ligne; ?></h2>
	<br />

	<table >
			<tr>
		<?php
		$exclus=-1;
		if ($ligne>0) {
		foreach($donnees as $cle => $element) {
			$i++;
			if($i % 2 !=0) continue;
			if ( $cle == 'Mot_passe') {$exclus=$i-1; continue;}		
			?>
			<th>
			<?php // calcul des variables : $tri (colonne du tri)  et sens (Asc ou desc)
			$triCol = $cle;
			if ( $tri !=$cle ) {
				 $sensCol="ASC"; } 
			else { //on change le sens du tri
				$sensCol = $sens=="ASC" ? "DESC" : "ASC";
			}
			?>
				<a href="<?php echo $adressePage;?>?classement=<?php echo $triCol;?>&amp;sens=<?php echo $sensCol;?>&amp;amap=<?php echo $_GET['amap']; ?>&amp;ordre=rien"> 
				<?php echo $cle;?> </a>
			</th>
		<?php }} ?>
		</tr>
		<?php
		$reponse = mysql_query($question);
		while($donnees = mysql_fetch_array($reponse)) { ?>
			<tr>
				<?php 
				$i=0;
				foreach($donnees as $element) { 
					$i++;
					if($i % 2 ==1 && ($i != $exclus)) {
						?><td><?php echo $$tmp.$element; ?></td><?php	}
				}?>
			</tr>
		<?php
		}
		mysql_close();
		?>
	</table>		
<?php } ?> 	


<!--***********************************************************************************************************-->
<!-- fin fonction afficher table -->	
<!--***********************************************************************************************************-->
<!--***********************************************************************************************************-->
<!-- debut fonction afficher certains champs d'une table -->	
<!--***********************************************************************************************************-->
<?php

function AfficherTousLesContrats($base,$table,$ListeChamps, $NbreChamps, $ordre)
{
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db($base); // Sélection de la base 
	for($i=0; $i<$NbreChamps; $i++) {
		if($i==0) $question="SELECT ".$ListeChamps[$i];
		else $question.=", ".$ListeChamps[$i];
	}
	$question.=" FROM ".$table." ORDER BY ".$ordre;
	$reponse = mysql_query($question);
	/*$colonne = mysql_num_fields($reponse);*/
	$ligne = mysql_num_rows($reponse);
	$donnees = mysql_fetch_array($reponse);
	$i=0;
?>
	<table 	style="
			margin: 5px auto;
			border-collapse: collapse;
			border: 2px ridge white"
			>
		<caption style="
			margin: 5px auto;
			background-color: #DDDDFF;
			border: 2px ridge white;
			text-align: left;
			padding: 0px 0px 0px 10px;
			color: red;
			font-weight: bold"		
		>
		<?php echo $table.' - nombre d\'adhérents : '.$ligne; ?></caption>
		<tr>
			<?php
			if($ligne>0) {
				foreach($donnees as $cle => $element) {
					$i++;
					if($i % 2 ==0 && ($i/2)%2==0 || $i==2) {?><th 	style="
											text-align: center;
											padding: 2px 5px 2px 5px;
											border: 2px ridge white;
											background-color: #CCCCCC"				
										><?php echo $cle; ?></th>
				<?php   }
				} ?>
				<th style="
					text-align: center;
					padding: 2px 5px 2px 5px;
					border: 2px ridge white;
					background-color: #CCCCCC"				
				>Total</th>
			<?php } ?>
		</tr>
		<?php
		$reponse = mysql_query($question);
		$PrixTotal=0.0;
		while($donnees = mysql_fetch_array($reponse)) {
			$prix=0.0;?>
			<tr>
				<?php 
				$i=0;
				foreach($donnees as $element) { 
					$i++;
					if($i % 2 ==0) {
						if($i==2) {
							?><td style="
											white-space:nowrap;
											text-align: center;
											padding: 2px 5px 2px 5px;
											border: 1px solid black;
											background-color: white"
							><?php echo $element; ?></td><?php 
						}
						else {
							if(($i/2)%2==0) {
								?><td style="
												white-space:nowrap;
												text-align: center;
												padding: 2px 5px 2px 5px;
												border: 1px solid black;
												background-color: white"
								><?php echo $element; ?></td><?php 
							}
							if(($i/2) % 2 == 0) $qte=$element;
							else $prix+=$qte*$element;
						}
					} 
				} ?>
				<td style="
								white-space:nowrap;
								text-align: center;
								padding: 2px 5px 2px 5px;
								border: 1px solid black;
								background-color: #FFFFAA"
				><?php echo sprintf('%.2f',round($prix,2)).'&euro;'; ?></td><?php
				$PrixTotal+=$prix;?>
			</tr>
		<?php
		} 
	/********* affichage des quantites totales et du prix total */
		$reponse = mysql_query($question);
		$colonne = mysql_num_fields($reponse);?>
		<tr> 
			<th style="
				text-align: center;
				padding: 2px 5px 2px 5px;
				border: 2px ridge white;
				background-color: #CCCCCC"				
			>Total</th><?php
			for($j=0; $j<($colonne-1)/2; $j++) {
				$qte=0;
				$reponse = mysql_query($question);
				for($i=0; $i<$ligne; $i++) {
					$donnees = mysql_fetch_array($reponse);
					$qte+=$donnees[2*$j+1];
				} ?>
				<td style="
								white-space:nowrap;
								text-align: center;
								padding: 2px 5px 2px 5px;
								border: 1px solid black;
								background-color: #FFFFAA"
				><?php echo sprintf('%d',round($qte,0)); ?></td>
			<?php }?>
			<td style="
								white-space:nowrap;
								text-align: center;
								padding: 2px 5px 2px 5px;
								border: 1px solid black;
								background-color: #FFAAFF"
			><?php echo sprintf('%.2f',round($PrixTotal,2)).'&euro;'; ?></td>
		</tr>
		
		<?php
		mysql_close();
		?>
	</table>		
<?php } ?> 	
<!--***********************************************************************************************************-->
<!-- fin fonction afficher certains champs d'une table -->	
<!--***********************************************************************************************************-->
<!--***********************************************************************************************************-->
<!-- debut fonction liste deroulante des noms -->	
<!--***********************************************************************************************************-->
<?php
function ChoixDuNom($base, $table, $amap_exclue, $message, $classement, $action) {
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db($base); // Sélection de la base 
	$question="SELECT * FROM ".$table." ORDER BY ".$classement;
	$reponse = mysql_query($question);
?>
<form method="post" action=<?php echo $action; ?>>
   <p>
       <label for="adherent"><?php echo $message; ?></label>
       <select name="adherent" id="adherent">
	   <?php
			while($donnees = mysql_fetch_array($reponse)) {
					if($amap_exclue=='amap_viande_bovine' //&& $donnees['Amap_viande_bovine']!='1'
						|| $amap_exclue=='amap_legumes' && $donnees['Amap_legumes']!='1'
						|| $amap_exclue=='amap_pommes' //&& $donnees['Amap_pommes']!='1'
						|| $amap_exclue==''
					) {
	   ?>
               <option value=<?php echo $donnees['id']; ?>><?php echo $donnees['Nom'].' '.$donnees['Prenom'].' ('.$donnees['id'].')'; ?></option>
		<?php } } ?>
       </select>
	   <input type="submit" value="ok" />
   </p>
</form>
<?php 
	mysql_close();
} ?>
