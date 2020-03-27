<?php include_once("webmaster/define.php"); 
	  include_once("includes/fileTools.php");
	  
	function addFile( $directory, $extension) {
			
		$files = getFilesFrom( $directory, '.pdf');
		foreach ( $files as $file) {
			$tmp = $directory.$file; ?>
			<a class="texte" href="<?php echo $tmp; ?>"><?php echo $file; ?></a><br />
		<?php } 
	}
					
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
			<?php include_once("includes/menu_gauche.php") ?>
			<table>
			<tr ><td>
			<h2 class="texte">Visites chez les producteurs :</h2>
				<?php addFile('documentation/compte_rendu_prod/', '.pdf'); ?>		
			<h2 class="texte">Documents officiels :</h2>
				<?php addFile('documentation/association/', '.pdf'); ?>	
			<h2 class="texte">Réunions du conseil d'administration :</h2>
				<?php addFile('documentation/association/compte_rendu/', '.pdf'); ?>	
			<h2 class="texte">Affichettes de pub :</h2>
				<a class="texte" href="documentation/affichettes/afficheAMAP.pdf">affichette 2015</a><br />
				<a class="texte" href="documentation/affichettes/flyeramap16.pdf">affichette 2016</a><br />
				<a class="texte" href="documentation/affichettes/A4flyersamap16.pdf">flyers 2016</a><br />	<br />	
			</td>
			<td>
			<h2 class="texte">Archives :</h2>
			 	<?php addFile('documentation/archives/', '.pdf'); ?>
						
			</td></tr></table>
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