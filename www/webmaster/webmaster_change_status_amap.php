<?php include_once("define.php"); ?>

<?php 
	  // obtenir l'�tat actuel de cet amap
	  $amap  = $_GET['amap'];
	  $table_produit = $amap."_produits";
	 	mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
  	mysql_select_db(base_de_donnees); // S�lection de la base 
    
    $reponse = mysql_query("SELECT Etat_inscription, Table_produit FROM liste_amap WHERE Table_amap='".$amap."'") or die(mysql_error()); // Requ�te SQL
	  $ligne = mysql_num_rows($reponse);
    // pour le moment, tous les produits d'une m�me table sont dans le m�me �tat
  	$donnees = mysql_fetch_array($reponse);
    $table_produit = $donnees['Table_produit'];
    $etat_courant = $donnees['Etat_inscription'];
     
    if ( isset( $_POST['etat'] )) {
      $futur_etat = $_POST["etat"];
      if ( $futur_etat != etat_courant)  {
        //changement de l'�tat en base de donn�e
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
<!-- xml:lang : sert � indiquer dans quelle langue est r�dig�e votre page -->
	<head>
		<title>AMAP Saint-S�bastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="stylesheet" type="text/css" media="all" href="styleW.css" />
		<!-- meta indique que l'on utilise des caract�res sp�cifiques au fran�ais ����... -->
	</head>
	<body>
	

	<!-- changement du champs de la table corrsepondant au type de l'AMAP -->
	<form method="post" action="webmaster_change_status_amap.php?amap=<?php echo $amap; ?>">
		<h2>Etat de l'inscription � <?php echo $amap; ?> </h2>
		<p> Etat actuel : <?php echo $etat_courant; ?></p>	
		<p> Choisissez un nouvel �tat puis valider :
		<select name="etat">
			<option 
			  value="ouvert"  
			  <?php if ($etat_courant == "ouvert" )  echo "selected ='true'";?> >
			  inscription ouverte
			</option>
			<option 
			  value="ferm�" 
			  <?php if ($etat_courant == "ferm�" )  echo "selected ='true'";?> >
			  inscription ferm�e
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
