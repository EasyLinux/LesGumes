<?php
include_once("define.php"); 
include_once("../espace_producteurs/mes_fonctions.php");

$tri = isset( $_GET['classement']) ? $_GET['classement'] : "nom";
$sens = $_GET['sens']=="DESC" ? "DESC" : "ASC";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="../espace_producteurs/style_producteurs.css" />
	</head>
	
	<body>
		<div id="page_principale">
		<p> <strong>&nbsp;&nbsp;Navigation &nbsp;&nbsp;&nbsp;:&nbsp;</strong>
			<input type="button" value="Retour à l'accueil" onclick="document.location.href='../index.php'"/>
			<input type="button" value="Menu Webmaster" onclick="document.location.href='webmaster.php?mode=<?php echo $_GET['amap']; ?>'"/>
		</p>
		
		<!-- composition de la requête SQL ... -->
		<?php 
		$debutRequete = "Select ag.id, concat( ag.nom , ' ',ag.prenom) nom, etat_asso,	paiement, ";
		$firstColonneContrat = 5;
		$milieuRequete = " from amap_generale ag ";
		$finRequete = " order by ".$tri." ".$sens;
		
		mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
		mysql_select_db(base_de_donnees); // Sélection de la base 
		// parcours de chaque table d'amap listée dans la table liste_amap
		$amaps =mysql_query("Select Nom_amap, Nom_court, Table_amap from liste_amap") or die(mysql_error());
		
		// ajout pour chaque amap 
		// pour le début: ifNull(<Table_amap>.contrat_numero,0) <Nom_amap>,
		// pour le milieu : left join <Table_amap> on ag.id = <Table_amap>.id
		$first= true;
		while ($amap=mysql_fetch_array($amaps, MYSQL_ASSOC )) {
			if ( ! $first) { $debutRequete .= ", ";} else { $first=false;}
			$debutRequete .= "ifNull(";
			$debutRequete .= $amap["Table_amap"];
			$debutRequete .= ".contrat_numero,'') '";
			$debutRequete .= $amap["Nom_court"];
			$debutRequete .= "' ";
			
			$milieuRequete .= "left join ";
			$milieuRequete .= $amap["Table_amap"];
			$milieuRequete .= " on ag.id=";
			$milieuRequete .= $amap["Table_amap"];
			$milieuRequete .= ".id ";		
		}
		$superRequete = $debutRequete.$milieuRequete.$finRequete;
		$details =mysql_query($superRequete) or die(mysql_error());	
		?>
		
		<!--Affichage sous forme de tableau -->
		<table>
			<?php 
			$detail=mysql_fetch_array($details, MYSQL_ASSOC); ?>
			<tr>
				<?php // premier parcours pour les entêtes de colonne
				foreach ($detail as  $cle=>$valeur) { 
					// calcul des variables : $tri (colonne du tri)  et sens (Asc ou desc)
					$triCol = $cle;
					if ( $tri !=$cle ) {
						$sensCol="ASC"; } 
					else { //on change le sens du tri
						$sensCol = $sens=="ASC" ? "DESC" : "ASC";
					}
					$ordre = "detailParAmapien.php?classement=".$triCol."&amp;sens=".$sensCol;
					?>				
					<th> <a href="<?php echo $ordre;?>">  <?php echo $cle;?> </a></th>
					<?php } ?>
					<th> binome</th>
					<th> nb contrats</th>
			</tr>
			<?php 
			//on recommence la requete pour les valeurs cette fois ...
			$size = count($detail);
			mysql_free_result($details);
			$details =mysql_query($superRequete) or die(mysql_error());			
			while ($detail=mysql_fetch_array($details)) { ?>	
				<tr>
					<?php 
					$nbContrats =0; 
					if ( (strcmp($detail["etat_asso"], "ACTIF") ==0) && ($detail["paiement"]=="0")) { 
						$color ="bgcolor=yellow";
					} else { $color ="";}
					?>
					<td><?php echo $detail["id"]; ?></td>
					<td <?php echo $color;?>><?php echo substr($detail["nom"],0,22);?></td>
					<td ><?php echo $detail["etat_asso"];?></td>
					<td ><?php echo $detail["paiement"];?></td>
					<?php 
					
					for( $i=$firstColonneContrat; $i<= $size; $i++) {
						$valeur = $detail[$i-1];
						$nbContrats += $valeur; ?>
						<td> <?php echo $valeur;?></td>
					<?php } 
					// traitement spécial pour les binômes ...
					$binomes ="select count(*) nb from binome Where binome.id_binome = ".$detail["id"];
					$binomes = mysql_query($binomes) or die(mysql_error());
					$binome = mysql_fetch_array($binomes); 
					$nbContrats += $binome["nb"];
					mysql_free_result($binomes);?>
					<td> <?php if ($binome["nb"] !=0) {echo $binome["nb"];}?></td>				
					<td > <?php echo $nbContrats; ?></td>
				</tr >
			<?php } ?>
		</table>
		<?php
			$impayés =mysql_query("Select GROUP_CONCAT(e_mail SEPARATOR '; ') mail, COUNT(*) nb from amap_generale WHERE Etat_asso='ACTIF' AND Paiement=0") or die(mysql_error());
			$mails= mysql_fetch_array($impayés);	
			mysql_close(); 
		?>
		<p>Liste des mails des <?php echo $mails['nb'] ?> amapiens ACTIF n'ayant pas payés leur cotisation : <br />
		<?php echo $mails['mail']; ?>
		</p>
		</div>
	</body>
</html>
