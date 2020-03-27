<?php
// Objectif : l'amapien enregistre  ou annule son don de panier pour la date donn�e.
include("webmaster/define.php");

$date=$_GET['date'];      // la date de distribution concern�e 

if (isset($_COOKIE['identification_amap'])) // Si la variable existe
{
  // v�rification des donn�es avant de modifier quoi que ce soit
	mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
	mysql_select_db(base_de_donnees); // S�lection de la base 
	$id=$_COOKIE['identification_amap'];
	$question="SELECT Nom, Prenom FROM amap_legumes WHERE id='".$id."'";
	$reponse= mysql_query($question) or die(mysql_error());

  if ( $donnees = mysql_fetch_array($reponse) )  {
    // la personne est bien inscrite dans la table amap_legumes
  	 $nom_prenom = $donnees['Prenom'].' '.$donnees['Nom'];    
      
    // enregistrement ou annulation ?
    $question="SELECT * FROM amap_legumes_dons WHERE date ='".$date."' and id='".$id."'";
    $reponse= mysql_query($question) or die(mysql_error());
    if ( mysql_fetch_array($reponse) ) {
      // on a d�j� un don pour cette date et cette personne -> annulation
      mysql_query("DELETE FROM amap_legumes_dons WHERE Date='".$date."' And id='".$id."'");   
    } else {
      // on n'a pas encore de don pour cette personne � cete date -> enregistrement
       mysql_query("INSERT into amap_legumes_dons (id, Date, Personne) VALUES( '".$id. "','".$date."','".$nom_prenom."')");
    }
    mysql_close();
		header("Location: donccas.php?amap=".$_GET['amap']);  
  }
  mysql_close();
}   
  

// la personne n'est pas bien identifi�e ou n'est pas inscrite � cette amap !
?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert � indiquer dans quelle langue est r�dig�e votre page -->
	<head>
		<title>AMAP Saint-S�bastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l on utilise des caract�res sp�cifiques au fran�ais ����... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>
	<body onload="document.getElementById('motpasse').focus();">
		<div id="en_tete">
			<?php include("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php include("includes/menu_gauche.php"); ?>
			<h3 class="mot_passe_recette">vous n'�tes pas inscrit au contrat legumes </h3>
		</div>		
		<div id="pied_page">
			<!-- <?php include("includes/pied_page.php") ?> -->
		</div>
	</body>
</html>

