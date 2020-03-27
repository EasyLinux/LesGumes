<!--
fonction AfficherTable(base,table,ordreAffichage)
fonction AfficheTousLesContrats(base,table,ListeChamps,NbreChamp,ordreAffichage)
fonction ChoixDuNom(base, table, amap_exclue, label_liste_de_choix, ordre de la liste, action)
				affiche la liste générale en excluant ceux de l'amap_exclue dans une liste de choix
				action=action de la méthode POST
function SupprimeEnr(base, table, id) supprime l'enregistrement de champ id de la table
function AjouteEnr(base, table, id) ajoute l'enregistrement id dans la table
function MajChamp(base, table, id, champ, valeur) met à valeur le champ dont l'id est donné
function AfficheFormulaireEnr(base, table, id, action) pour modifier l'enregistrement id de table. action =action de la methode post du formulaire
function MajEnr(base, table, id) met à jour tous les champ de l'enregistrement id reçu par une méthode POST
function VoirContratH(base, table, id) affiche le(s) contrat(s) horizontalement
function VoirContratV(base, table, id)  	affiche le(s) contrat(s) verticalement ajout/suppression de contrats possible 
		
-->
<!--***********************************************************************************************************-->
<!-- debut fonction afficher table -->	
<!--***********************************************************************************************************-->
<?php

function AfficherTable($base,$table,$ordre1)
{
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db($base); // Sélection de la base 
	$question="SELECT * FROM ".$table." ORDER BY ".$ordre1;
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
				if($i % 2 ==0) {?><th 	style="
										text-align: center;
										padding: 2px 5px 2px 5px;
										border: 2px ridge white;
										background-color: #CCCCCC"				
									><?php echo $cle; ?></th>
			<?php   } } } ?>
		</tr>
		<?php
		$reponse = mysql_query($question);
		while($donnees = mysql_fetch_array($reponse)) { ?>
			<tr>
				<?php 
				$i=0;
				foreach($donnees as $element) { 
					$i++;
					if($i % 2 ==0) {
						if($i==2) {?><td style="
											white-space:nowrap;
											text-align: center;
											padding: 2px 5px 2px 5px;
											border: 1px solid black;
											background-color: white"
										><?php echo $element; ?></td><?php	}
						else { ?><td style="
											white-space:nowrap;
											text-align: center;
											padding: 2px 5px 2px 5px;
											border: 1px solid black;
											background-color: white"
									><?php echo $element; ?></td><?php	}
					} 
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
<!--***********************************************************************************************************-->
<!-- fin fonction liste deroulante des noms -->	
<!--***********************************************************************************************************-->
<?php
function SupprimeEnr($base, $table, $enr) {
	echo "<br /><br />SupprimeEnr<br />";
	echo "connexion = ".mysql_connect(hote, login, mot_passe_sql)."<br />"; // Connexion à MySQL
	if(mysql_select_db($base)) echo "selection-base = true<br />"; else echo "selection-base = false<br />";
	$question="DELETE FROM ".$table." WHERE id='".$enr."'";
	echo mysql_query($question);
	echo "<br />".$question."<br />";
	mysql_close();

}
?>
<?php
function AjouteEnr($base, $table, $id) {
	echo "<br /><br />AjouteEnr<br />";
	echo "connexion = ".mysql_connect(hote, login, mot_passe_sql)."<br />"; // Connexion à MySQL
	if(mysql_select_db($base)) echo "selection-base = true<br />"; else echo "selection-base = false<br />";
	$question="SELECT * FROM amap_generale WHERE id='".$id."'";
	echo $reponse=mysql_query($question);
	echo "<br />".$question."<br />";
	$donnees=mysql_fetch_array($reponse);
	$texte_1=addslashes($donnees['Nom']);
	$texte_2=addslashes($donnees['Prenom']);
	$question="INSERT INTO ".$table."(id, Nom, Prenom) VALUES ('".$id."', '".$texte_1."', '".$texte_2."')";	
	echo mysql_query($question);
	echo "<br />".$question."<br />";
	mysql_close();
}
?>
<?php
function MajChamp($base, $table, $id, $champ, $valeur) {
	echo "<br /><br />MajChamp<br />";
	echo "connexion = ".mysql_connect(hote, login, mot_passe_sql)."<br />"; // Connexion à MySQL
	if(mysql_select_db($base)) echo "selection-base = true<br />"; else echo "selection-base = false<br />";
	$texte=addslashes($valeur);
	$question="UPDATE ".$table." SET ".$champ."='".$texte."' WHERE id='".$id."'";
	echo mysql_query($question);
	echo "<br />".$question."<br />";
	mysql_close();

}
?>
<?php
function AfficheFormulaireEnr($base, $table, $id, $action) {
	echo "<br /><br />AfficheEnr<br />";
	echo "connexion = ".mysql_connect(hote, login, mot_passe_sql)."<br />"; // Connexion à MySQL
	if(mysql_select_db($base)) echo "selection-base = true<br />"; else echo "selection-base = false<br />";
	$question="SELECT * FROM ".$table." WHERE id='".$id."'";
	echo $reponse=mysql_query($question);
	echo "<br />".$question."<br />";
	$donnees=mysql_fetch_array($reponse);
	$i=0;
	?>
	<form method="post" action=<?php echo $action; ?>>
	<p>
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
		<?php echo $table.' adhérents : '.$donnees['Prenom'].' '.$donnees['Nom']; ?></caption>
		<tr>
			<?php
			foreach($donnees as $cle => $element) {
				$i++;
				if($i % 2 ==0) {?><th 	style="
										text-align: center;
										padding: 2px 5px 2px 5px;
										border: 2px ridge white;
										background-color: #CCCCCC"				
									><?php echo $cle; ?></th>
			<?php   } } ?>
		</tr>
		<tr>
			<?php 
			$i=0;
			foreach($donnees as $cle => $element) { 
				$i++;
				if($i % 2 ==0) {
					if($i<7) {?><td style="
										white-space:nowrap;
										text-align: center;
										padding: 2px 5px 2px 5px;
										border: 1px solid black;
										background-color: white"
									><input readonly style="background-color:#DDDDDD" type="text" name="<?php echo $cle; ?>" value="<?php echo $element; ?>"/></td><?php	}
					else { ?>
							<td style="
										white-space:nowrap;
										text-align: center;
										padding: 2px 5px 2px 5px;
										border: 1px solid black;
										background-color: white"
							><input type="text" name="<?php echo $cle; ?>" value="<?php echo $element; ?>"/></td>
					<?php	}
				} 
			}?>
		</tr>
	</table>
	<?php
	mysql_close();
	?>
	<p>
	</form>
<?php
}
?>

<?php
function MajEnr($base, $table) {
	echo "<br /><br />MajEnr<br />";
	echo "connexion = ".mysql_connect(hote, login, mot_passe_sql)."<br />"; // Connexion à MySQL
	if(mysql_select_db($base)) echo "selection-base = true<br />"; else echo "selection-base = false<br />";
	$question="SELECT * FROM ".$table." WHERE id='".$_POST['id']."'";
	$reponse = mysql_query($question);
	$colonne = mysql_num_fields($reponse);
	echo "Nbre colonne = ".$colonne."<br />";
	$donnees=mysql_fetch_array($reponse);
	$question2="UPDATE ".$table." SET";/*.$champ."='".$valeur."' WHERE id='".$id."'";*/
	$i=0;
	foreach($donnees as $cle => $element) { 
		$i++;
		if($i % 2 ==0 && $i>7) {
			if($i==8) { if($_POST[$cle]=='')  $question2.=" ".$cle."=NULL"; else $question2.=" ".$cle."='".addslashes($_POST[$cle])."'";}
			else { if($_POST[$cle]=='') $question2.=", ".$cle."=NULL"; else $question2.=", ".$cle."='".addslashes($_POST[$cle])."'";}
		} 
	}
    $question2.=" WHERE id='".$_POST['id']."'";
	echo "<br />".$question2."<br />";
	echo $reponse=mysql_query($question2)."<br />";
	mysql_close();
}
?>

<?php
function SupTabPermLeg($id) {
	echo "<br /><br />SupTabPermLeg<br />";
	echo "connexion = ".mysql_connect(hote, login, mot_passe_sql)."<br />"; // Connexion à MySQL
	if(mysql_select_db(base_de_donnees)) echo "selection-base = true<br />"; else echo "selection-base = false<br />";
	$question="UPDATE amap_legumes_permanences SET Personne_1='?' WHERE id_1='".$id."'";
	echo mysql_query($question)."<br />";
	$question="UPDATE amap_legumes_permanences SET id_1='0' WHERE id_1='".$id."'";
	echo mysql_query($question)."<br />";
	$question="UPDATE amap_legumes_permanences SET Personne_2='?' WHERE id_2='".$id."'";
	echo mysql_query($question)."<br />";
	$question="UPDATE amap_legumes_permanences SET id_2='0' WHERE id_2='".$id."'";
	echo mysql_query($question)."<br />";
	$question="UPDATE amap_legumes_permanences SET Personne_3='?' WHERE id_3='".$id."'";
	echo mysql_query($question)."<br />";
	$question="UPDATE amap_legumes_permanences SET id_3='0' WHERE id_3='".$id."'";
	echo mysql_query($question)."<br />";
	mysql_close();
}
?>

<?php
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
				<th>Contrat n°<?php echo $j; ?></th>
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
// On ne gère toujours qu'un contrat par amapien : on peut donc simplifier cette fonction
// la version précédente, avec possibilité d'ajout est conservée dans le fichier mes_fonctions_accès_contrat.php
function VoirContratV($base, $table, $id) { 
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db($base); // Sélection de la base 
	$question="SELECT * FROM ".$table." WHERE id='".$id."'";  
	$reponse = mysql_query($question) or die(mysql_error());      
	$colonne = mysql_num_fields($reponse);  
	$ligne = mysql_num_rows($reponse);    

?>
		
	
	<?php
	for($j=1; $j<=$ligne; $j++) {
		$donnees = mysql_fetch_array($reponse);
		if($j==1) { ?>
			<h2 style="color:yellow; text-align:center">Vous êtes inscrit à l'<?php echo str_replace("_", " ",$table); ?><br /> sous le nom : 
			<?php echo $donnees['Prenom']; echo(" ")?> <?php echo $donnees['Nom']; ?></h2> <?php
		}
		$i=0;
		$prix=0.0;?>
		<table class="h3" >
		<caption class="h3">Contrat n°<?php echo $donnees['Contrat_numero']; ?></caption>
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
						<th style="font-size:small"><?php echo $cle; ?></th>
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
  if ( $element==0)    {    // contrat non vérouillé
  ?>
		<form method="post" action=<?php echo "acces_contrat.php?amap=".$table."&amp;id=".$id."&amp;maj=suppression"; ?>>
			<table style="margin-left:auto; margin-right:auto;">
				<input  type="hidden" name="contrat_numero" value="1" />
				<tr><td ><input type="submit" value="Supprimer ce contrat" /></td></tr>
			</table>
		</form><?php
}
}
?>

<?php
function VoirContratBimensuelV($base, $table, $id ) {
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db($base); // Sélection de la base 
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
	<h2 style="color:yellow; text-align:center">Vous êtes inscrit à l'<?php echo $table; ?><br /> sous le nom : 
		<?php echo $donnees['Prenom']; echo(" ")?> <?php echo $donnees['Nom']; ?></h2>
		
	<table class="h3" style="margin-left:5px; float:left">
	  <caption class="h3">Contrat n°<?php echo $donnees['Contrat_numero']; ?></caption>
	
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
	


