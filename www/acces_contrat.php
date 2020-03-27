<?php 
include_once("webmaster/define.php");
include_once("acces_contrat_fonctions.php");

define ("ETAT_NON_DEFINI", 0);
define ("ETAT_IDENTIFIE", 1);
define ("ETAT_INSCRIT", 2);
define ("ETAT_LISTE_ATTENTE", 3);

           
$etat=ETAT_NON_DEFINI;		
if (isset($_COOKIE['identification_amap']))  { 
	$etat=ETAT_IDENTIFIE;

	// vérification que l'amapien est dans inscrit à ce contrat*/
	mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
	mysql_select_db(base_de_donnees); // Sélection de la base 
	$id=$_COOKIE['identification_amap'];
	if ( InscritAuContrat( base_de_donnees, $_GET['amap'], $id)) {
		$etat=ETAT_INSCRIT; 
	} else { 
		$idContrat = getIdBinome( base_de_donnees, $_GET['amap'], $id);
		if ( $idContrat != -1 ) {
			$id = $idContrat;
			$etat=ETAT_INSCRIT; 
		}  elseif  ($_GET['amap']=='amap_legumes') {
			$question="SELECT * FROM amap_legumes_liste_attente WHERE id='".$id."'";
			$reponse = mysql_query($question) or die(mysql_error());
			$ligne = mysql_num_rows($reponse);
			if ( $ligne > 0) {
				$etat=ETAT_LISTE_ATTENTE;
			}
		}
	}
	mysql_close();
}

if ($etat==ETAT_INSCRIT) {
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>
	<body>
		<div id="en_tete">
			<?php include_once("includes/en_tete.php") ?>
		</div>
		<div id="bandeau">
			<?php include_once("includes/bandeau.php") ?>
		</div>
		<div id="page_principale">
			<?php
			
			include_once("includes/menu_gauche.php");
          
			if ($_GET['amap']=='amap_champignons' ) { 
				VoirContratV(base_de_donnees, $_GET['amap'], $id );
			}
			elseif ($_GET['amap']=='amap_tisanes' ) { 
				include ("acces_contrat_tisane.php");
				VoirContratTisane( $id );
			}
			elseif ($_GET['amap']=='amap_oeufs' ) { 
				include ("acces_contrat_oeufs.php");
				VoirContratOeufs( $id );
			}
			elseif ($_GET['amap']=='amap_chevre' ) { 
				include ("acces_contrat_chevre.php");
				VoirContratChevre( $id );
			}
			else {
				VoirContratV(base_de_donnees, $_GET['amap'], $id);		
			} ?>
		</div>	
	
		<div id="pied_page">
			<!-- <?php include_once("includes/pied_page.php") ?> -->
		</div>
	<p>
		<!--<img src="images/logo_lesgumes.jpeg" alt="Logo de l'AMAP" title="Groupement Uni pour un Meilleur Environnement Solidaire" /> -->
		<!-- alt indique un texte alternatif au cas où l'image ne peut pas être téléchargée -->
	</p>
	</body>
</html>

<?php
}
if( $etat== ETAT_LISTE_ATTENTE) { 
	include_once( "msg_liste_attente.php");
}	
if ( $etat== ETAT_IDENTIFIE)  {
	include_once( "msg_non_inscrit.php");
}
if($etat== ETAT_NON_DEFINI) { 
 include_once( "msg_identification.php");
}
?>
