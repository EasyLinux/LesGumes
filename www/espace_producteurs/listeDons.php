<?php include_once("../webmaster/define.php"); 
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db(base_de_donnees); // Sélection de la base 
	$reponseDate = mysql_query("SELECT Date FROM amap_legumes_permanences WHERE Distribution='1' AND Date > '2015-07-01' ORDER BY Date ASC") or die(mysql_error());
	$rowdate = mysql_fetch_array ($reponseDate);	
     
	$reponse =  mysql_query("SELECT Count(*) FROM amap_legumes_dons WHERE Date <= CURRENT_DATE and id='".$id."'");
    $row = mysql_fetch_array ($reponse);
    $nbDons = $row[0];	 
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style_producteurs.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>
	<body>
    <div id="page_principale">
		<p> <strong>&nbsp;&nbsp;Navigation &nbsp;&nbsp;&nbsp;:&nbsp;</strong>
			<input type="button" value="Retour à l'accueil" onclick="document.location.href='../index.php'"/>
			<input type="button" value="Page précédente" onclick="javascript:history.back();"/>		
		</p>
	
		<p> <br />Nombre de don déjà réalisé par l'amap depuis le début du contrat :  <?php echo $nbDons;?> </p>
	   <table class="h3">
      <caption> Liste des dons  </caption>
       <tr>
				<th>Date de distribution</th>
        <th>Nombre de paniers</th>
        <th>Nom des contrats</th>				
			</tr>
      <tr>  
				<?php
		$timetoday = time();	
		$done = false;		
		while ($rowdate = mysql_fetch_array ($reponseDate) ) {
			$style = '';
			$date = $rowdate['Date'];
			
			if ( !$done && (strtotime($date) >= $timetoday) ) {
				$style = ' style="background-color: #C1FFC1"'; 
				$done= true;
			}
			$reponseDon = mysql_query("SELECT * FROM amap_legumes_dons WHERE Date = '".$date."'") or die(mysql_error());
			$count=mysql_num_rows($reponseDon);     ?>

			<td <?php echo $style; ?> rowspan=<?php echo $count;?> > <?php echo date("d M y",strtotime($date)); ?> </td>
			<td <?php echo $style; ?> rowspan=<?php echo $count;?>> <?php echo $count; ?> </td>  <?php
       
			if ( $count == 0) {  ?> <td  <?php echo $style; ?> > - </td></tr><tr> <?php  }
			
			while ($row = mysql_fetch_array ($reponseDon) ) {    
			?>
				<td <?php echo $style; ?>> <?php echo $row['Personne']; ?> </td>
				</tr><tr>
         <?php } }  ?>
        	</tr>				
      </table>   
		</div>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</body>
</html>
<?php
mysql_close(); // Déconnexion de MySQL
?>
