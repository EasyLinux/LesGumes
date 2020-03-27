<?php
function VoirContratTisane ( $id) { 
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db(base_de_donnees); // Sélection de la base 
	$table= 'amap_tisanes';
	$question="SELECT * FROM $table WHERE id='".$id."'";  
	$reponse = mysql_query($question) or die(mysql_error());
   	$donnees = mysql_fetch_array($reponse); 
?>
	<h2 style="color:yellow; text-align:center">Vous êtes inscrit à l'AMAP Tisanes<br /> sous le nom : 
		<?php echo $donnees['Prenom']; echo(" ")?> <?php echo $donnees['Nom']; ?></h2>
			
	<table class="h3" style="margin-left:5px; float:left">
	  <caption class="h3">Description du contrat </caption>
	
    <tr>
      <th style="font-size:small" rowspan='4'>premier trimestre</th>
	  <td> tisane unitaire </td>
      <td><?php echo $donnees["t1_unitaire"];?></td>    
    </tr> 
	<tr>
  	  <td> petite composition </td>
      <td><?php echo $donnees["t1_petit"];?></td>    
    </tr>
	<tr>
  	  <td> grande composition </td>
      <td><?php echo $donnees["t1_grand"];?></td>    
    </tr>	
	<tr>
  	  <td> sirop </td>
      <td><?php echo $donnees["t1_sirop"];?></td>    
    </tr>
	 <tr>
      <th style="font-size:small" rowspan='4'>second trimestre</th>
	  <td> tisane unitaire </td>
      <td><?php echo $donnees["t2_unitaire"];?></td>    
    </tr> 
	<tr>
  	  <td> petite composition </td>
      <td><?php echo $donnees["t2_petit"];?></td>    
    </tr>
	<tr>
  	  <td> grande composition </td>
      <td><?php echo $donnees["t2_grand"];?></td>    
    </tr>	
	<tr>
  	  <td> sirop </td>
      <td><?php echo $donnees["t2_sirop"];?></td>    
    </tr>
	<tr>
      <th style="font-size:small" rowspan='4'>troisième trimestre</th>
	  <td> tisane unitaire </td>
      <td><?php echo $donnees["t3_unitaire"];?></td>    
    </tr> 
	<tr>
  	  <td> petite composition </td>
      <td><?php echo $donnees["t3_petit"];?></td>    
    </tr>
	<tr>
  	  <td> grande composition </td>
      <td><?php echo $donnees["t3_grand"];?></td>    
    </tr>	
	<tr>
  	  <td> sirop </td>
      <td><?php echo $donnees["t3_sirop"];?></td>    
    </tr>
	<tr>
      <th style="font-size:small" rowspan='4'>quatrième trimestre</th>
	  <td> tisane unitaire </td>
      <td><?php echo $donnees["t4_unitaire"];?></td>    
    </tr> 
	<tr>
  	  <td> petite composition </td>
      <td><?php echo $donnees["t4_petit"];?></td>    
    </tr>
	<tr>
  	  <td> grande composition </td>
      <td><?php echo $donnees["t4_grand"];?></td>    
    </tr>	
	<tr>
  	  <td> sirop </td>
      <td><?php echo $donnees["t4_sirop"];?></td>    
    </tr>
	<tr>
		<th class="h3" style="font-size:small;background-color:#FFAAFF" colspan='2'>Prix total</th>
		<td style="background-color:#FFAAFF"><?php echo $donnees["Prix"];?></td>    
    </tr>
	<tr>
		<th   class="h3" colspan='2' style="font-size:small"">date de début du contrat</th>
		<td ><?php echo date("d-M-Y",strtotime($donnees['Date_debut_contrat'])); ?></td>
	</tr>
	<tr>
		<th   class="h3" colspan='2' style="font-size:small">date de fin du contrat</th>
		<td ><?php echo date("d-M-Y",strtotime($donnees['Date_fin_contrat'])); ?></td>
	</tr>
	<tr>
		<th   class="h3" colspan='2' style="font-size:small">date du paiement</th>
		<td ><?php echo date("d-M-Y",strtotime($donnees['Date_paiement'])); ?></td>
	</tr>				
	
	
	<?php	if ( $donnees["Contrat_verrouille"]==1)  { ?>
		<tr><td class="h3" colspan=3 style="color:red">contrat verrouillé</td></tr>
	<?php } ?>
	</table>
	

<?php	
	mysql_close();
}
?>
