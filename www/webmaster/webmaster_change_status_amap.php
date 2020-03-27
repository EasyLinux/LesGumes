<?php include_once("define.php"); ?>

<?php 
	  // obtenir l'état actuel de cet amap
	  $amap  = $_GET['amap'];
	  $table_produit = $amap."_produits";
	 	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
  	mysql_select_db(base_de_donnees); // Sélection de la base 
    
    $reponse = mysql_query("SELECT Etat_inscription, Table_produit FROM liste_amap WHERE Table_amap='".$amap."'") or die(mysql_error()); // Requête SQL
	  $ligne = mysql_num_rows($reponse);
    // pour le moment, tous les produits d'une même table sont dans le même état
  	$donnees = mysql_fetch_array($reponse);
    $table_produit = $donnees['Table_produit'];
    $etat_courant = $donnees['Etat_inscription'];
     
    if ( isset( $_POST['etat'] )) {
      $futur_etat = $_POST["etat"];
      if ( $futur_etat != etat_courant)  {
        //changement de l'état en base de donnée
        $question="UPDATE liste_amap SET Etat_inscription ='".$futur_etat."' WHERE Table_amap='".$amap."'";
        $reponse = mysql_query($question) or die(mysql_error());
        $etat_courant = $futur_etat;
      }   
   }
  
   mysql_close();
	 
?>
	
	

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" media="all" href="styleW.css" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
	</head>
	<body>
	

	<!-- changement du champs de la table corrsepondant au type de l'AMAP -->
	<form method="post" action="webmaster_change_status_amap.php?amap=<?php echo $amap; ?>">
		<h2>Etat de l'inscription à <?php echo $amap; ?> </h2>
		<p> Etat actuel : <?php echo $etat_courant; ?></p>	
		<p> Choisissez un nouvel état puis valider :
		<select name="etat">
			<option 
			  value="ouvert"  
			  <?php if ($etat_courant == "ouvert" )  echo "selected ='true'";?> >
			  inscription ouverte
			</option>
			<option 
			  value="fermé" 
			  <?php if ($etat_courant == "fermé" )  echo "selected ='true'";?> >
			  inscription fermée
			</option>
			<?php if ( $amap=="amap_legumes") { ?>
			<option 
			  value="attente"
			  <?php if ($etat_courant == "attente" )  echo "selected ='true'";?> >
			  inscription sur liste d&#39;attente
			</option>
			<?php } ?>
	  </select>
	  
      <input type="submit"/>
	  </p>
      <input type="button" value="Revenir au menu webmaster" onclick="document.location.href='webmaster.php?mode=<?php echo $amap; ?>'" />
	</form>
	</body>
</html>
