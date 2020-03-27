<?php include_once("define.php"); 


function ajouterAmap($type, $typeAmap) { 
	$nom = substr( $type, 5, strlen($type)); ?>
	<th><?php echo $nom;?></th>
	<td> <ul>	<?php
	
	if ( $typeAmap== $type ) { ?>
		<li><a href="webmaster_infos.php?nom_amap=<?php echo $type;?>">Mise à jour des inscriptions</a></li>
		<li><a href="webmaster_change_status_amap.php?amap=<?php echo $type;?>">Changer état des incriptions</a></li>
		<li><a href="webmaster_maj_permanence.php?amap=<?php echo $type;?>">Modifier les dates de permanence</a></li>
	<?php } ?>

	<!-- items communs à tous -->
	<li><a href="webmaster_contrat_amap.php?amap=<?php echo $type;?>&amp;ordre=rien&amp;classement=id">Voir inscriptions</a></li>
	<li><a href="webmaster_mail_to.php?amap=<?php echo $type;?>&amp;modewebmaster=true">Ecrire aux adhérents</a></li>
	
	<?php // items spécifiques
	switch ($type) {
	case "amap_legumes" : ?>	
		<li><a href="webmaster_contrat_amap.php?amap=amap_legumes_liste_attente&amp;ordre=rien&amp;classement=id">Mise à jour de la liste attente</a></li>
		<li><a href="webmaster_mail_to.php?amap=amap_legumes_liste_attente">Ecrire à la liste d'attente</a></li>
	 <?php ; break;
	case "amap_tisanes" : ?>	
		<li><a href="webmaster_commandes_tisanes.php">Administrer les commandes</a></li>	 
	<?php ; break;
	}
	?>
								
	</ul> </td>	
<?php }

$ok=0;
$type_amap = "";
$mot_test='';

if (isset($_POST['motpasse'])) 
    $mot_test=$_POST['motpasse'];
else if(isset($_GET['mode'])) {
    $type_amap=$_GET['mode'];
	if ( $type_amap='amap_legumes_liste_attente') 
	// en attendant de corriger le pb de contexte : le référent est perdu après action sur une autre amap ...
		$type_amap='amap_legumes';	
	
    $ok=2;
}


if($mot_test=="180958")	$ok=1; //mode general uniquement en consultation
else if ($mot_test=="&lait&")	{ $ok=2; $type_amap="amap_produits_laitiers" ;}
else if ($mot_test=="&legumes&")	{ $ok=2; $type_amap="amap_legumes" ;    }
else if ($mot_test=="&pain&")	{ $ok=2; $type_amap="amap_pain" ;    }
else if ($mot_test=="&oeufs&")	{ $ok=2; $type_amap="amap_oeufs" ;    }
else if ($mot_test=="&pommes&")	{ $ok=2; $type_amap="amap_pommes" ;    }     
else if ($mot_test=="&agrumes&") { $ok=2; $type_amap="amap_agrumes" ; }
else if ($mot_test=="&chevres&")	{ $ok=2; $type_amap="amap_chevre" ;    }
else if ($mot_test=="&champignons&")	{ $ok=2; $type_amap="amap_champignons" ;    }
else if ($mot_test=="&pates&")	{ $ok=2; $type_amap="amap_pates" ;    }
else if ($mot_test=="&tisanes&")	{ $ok=2; $type_amap="amap_tisanes" ;    }
else if ($mot_test=="&miel&")	{ $ok=2; $type_amap="amap_miel" ;    }
else if ($mot_test=="&poissons&")	{ $ok=2; $type_amap="amap_poissons" ;    }
else if ($mot_test=="&kiwis&")	{ $ok=2; $type_amap="amap_kiwis" ;    }
else if ($mot_test=="&pates&")	{ $ok=2; $type_amap="amap_pates" ;    }
else if ($mot_test=="&tommes&")	{ $ok=2; $type_amap="amap_tommes" ;    }
else if ($mot_test=="&biere&")	{ $ok=2; $type_amap="amap_bieres" ;    } 


$listeAmap = array("amap_agrumes","amap_champignons","amap_chevre",
	"amap_legumes", "amap_oeufs", "amap_pain", "amap_pommes","amap_poissons",
	"amap_produits_laitiers", "amap_tisanes", "amap_miel", "amap_pates", "amap_poissons",
	"amap_kiwis", "amap_tommes", "amap_bieres");
	
if($ok==1 || $ok==2)

// Si le mot de passe est bon
{
//***********************************************************************
// On a le droit
//***********************************************************************
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="stylesheet" type="text/css" media="all" href="styleW.css" />
			
	</head>
	<body>
	<h2> Administration de la base de données et des contrats</h2>
	<p> <strong>&nbsp;&nbsp;Navigation &nbsp;&nbsp;&nbsp;:&nbsp;</strong>
			<input type="button" value="Retour à l'accueil" onclick="document.location.href='../index.php'"/>
	</p>
	<p> 
	<?php if ($ok==2) { ?> accès en mise à jour pour le référent de l'<?php echo $type_amap; ?> : <?php }	?>
	<?php if ($ok==1) { ?> accès général en consultation uniquement : <?php } ?>
	</p>
	
		
	<table>
		<!--  en premier le menu général puis celui de l'amap correspondant au mot de passe -->
		<tr> 
			<th>générale</th>
			<td> <ul>
				<li><a href="liste_news.php?amap=<?php echo $type_amap;?>">Gestion des news</a></li>
				<li><a href="webmaster_amap_generale.php?amap=<?php echo $type_amap;?>&amp;ordre=rien&amp;classement=Nom">Voir table des amapiens</a></li>
				<li><a href="detailParAmapien.php?amap=<?php echo $type_amap;?>&amp;classement=Nom">Détails des contrats par amapien</a></li>
				<li><a href="mailto:amap-lesgumes@googlegroups.com">Ecrire sur la liste de diffusion amap</a></li>
				<li><a href="mailto:inforumamapsaintseb@googlegroups.com">Ecrire sur la liste de diffusion inforum</a></li>
				<li><a href="webmaster_gestion_login.php">Gestion des logins et mot de passe</a></li>
				<li><a href="webmaster_nouveau_amapien.php">Ajouter un amapien dans le site</a></li>
			</ul> </td>	<?php
			if ($ok== 2) ajouterAmap($type_amap, $type_amap); ?>
		</tr>
	</table>
	<p>	autres amaps en consultation uniquement :</p>
	<table><tr>
		<?php $i = 0;	// les autres amaps - 3 par lignes
			foreach( $listeAmap as $value) {
				if ( $value == $type_amap) continue;
				if ( $i ==0) {
					// ajouter un tr
					?> </tr><tr> <?php
				}
				ajouterAmap($value, $type_amap); 
				$i==2 ? $i=0 : $i++;
			}			
			if ( $i==0) ?> </tr> 
		</table>
	</body>
	<P>
	(version php du serveur : <?php echo phpversion(); ?> )
	</p>
</html>
<?php
} 
else // le mot de passe n'est pas bon
{
//***********************************************************************
// On affiche la zone de texte pour rentrer de nouveau le mot de passe.
//***********************************************************************
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
		<link rel="stylesheet" type="text/css" media="all" href="styleW.css" />

<script type="text/javascript">
<!--
function verifkey(boite) {
	if(boite.value=='') {
		alert('Entrer un mot de passe!');
		boite.focus();
		return false;
	}
	return true;
}
-->
</script>
	
	</head>
	<body onload="document.getElementById('motpasse').focus();">
		<form onsubmit="return verifkey(this.motpasse);" method="post" action="webmaster.php" >
			<p>
			</p>
			<p>
				saisir le mot de passe
				<input type="password" name="motpasse" id="motpasse" size="30" maxlength="30" tabindex="10"/>
				<input type="submit" tabindex="20"/> <input type="reset" tabindex="30"/>
			</p>
		</form>
	</body>
</html>
<?php } ?>