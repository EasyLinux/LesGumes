<?php include_once("define.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert à indiquer dans quelle langue est rédigée votre page -->
	<head>
		<title>AMAP Saint-Sébastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<!-- meta indique que l'on utilise des caractères spécifiques au français éèêà... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style.css" />
	</head>
	<body>
		<p style="text-align:center; color:red"><?php echo $_GET['table'];?> : <?php echo $_GET['action']; ?></p>
<!--***********************************************************************************************************-->
<!-- début fonction tester formulaire -->	
<!--***********************************************************************************************************-->
<script type="text/javascript">
<!--
function tester(formulaire)
{
	var mot="";
	var regExpBeginning = /^\s+/;
	var regExpEnd = /\s+$/;  
	var regExTel = /^(0[1-689])(?:[ _.-]?(\d{2})){4}$/;
	var regExNom = /^([A-Z]{1,})(?:[' _-]?([A-Z]{1,})){0,4}$/;
	var regExPrenom = /^([A-Z][a-zéèçàêîôûïëöüù]{0,})(?:[' _-]?([A-Z]?([a-zéèçàêîôûïëöüù]{1,}))){0,4}$/;
	var regExMail = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-z]{2,4}$/;
	var regExDate = /^20\d\d-\d\d-\d\d\s\d\d:\d\d:\d\d$/;
	var regExAdresse = /^[a-zA-Z0-9]{1}[a-zA-Z0-9_ '-]+[a-zA-Z]{1}$/;
	var i=0,j;

	mot=document.getElementsByTagName('input')[1].value;
	mot=mot.replace(regExpBeginning, "").replace(regExpEnd, "");//supprime les espaces de début et de fin
	mot=mot.toUpperCase();
	document.getElementsByTagName('input')[1].value=mot;
	if(!mot.match(regExNom)) {alert('Nom incorrect');return false;}

	mot=document.getElementsByTagName('input')[2].value;
	mot=mot.replace(regExpBeginning, "").replace(regExpEnd, "");//supprime les espaces de début et de fin
	mot=mot.charAt(0).toUpperCase()+mot.substr(1);
	document.getElementsByTagName('input')[2].value=mot;
	if(!mot.match(regExPrenom)) {alert('Prénom incorrect');return false;}
	
	mot=document.getElementsByTagName('input')[3].value;
	mot=mot.replace(regExpBeginning, "").replace(regExpEnd, "");//supprime les espaces de début et de fin
	document.getElementsByTagName('input')[3].value=mot;
	if(!mot.match(regExMail)) {alert('e_mail incorrect');return false;}
	
	mot=document.getElementsByTagName('input')[4].value;
	mot=mot.replace(regExpBeginning, "").replace(regExpEnd, "");
	document.getElementsByTagName('input')[4].value=mot;
	if(mot != ""){if(!mot.match(regExAdresse)) {alert('Adresse incorrecte');return false;}}

	mot=document.getElementsByTagName('input')[5].value;
	mot=mot.replace(regExpBeginning, "").replace(regExpEnd, "");//supprime les espaces de début et de fin
	//marche aussi if (!regExTel.test(mot)) {alert('tel incorrect');return test;}marche aussi
	document.getElementsByTagName('input')[5].value=mot;
	if(mot != ""){if(!mot.match(regExTel)) {alert('Tel incorrect');return false;}}

	mot=document.getElementsByTagName('input')[6].value;
	mot=mot.replace(regExpBeginning, "").replace(regExpEnd, "");//supprime les espaces de début et de fin
	document.getElementsByTagName('input')[6].value=mot;
	if(mot != ""){if(!mot.match(regExTel)) {alert('n° tel-2 incorrect');return false;}}
	
	mot=document.getElementsByTagName('input')[7].value;
	mot=mot.replace(regExpBeginning, "").replace(regExpEnd, "");//supprime les espaces de début et de fin
	document.getElementsByTagName('input')[7].value=mot;
	if(!mot.match(regExDate)) {alert('Date incorrecte');return false;}

	for(i=8;i<13;i++) {
		j=parseInt(document.getElementsByTagName('input')[i].value);// voir aussi parseInt()
		if(j!=1 && j!=0) {alert('flag incorrect 0 ou 1');return false;}
	}
	return true;
}
-->
</script>	
	
	<?php
/*******************************************************************************************************************/
/* ajouter enregistrement generale
/*******************************************************************************************************************/
		if($_GET['table']=='amap_generale' && $_GET['action']=='ajouter') {
		if($_POST['amap_legumes']=='1' && $_POST['liste_attente_leg']=='1') echo "incompatibilté entre les drapeaux amap_legumes et amap_legumes_liste_attente!!!!!!!!!!!!!!";	
		else {
			echo "connexion = ".mysqli_connect(hote, login, mot_passe_sql)."<br />"; // Connexion à MySQL
			if(mysqli_select_db(base_de_donnees)) echo "selection-base = true<br /><br />"; else echo "selection-base = false<br />";
			$texte_1=addslashes($_POST['nom']);
			$texte_2=addslashes($_POST['prenom']);
			$question = "SELECT COUNT(*) AS nbre FROM amap_generale WHERE Nom='".$texte_1."' AND Prenom='".$texte_2."'";
			$reponse=mysqli_query($question);
			$donnees=mysqli_fetch_array($reponse);
			if($donnees['nbre']!=0) {
				echo "Il y a déjà un enregistrement avec le même [Nom, Prénom]!!!!!!<br />";
			}
			else {
				$question="SELECT COUNT(*) AS nbre FROM amap_generale WHERE e_mail='".$_POST['e_mail']."'";
				$reponse=mysqli_query($question);
				$donnees=mysqli_fetch_array($reponse);
				if($donnees['nbre']!=0) {
					echo "Il y a déjà un enregistrement avec cette e_mail!!!!!!<br />";
				}
				else {
					$question="INSERT INTO amap_generale(id, Nom, Prenom, e_mail, Telephone, Date_inscription) ";
					$question=$question."VALUES('', '".$texte_1."', '".$texte_2."', '".$_POST['e_mail']."', '".$_POST['tel']."', '".$_POST['date']."')";
					mysqli_query($question);
					echo "<br />requete-1 = ".$question."<br /><br />";
					
					$question="SELECT id FROM amap_generale WHERE e_mail='".$_POST['e_mail']."'";
					$reponse=mysqli_query($question);
					$donnees=mysqli_fetch_array($reponse);
					if($_POST['adresse']!='') {
						$question="UPDATE amap_generale SET Adresse='".$_POST['adresse']."' WHERE id='".$donnees['id']."'";
						mysqli_query($question);
						echo "requete-adresse = ".$question."<br /><br />";
					}
					if($_POST['portable']!='') {
						$question="UPDATE amap_generale SET Tel_portable='".$_POST['portable']."' WHERE id='".$donnees['id']."'";
						mysqli_query($question);
						echo "requete-portable = ".$question."<br /><br />";
					}
					$question="UPDATE amap_generale SET Paiement_centre='".$_POST['pay_centre']."', Amap_legumes='".$_POST['amap_legumes']."', ";
					$question=$question."Amap_legumes_liste_attente='".$_POST['liste_attente_leg']."', Amap_pommes='".$_POST['amap_pommes']."', Amap_viande_bovine='".$_POST['amap_viande_bovine']."' ";
					$question=$question."WHERE id='".$donnees['id']."'";
					mysqli_query($question);
					echo "requete-drapeaux 0/1 = ".$question."<br />";

					if($_POST['amap_legumes']=='1') {
						$question="INSERT INTO amap_legumes(id, Nom, Prenom) VALUES('".$donnees['id']."', '".$texte_1."', '".$texte_2."')";
						mysqli_query($question);
						echo "<br />".$_POST['prenom']." ".$_POST['nom']." est enregistré à l'amap légumes<br />";
					}
					if($_POST['amap_pommes']=='1') {
						$question="INSERT INTO amap_pommes(id, Nom, Prenom) VALUES('".$donnees['id']."','".$texte_1."', '".$texte_2."')";
						mysqli_query($question);
						echo "<br />".$_POST['prenom']." ".$_POST['nom']." est enregistré à l'amap pommes<br />";
					}
					if($_POST['amap_viande_bovine']=='1') {
						$question="INSERT INTO amap_viande_bovine(id, Nom, Prenom) VALUES('".$donnees['id']."','".$texte_1."', '".$texte_2."')";
						mysqli_query($question);
						echo "<br />".$_POST['prenom']." ".$_POST['nom']." est enregistré à l'amap viande bovine<br />";
					}
					
					echo "<br /><br />paramètres enregistrés<br /><br />";
					echo "Nom : ".$_POST['nom']."<br />";
					echo "Prénom : ".$_POST['prenom']."<br />";
					echo "e_mail : ".$_POST['e_mail']."<br />";
					echo "Adresse : ".$_POST['adresse']."<br />";
					echo "Téléphone : ".$_POST['tel']."<br />";
					echo "Tel-2 : ".$_POST['portable']."<br />";
					echo "Date inscription : ".$_POST['date']."<br />";
					echo "Paiement centre : ".$_POST['pay_centre']."<br />";
					echo "inscription amap_legumes : ".$_POST['amap_legumes']."<br />";
					echo "inscription amap_legumes_liste_attente : ".$_POST['liste_attente_leg']."<br />";
					echo "inscription amap_pommes : ".$_POST['amap_pommes']."<br />";
					echo "inscription amap_viande_bovine : ".$_POST['amap_viande_bovine']."<br />";
				}
			}
			mysqli_close();
		}
		}
/*******************************************************************************************************************/
/* supprimer enregistrement generale
/*******************************************************************************************************************/
		if($_GET['table']=='amap_generale' && $_GET['action']=='supprimer') {
			echo "connexion = ".mysqli_connect(hote, login, mot_passe_sql)."<br />"; // Connexion à MySQL
			if(mysqli_select_db(base_de_donnees)) echo "selection-base = true<br /><br />"; else echo "selection-base = false<br />";
			
			$question="SELECT * FROM amap_generale WHERE id='".$_POST['id']."'";
			$reponse=mysqli_query($question);
			$nbre_eng=mysqli_num_rows($reponse);
			if($nbre_eng==0) echo "Pas d'adhérent avec l'id : ".$_POST['id'];
			else {
				$donnees=mysqli_fetch_array($reponse);
				$nom_prenom=$donnees['Prenom']." ".$donnees['Nom'];
				
				$question="SELECT * FROM amap_legumes_permanences WHERE Personne_1='".$nom_prenom."' OR Personne_2='".$nom_prenom."' OR Personne_3='".$nom_prenom."'";
				$reponse=mysqli_query($question);
				$nbre_eng=mysqli_num_rows($reponse);
				if($nbre_eng!=0) {
					$question="UPDATE amap_legumes_permanences SET Personne_1='?', id_1='0' WHERE Personne_1='".$nom_prenom."'";
					mysqli_query($question);
					$question="UPDATE amap_legumes_permanences SET Personne_2='?', id_2='0' WHERE Personne_2='".$nom_prenom."'";
					mysqli_query($question);
					$question="UPDATE amap_legumes_permanences SET Personne_3='?', id_3='0' WHERE Personne_3='".$nom_prenom."'";
					mysqli_query($question);
					echo "<br />".$nom_prenom." supprimer du tableau permanences légumes<br /><br />";
				}

				$question="DELETE FROM amap_generale WHERE id='".$_POST['id']."'";
				if(mysqli_query($question)) echo $nom_prenom." supprimer du fichier général<br /><br />";
				
				$question="SELECT * FROM amap_legumes WHERE id='".$_POST['id']."'";
				$reponse=mysqli_query($question);
				$nbre_eng=mysqli_num_rows($reponse);
				if($nbre_eng!=0) {
					$question="DELETE FROM amap_legumes WHERE id='".$_POST['id']."'";
					mysqli_query($question);
					echo $nom_prenom." supprimer de l'amap légumes<br /><br />";
				}
				$question="SELECT * FROM amap_pommes WHERE id='".$_POST['id']."'";
				$reponse=mysqli_query($question);
				$nbre_eng=mysqli_num_rows($reponse);
				if($nbre_eng!=0) {
					$question="DELETE FROM amap_pommes WHERE id='".$_POST['id']."'";
					mysqli_query($question);
					echo $nom_prenom." supprimer de l'amap pommes<br /><br />";
				}
				$question="SELECT * FROM amap_viande_bovine WHERE id='".$_POST['id']."'";
				$reponse=mysqli_query($question);
				$nbre_eng=mysqli_num_rows($reponse);
				if($nbre_eng!=0) {
					$question="DELETE FROM amap_viande_bovine WHERE id='".$_POST['id']."'";
					mysqli_query($question);
					echo $nom_prenom." supprimer de l'amap viande bovine<br /><br />";
				}
			}
			mysqli_close();
		}
/*******************************************************************************************************************/
/* modifier enregistrement sans maj table generale
/*******************************************************************************************************************/
		if($_GET['table']=='amap_generale' && $_GET['action']=='modifier') {
			echo "connexion = ".mysqli_connect(hote, login, mot_passe_sql)."<br />"; // Connexion à MySQL
			if(mysqli_select_db(base_de_donnees)) echo "selection-base = true<br /><br />"; else echo "selection-base = false<br />";
			
			$question="SELECT * FROM amap_generale WHERE id='".$_POST['id']."'";
			$reponse=mysqli_query($question);
			$nbre_eng=mysqli_num_rows($reponse);
			if($nbre_eng==0) { echo "Pas d'adhérent avec l'id : ".$_POST['id'];}
			else {
				$donnees=mysqli_fetch_array($reponse);
				?>
				<h4> Adhérent : <?php echo $donnees['id']; ?> (* = champ obligatoire)</h4>
				<form name="form_ajouter" onsubmit="return tester(this);"  method="post" action="webmaster_maj_tables.php?table=amap_generale&amp;action=maj" >
				<p>
					<label for="id">id = </label>
					<input type="text" style="background:#EEEEEE" size="3" readonly name="id" id="id" value="<?php echo $donnees['id']; ?>"/> 
					<label for="nom">Nom<sup>*</sup> = </label>
					<input type="text" style="text-transform:uppercase" name="nom" id="nom" value="<?php echo $donnees['Nom']; ?>"/>     <!-- style="text-transform:uppercase"-->
					<label for="prenom">Prénom<sup>*</sup> = </label>
					<input type="text" name="prenom" id="prenom" value="<?php echo $donnees['Prenom']; ?>"/>   <!-- style="text-transform:capitalize"-->
					<label for="e_mail">e_mail<sup>*</sup> = </label>
					<input type="text" name="e_mail" id="e_mail" value="<?php echo $donnees['e_mail']; ?>"/>
					<label for="adresse">Adresse = </label>
					<input type="text" size="50" name="adresse" id="adresse" value="<?php echo $donnees['Adresse']; ?>"/><br /><br />
					
					<label for="tel">Tel = </label>
					<input type="text" size="15" maxlength="14" name="tel" id="tel" value="<?php echo $donnees['Telephone']; ?>"/>
					<label for="portable">Tel-2 = </label>
					<input type="text" size="15" maxlength="14" name="portable" id="portable" value="<?php echo $donnees['Tel_portable']; ?>"/>
					<label for="date">Date_inscription aaaa-mm-jj hh:mm:ss<sup>*</sup> = </label>
					<input type="text" size="20" name="date" id="date" value="<?php echo $donnees['Date_inscription']; ?>"/><br /><br />
					
					<label for="pay_centre">Pay_centre = </label>
					<input type="text" size="1" maxlength="1" name="pay_centre" id="pay_centre" value="<?php echo $donnees['Paiement_centre']; ?>"/>
					<label for="amap_legumes">Amap légumes = </label>
					<input type="text" size="1" maxlength="1" name="amap_legumes" id="amap_legumes" value="<?php echo $donnees['Amap_legumes']; ?>"/>
					<label for="liste_attente_leg">Liste attente légumes = </label>
					<input type="text" size="1" maxlength="1" name="liste_attente_leg" id="liste_attente_leg" value="<?php echo $donnees['Amap_legumes_liste_attente']; ?>"/>
					<label for="amap_pommes">Amap pommes = </label>
					<input type="text" size="1" maxlength="1" name="amap_pommes" id="amap_pommes" value="<?php echo $donnees['Amap_pommes']; ?>"/>
					<label for="amap_viande_bovine">Amap viande bovine = </label>
					<input type="text" size="1" maxlength="1" name="amap_viande_bovine" id="amap_viande_bovine" value="<?php echo $donnees['Amap_viande_bovine']; ?>"/>

					<input type="submit" value="Modifier la table"/> <!-- onclick="javascript:tester(this.form);"-->
				</p>
				</form>
			<?php
			}
			mysqli_close();
		}
/*******************************************************************************************************************/
/* maj table generale
/*******************************************************************************************************************/
		if($_GET['table']=='amap_generale' && $_GET['action']=='maj') {
		if($_POST['amap_legumes']=='1' && $_POST['liste_attente_leg']=='1') echo "incompatibilté entre les drapeaux amap_legumes et amap_legumes_liste_attente!!!!!!!!!!!!!!";	
		else {
			echo "connexion = ".mysqli_connect(hote, login, mot_passe_sql)."<br />"; // Connexion à MySQL
			if(mysqli_select_db(base_de_donnees)) echo "selection-base = true<br /><br />"; else echo "selection-base = false<br />";
			$texte_1=addslashes($_POST['nom']);
			$texte_2=addslashes($_POST['prenom']);
			$question = "SELECT COUNT(*) AS nbre FROM amap_generale WHERE Nom='".$texte_1."' AND Prenom='".$texte_2."' AND id!='".$_POST['id']."'";
			$reponse=mysqli_query($question);
			$donnees=mysqli_fetch_array($reponse);
			if($donnees['nbre']!=0) {
				echo "Il y a déjà un autre enregistrement avec le même [Nom, Prénom]!!!!!!<br />";
			}
			else {
				$question="SELECT COUNT(*) AS nbre FROM amap_generale WHERE e_mail='".$_POST['e_mail']."' AND id!='".$_POST['id']."'";
				$reponse=mysqli_query($question);
				$donnees=mysqli_fetch_array($reponse);
				if($donnees['nbre']!=0) {
					echo "Il y a déjà un autre enregistrement avec cette e_mail!!!!!!<br />";
				}
				else {
		/*maj table amap_legumes et permanences legumes*/
					$question="SELECT * FROM amap_generale WHERE id='".$_POST['id']."'";
					$reponse=mysqli_query($question);
					$donnees=mysqli_fetch_array($reponse);
					$anc_nom_prenom=addslashes($donnees['Prenom'])." ".addslashes($donnees['Nom']);
					$nouv_nom_prenom=$texte_2.' '.$texte_1;
					if($donnees['Amap_legumes']=='1' && $_POST['amap_legumes']=='1') {
						$question="UPDATE amap_legumes SET Nom='".$texte_1."', Prenom='".$texte_2."' WHERE id='".$_POST['id']."'";
						mysqli_query($question);
						echo "maj table amap légumes = ".$question."<br /><br />";
						$question="UPDATE amap_legumes_permanences SET Personne_1='".$nouv_nom_prenom."' WHERE Personne_1='".$anc_nom_prenom."'";
						mysqli_query($question);
						$question="UPDATE amap_legumes_permanences SET Personne_2='".$nouv_nom_prenom."' WHERE Personne_2='".$anc_nom_prenom."'";
						mysqli_query($question);
						$question="UPDATE amap_legumes_permanences SET Personne_3='".$nouv_nom_prenom."' WHERE Personne_3='".$anc_nom_prenom."'";
						mysqli_query($question);
					}
					elseif ($donnees['Amap_legumes']=='0' && $_POST['amap_legumes']=='1') {
						$question="INSERT INTO amap_legumes(id, Nom, Prenom) VALUES('".$_POST['id']."', '".$texte_1."','".$texte_2."')";
						mysqli_query($question);
						echo "ajout à la table amap légumes = ".$question."<br /><br />";
					}
					elseif($donnees['Amap_legumes']=='1' && $_POST['amap_legumes']=='0') {
						$question="DELETE FROM amap_legumes WHERE id='".$_POST['id']."'";
						mysqli_query($question);
						echo "suppression de la table amap légumes = ".$question."<br /><br />";
						$question="UPDATE amap_legumes_permanences SET Personne_1='?', id_1='0' WHERE Personne_1='".$anc_nom_prenom."'";
						mysqli_query($question);
						$question="UPDATE amap_legumes_permanences SET Personne_2='?', id_2='0' WHERE Personne_2='".$anc_nom_prenom."'";
						mysqli_query($question);
						$question="UPDATE amap_legumes_permanences SET Personne_3='?', id_3='0' WHERE Personne_3='".$anc_nom_prenom."'";
						mysqli_query($question);
					}

		/*maj table amap_pommes */
					if($donnees['Amap_pommes']=='1' && $_POST['amap_pommes']=='1') {
						$question="UPDATE amap_pommes SET Nom='".$texte_1."', Prenom='".$texte_2."' WHERE id='".$_POST['id']."'";
						mysqli_query($question);
						echo "maj table amap pommes = ".$question."<br /><br />";
					}
					elseif ($donnees['Amap_pommes']=='0' && $_POST['amap_pommes']=='1') {
						$question="INSERT INTO amap_pommes(id, Nom, Prenom) VALUES('".$_POST['id']."', '".$texte_1."','".$texte_2."')";
						mysqli_query($question);
						echo "ajout à la table amap pommes = ".$question."<br /><br />";
					}
					elseif($donnees['Amap_pommes']=='1' && $_POST['amap_pommes']=='0') {
						$question="DELETE FROM amap_pommes WHERE id='".$_POST['id']."'";
						mysqli_query($question);
						echo "suppression de la table amap pommes = ".$question."<br /><br />";
					}
		/*maj table amap_viande_bovine */
					if($donnees['Amap_viande_bovine']=='1' && $_POST['amap_viande_bovine']=='1') {
						$question="UPDATE amap_viande_bovine SET Nom='".$texte_1."', Prenom='".$texte_2."' WHERE id='".$_POST['id']."'";
						mysqli_query($question);
						echo "maj table amap viande_bovine = ".$question."<br /><br />";
					}
					elseif ($donnees['Amap_viande_bovine']=='0' && $_POST['amap_viande_bovine']=='1') {
						$question="INSERT INTO amap_viande_bovine(id, Nom, Prenom) VALUES('".$_POST['id']."', '".$texte_1."','".$texte_2."')";
						mysqli_query($question);
						echo "ajout à la table amap viande_bovine = ".$question."<br /><br />";
					}
					elseif($donnees['Amap_viande_bovine']=='1' && $_POST['amap_viande_bovine']=='0') {
						$question="DELETE FROM amap_viande_bovine WHERE id='".$_POST['id']."'";
						mysqli_query($question);
						echo "suppression de la table amap viande_bovine = ".$question."<br /><br />";
					}
					
					
					
			/* maj table amap_generale */
					$question="UPDATE amap_generale SET Nom='".$texte_1."', Prenom='".$texte_2."', e_mail='".$_POST['e_mail']."', Date_inscription='".$_POST['date']."' WHERE id='".$_POST['id']."'";
					mysqli_query($question);
					echo "<br />requete-1 = ".$question."<br /><br />";
					
					if($_POST['adresse']!='') {
						$question="UPDATE amap_generale SET Adresse='".$_POST['adresse']."' WHERE id='".$_POST['id']."'";
						mysqli_query($question);
						echo "requete-adresse = ".$question."<br /><br />";
					}
					else {
						$question="UPDATE amap_generale SET Adresse=NULL WHERE id='".$_POST['id']."'";
						mysqli_query($question);
					}
					if($_POST['tel']!='') {
						$question="UPDATE amap_generale SET Telephone='".$_POST['tel']."' WHERE id='".$_POST['id']."'";
						mysqli_query($question);
						echo "requete-telephone = ".$question."<br /><br />";
					}
					else {
						$question="UPDATE amap_generale SET Telephone=NULL WHERE id='".$_POST['id']."'";
						mysqli_query($question);
					}
					if($_POST['portable']!='') {
						$question="UPDATE amap_generale SET Tel_portable='".$_POST['portable']."' WHERE id='".$_POST['id']."'";
						mysqli_query($question);
						echo "requete-portable = ".$question."<br /><br />";
					}
					else {
						$question="UPDATE amap_generale SET Tel_portable=NULL WHERE id='".$_POST['id']."'";
						mysqli_query($question);
					}
					$question="UPDATE amap_generale SET Paiement_centre='".$_POST['pay_centre']."', Amap_legumes='".$_POST['amap_legumes']."', ";
					$question=$question."Amap_legumes_liste_attente='".$_POST['liste_attente_leg']."', Amap_pommes='".$_POST['amap_pommes']."', Amap_viande_bovine='".$_POST['amap_viande_bovine']."' ";
					$question=$question."WHERE id='".$_POST['id']."'";
					mysqli_query($question);

					echo "requete-drapeaux 0/1 = ".$question."<br />";
					echo "<br /><br />paramètres enregistrés<br /><br />";
					echo "Nom : ".$_POST['nom']."<br />";
					echo "Prénom : ".$_POST['prenom']."<br />";
					echo "e_mail : ".$_POST['e_mail']."<br />";
					echo "Adresse : ".$_POST['adresse']."<br />";
					echo "Téléphone : ".$_POST['tel']."<br />";
					echo "Tel portable : ".$_POST['portable']."<br />";
					echo "Date inscription : ".$_POST['date']."<br />";
					echo "Paiement centre : ".$_POST['pay_centre']."<br />";
					echo "inscription amap_legumes : ".$_POST['amap_legumes']."<br />";
					echo "inscription amap_legumes_liste_attente : ".$_POST['liste_attente_leg']."<br />";
					echo "inscription amap_pommes : ".$_POST['amap_pommes']."<br />";
					echo "inscription amap_viande_bovine : ".$_POST['amap_viande_bovine']."<br />";
				}
			}
			mysqli_close();
		}
		} 
/*******************************************************************************************************************/
/* modifier enregistrement sans maj  tables legumes
/*******************************************************************************************************************/
		if($_GET['table']=='amap_legumes' && $_GET['action']=='modifier') {
			echo "connexion = ".mysqli_connect(hote, login, mot_passe_sql)."<br />"; // Connexion à MySQL
			if(mysqli_select_db(base_de_donnees)) echo "selection-base = true<br /><br />"; else echo "selection-base = false<br />";
			
			$question="SELECT * FROM amap_legumes WHERE id='".$_POST['id']."'";
			$reponse=mysqli_query($question);
			$nbre_eng=mysqli_num_rows($reponse);
			if($nbre_eng==0) { echo "Pas d'adhérent avec l'id : ".$_POST['id'];}
			else {
				$donnees=mysqli_fetch_array($reponse);
				?>
				<h4> Adhérent : <?php echo $donnees['id']; ?></h4>
				<form name="form_ajouter"  method="post" action="webmaster_maj_tables.php?table=amap_legumes&amp;action=maj" >
				<p>
					<label for="id">id = </label>
					<input type="text" style="background:#EEEEEE" size="3" readonly name="id" id="id" value="<?php echo $donnees['id']; ?>"/> 
					<label for="nom">Nom = </label>
					<input type="text"  style="background:#EEEEEE" readonly name="nom" id="nom" value="<?php echo $donnees['Nom']; ?>"/>     <!-- style="text-transform:uppercase"-->
					<label for="prenom">Prénom = </label>
					<input type="text"   style="background:#EEEEEE" readonly name="prenom" id="prenom" value="<?php echo $donnees['Prenom']; ?>"/>   <!-- style="text-transform:capitalize"-->
					<br /><br />
					<label for="nb_panier">Nombre de panier = </label>
					<input type="text" size="1" maxlength="1" name="nb_panier" id="nb_panier" value="<?php echo $donnees['Nombre_panier']; ?>"/>
					<label for="nb_cheque">Nombre de chèque = </label>
					<input type="text" size="1" maxlength="1" name="nb_cheque" id="nb_cheque" value="<?php echo $donnees['Nbre_cheque']; ?>"/>
					<br /><br />
					<label for="date_paiement">Date paiement aaaa-mm-jj = </label>
					<input type="text" size="20" name="date_paiement" id="date_paiement" value="<?php echo $donnees['Date_paiement']; ?>"/>
					<br /><br />
					<label for="date_deb_contrat">Date début contrat aaaa-mm-jj = </label>
					<input type="text" size="20" name="date_deb_contrat" id="date_deb_contrat" value="<?php echo $donnees['Date_paiement']; ?>"/>
					<br /><br />
					<label for="date_fin_contrat">Date fin contrat aaaa-mm-jj = </label>
					<input type="text" size="20" name="date_fin_contrat" id="date_fin_contrat" value="<?php echo $donnees['Date_paiement']; ?>"/>

					<input type="submit" value="Enregistrer les modifications"/> <!-- onclick="javascript:tester(this.form);"-->
				</p>
				</form>
			<?php
			}
			mysqli_close();
		}
/*******************************************************************************************************************/
/* modifier enregistrement sans maj  tables pommes
/*******************************************************************************************************************/
		if($_GET['table']=='amap_pommes' && $_GET['action']=='modifier') {
			echo "connexion = ".mysqli_connect(hote, login, mot_passe_sql)."<br />"; // Connexion à MySQL
			if(mysqli_select_db(base_de_donnees)) echo "selection-base = true<br /><br />"; else echo "selection-base = false<br />";
			
			$question="SELECT * FROM amap_pommes WHERE id='".$_POST['id']."'";
			$reponse=mysqli_query($question);
			$nbre_eng=mysqli_num_rows($reponse);
			if($nbre_eng==0) { echo "Pas d'adhérent avec l'id : ".$_POST['id'];}
			else {
				$donnees=mysqli_fetch_array($reponse);
				?>
				<h4> Adhérent : <?php echo $donnees['id']; ?></h4>
				<form name="form_ajouter" method="post" action="webmaster_maj_tables.php?table=amap_pommes&amp;action=maj" >
				<p>
					<label for="id">id = </label>
					<input type="text" style="background:#EEEEEE" size="3" readonly name="id" id="id" value="<?php echo $donnees['id']; ?>"/> 
					<label for="nom">Nom = </label>
					<input type="text"  style="background:#EEEEEE" readonly name="nom" id="nom" value="<?php echo $donnees['Nom']; ?>"/>     <!-- style="text-transform:uppercase"-->
					<label for="prenom">Prénom = </label>
					<input type="text"   style="background:#EEEEEE" readonly name="prenom" id="prenom" value="<?php echo $donnees['Prenom']; ?>"/>   <!-- style="text-transform:capitalize"-->
					<br /><br />
					<label for="nb_pom_doux">Nb pltx pommes doux = </label>
					<input type="text" size="1" maxlength="1" name="nb_pom_doux" id="nb_pom_doux" value="<?php echo $donnees['Nbre_pltx_pom_doux']; ?>"/>
					<label for="nb_pom_acide">Nb_pltx_pommes_acide = </label>
					<input type="text" size="1" maxlength="1" name="nb_pom_acide" id="nb_pom_acide" value="<?php echo $donnees['Nbre_pltx_pom_acide']; ?>"/>
					<label for="nb_pom_alterne">Nb pltx pommes alterné = </label>
					<input type="text" size="1" maxlength="1" name="nb_pom_alterne" id="nb_pom_alterne" value="<?php echo $donnees['Nbre_pltx_pom_alterne']; ?>"/>
					<br /><br />
					<label for="nb_jus_pom_nature">Nb_jus_pommes_nature = </label>
					<input type="text" size="1" maxlength="1" name="nb_jus_pom_nature" id="nb_jus_pom_nature" value="<?php echo $donnees['Nbre_jus_pom_nature']; ?>"/>
					<label for="nb_jus_pom_citron">Nb_jus_pommes_citron = </label>
					<input type="text" size="1" maxlength="1" name="nb_jus_pom_citron" id="nb_jus_pom_citron" value="<?php echo $donnees['Nbre_jus_pom_citron']; ?>"/>
					<label for="nb_jus_pom_cannelle">Nb_jus_pommes_cannelle = </label>
					<input type="text" size="1" maxlength="1" name="nb_jus_pom_cannelle" id="nb_jus_pom_cannelle" value="<?php echo $donnees['Nbre_jus_pom_cannelle']; ?>"/>
					<br /><br />
					<label for="nb_cheque">Nombre de chèque = </label>
					<input type="text" size="1" maxlength="1" name="nb_cheque" id="nb_cheque" value="<?php echo $donnees['Nbre_cheque']; ?>"/>
					<br /><br />
					<label for="date_paiement">Date paiement aaaa-mm-dd = </label>
					<input type="text" name="date_paiement" id="date_paiement" value="<?php echo $donnees['Date_paiement']; ?>"/>
					<br /><br />
					<label for="date_deb_contrat">Date_début_contrat aaaa-mm-jj = </label>
					<input type="text" name="date_deb_contrat" id="date_deb_contrat" value="<?php echo $donnees['Date_debut_contrat']; ?>"/>
					<br /><br />
					<label for="date_fin_contrat">Date_fin_contrat aaaa-mm-jj = </label>
					<input type="text" name="date_fin_contrat" id="date_fin_contrat" value="<?php echo $donnees['Date_fin_contrat']; ?>"/>
					<br /><br />
					<input type="submit" value="Enregistrer les modifications"/> <!-- onclick="javascript:tester(this.form);"-->
				</p>
				</form>
			<?php
			}
			mysqli_close();
		}
/*******************************************************************************************************************/
/* modifier enregistrement sans maj  tables viande bovine
/*******************************************************************************************************************/
		if($_GET['table']=='amap_viande_bovine' && $_GET['action']=='modifier') {
			echo "connexion = ".mysqli_connect(hote, login, mot_passe_sql)."<br />"; // Connexion à MySQL
			if(mysqli_select_db(base_de_donnees)) echo "selection-base = true<br /><br />"; else echo "selection-base = false<br />";
			
			$question="SELECT * FROM amap_viande_bovine WHERE id='".$_POST['id']."'";
			$reponse=mysqli_query($question);
			$nbre_eng=mysqli_num_rows($reponse);
			if($nbre_eng==0) { echo "Pas d'adhérent avec l'id : ".$_POST['id'];}
			else {
				$donnees=mysqli_fetch_array($reponse);
				?>
				<h4> Adhérent : <?php echo $donnees['id']; ?></h4>
				<form name="form_ajouter" method="post" action="webmaster_maj_tables.php?table=amap_viande_bovine&amp;action=maj" >
				<p>
					<label for="id">id = </label>
					<input type="text" style="background:#EEEEEE" size="3" readonly name="id" id="id" value="<?php echo $donnees['id']; ?>"/> 
					<label for="nom">Nom = </label>
					<input type="text"  style="background:#EEEEEE" readonly name="nom" id="nom" value="<?php echo $donnees['Nom']; ?>"/>     <!-- style="text-transform:uppercase"-->
					<label for="prenom">Prénom = </label>
					<input type="text"   style="background:#EEEEEE" readonly name="prenom" id="prenom" value="<?php echo $donnees['Prenom']; ?>"/>   <!-- style="text-transform:capitalize"-->
					<br /><br />
					<label for="nb_colis_5kg_cmd">Nb colis 5kg commandé= </label>
					<input type="text" size="1" maxlength="1" name="nb_colis_5kg_cmd" id="nb_colis_5kg_cmd" value="<?php echo $donnees['Nbre_colis_5kg_commande']; ?>"/>
					<label for="prix_colis_5kg">Prix colis 5kg = </label>
					<input type="text" size="10" maxlength="1" name="prix_colis_5kg" id="prix_colis_5kg" value="<?php echo $donnees['Prix_colis_5kg']; ?>"/>
					<label for="nb_colis_5kg_retire">Nb colis 5kg retiré = </label>
					<input type="text" size="1" maxlength="1" name="nb_colis_5kg_retire" id="nb_colis_5kg_retire" value="<?php echo $donnees['Nbre_colis_5kg_retire']; ?>"/>
					<br /><br />
					<label for="nb_colis_10kg_cmd">Nb colis 10kg commandé= </label>
					<input type="text" size="1" maxlength="1" name="nb_colis_10kg_cmd" id="nb_colis_10kg_cmd" value="<?php echo $donnees['Nbre_colis_10kg_commande']; ?>"/>
					<label for="prix_colis_10kg">Prix colis 10kg = </label>
					<input type="text" size="10" maxlength="1" name="prix_colis_10kg" id="prix_colis_10kg" value="<?php echo $donnees['Prix_colis_10kg']; ?>"/>
					<label for="nb_colis_10kg_retire">Nb colis 10kg retiré = </label>
					<input type="text" size="1" maxlength="1" name="nb_colis_10kg_retire" id="nb_colis_10kg_retire" value="<?php echo $donnees['Nbre_colis_10kg_retire']; ?>"/>
					<br /><br />
					<label for="date_paiement">Date_paiement aaaa-mm-jj = </label>
					<input type="text" name="date_paiement" id="date_paiement" value="<?php echo $donnees['Date_paiement']; ?>"/>
					<br /><br />
					<label for="date_deb_contrat">Date_début_contrat aaaa-mm-dd = </label>
					<input type="text" name="date_deb_contrat" id="date_deb_contrat" value="<?php echo $donnees['Date_debut_contrat']; ?>"/>
					<br /><br />
					<label for="date_fin_contrat">Date_fin_contrat aaaa-mm-dd = </label>
					<input type="text" name="date_fin_contrat" id="date_fin_contrat" value="<?php echo $donnees['Date_fin_contrat']; ?>"/>
					<br /><br />
					<input type="submit" value="Enregistrer les modifications"/>
				</p>
				</form>
			<?php
			}
			mysqli_close();
		}
/*******************************************************************************************************************/
/* maj  tables legumes
/*******************************************************************************************************************/
		if($_GET['table']=='amap_legumes' && $_GET['action']=='maj') {
			echo "connexion = ".mysqli_connect(hote, login, mot_passe_sql)."<br />"; // Connexion à MySQL
			if(mysqli_select_db(base_de_donnees)) echo "selection-base = true<br /><br />"; else echo "selection-base = false<br />";
			$question="UPDATE amap_legumes SET Nombre_panier='".$_POST['nb_panier']."', Nre_cheque='".$_POST['nb_cheque']."' WHERE id='".$_POST['id']."'";
			mysqli_query($question);
			if($_POST['date_paiement']=='')
				$question="UPDATE amap_legumes SET Date_paiement=NULL WHERE id='".$_POST['id']."'";
			else
				$question="UPDATE amap_legumes SET Date_paiement='".$_POST['date_paiement']."' WHERE id='".$_POST['id']."'";
			mysqli_query($question);
			echo "maj table amap légumes = ".$question."<br /><br />";
			if($_POST['date_deb_contrat']=='')
				$question="UPDATE amap_legumes SET Date_debut_contrat=NULL WHERE id='".$_POST['id']."'";
			else
				$question="UPDATE amap_legumes SET Date_debut_contrat='".$_POST['date_deb_contrat']."' WHERE id='".$_POST['id']."'";
			mysqli_query($question);
			echo "maj table amap légumes = ".$question."<br /><br />";
			if($_POST['date_fin_contrat']=='')
				$question="UPDATE amap_legumes SET Date_fin_contrat=NULL WHERE id='".$_POST['id']."'";
			else
				$question="UPDATE amap_legumes SET Date_fin_contrat='".$_POST['date_fin_contrat']."' WHERE id='".$_POST['id']."'";
			mysqli_query($question);
			echo "maj table amap légumes = ".$question."<br /><br />";
		}

/*******************************************************************************************************************/
/* maj  tables pommes
/*******************************************************************************************************************/
		if($_GET['table']=='amap_pommes' && $_GET['action']=='maj') {
			echo "connexion = ".mysqli_connect(hote, login, mot_passe_sql)."<br />"; // Connexion à MySQL
			if(mysqli_select_db(base_de_donnees)) echo "selection-base = true<br /><br />"; else echo "selection-base = false<br />";
			
			$question="UPDATE amap_pommes SET Nbre_pltx_pom_doux='".$_POST['nb_pom_doux']."', Nbre_pltx_pom_acide='".$_POST['nb_pom_acide']."', Nbre_pltx_pom_alterne='".$_POST['nb_pom_alterne']."' WHERE id='".$_POST['id']."'";
			mysqli_query($question);
			$question="UPDATE amap_pommes SET Nbre_jus_pom_nature='".$_POST['nb_jus_pom_nature']."', Nbre_jus_pom_citron='".$_POST['nb_jus_pom_citron']."', Nbre_jus_pom_cannelle='".$_POST['nb_jus_pom_cannelle']."' WHERE id='".$_POST['id']."'";
			mysqli_query($question);
			$question="UPDATE amap_pommes SET Nbre_cheque='".$_POST['nb_cheque']."' WHERE id='".$_POST['id']."'";
			mysqli_query($question);
			if($_POST['date_paiement']=='')
				$question="UPDATE amap_pommes SET Date_paiement=NULL WHERE id='".$_POST['id']."'";
			else
				$question="UPDATE amap_pommes SET Date_paiement='".$_POST['date_paiement']."' WHERE id='".$_POST['id']."'";
			mysqli_query($question);
			echo "maj table amap pommes = ".$question."<br /><br />";
			if($_POST['date_deb_contrat']=='')
				$question="UPDATE amap_pommes SET Date_debut_contrat=NULL WHERE id='".$_POST['id']."'";
			else
				$question="UPDATE amap_pommes SET Date_debut_contrat='".$_POST['date_deb_contrat']."' WHERE id='".$_POST['id']."'";
			mysqli_query($question);
			echo "maj table amap pommes = ".$question."<br /><br />";
			if($_POST['date_fin_contrat']=='')
				$question="UPDATE amap_pommes SET Date_fin_contrat=NULL WHERE id='".$_POST['id']."'";
			else
				$question="UPDATE amap_pommes SET Date_fin_contrat='".$_POST['date_fin_contrat']."' WHERE id='".$_POST['id']."'";
			mysqli_query($question);
			echo "maj table amap pommes = ".$question."<br /><br />";
		}


/*******************************************************************************************************************/
/* maj  tables viande bovine
/*******************************************************************************************************************/
		if($_GET['table']=='amap_viande_bovine' && $_GET['action']=='maj') {
			echo "connexion = ".mysqli_connect(hote, login, mot_passe_sql)."<br />"; // Connexion à MySQL
			if(mysqli_select_db(base_de_donnees)) echo "selection-base = true<br /><br />"; else echo "selection-base = false<br />";
			$question="UPDATE amap_viande_bovine SET Nbre_colis_5kg_commande='".$_POST['nb_colis_5kg_cmd']."', Prix_colis_5kg='".$_POST['prix_colis_5kg']."', Nbre_colis_5kg_retire='".$_POST['nb_colis_5kg_retire']."' WHERE id='".$_POST['id']."'";
			mysqli_query($question);
			$question="UPDATE amap_viande_bovine SET Nbre_colis_10kg_commande='".$_POST['nb_colis_10kg_cmd']."', Prix_colis_10kg='".$_POST['prix_colis_10kg']."', Nbre_colis_10kg_retire='".$_POST['nb_colis_10kg_retire']."' WHERE id='".$_POST['id']."'";
			mysqli_query($question);
			if($_POST['date_paiement']=='')
				$question="UPDATE amap_viande_bovine SET Date_paiement=NULL WHERE id='".$_POST['id']."'";
			else
				$question="UPDATE amap_viande_bovine SET Date_paiement='".$_POST['date_paiement']."' WHERE id='".$_POST['id']."'";
			mysqli_query($question);
			echo "maj table amap viande bovine = ".$question."<br /><br />";
			if($_POST['date_deb_contrat']=='')
				$question="UPDATE amap_viande_bovine SET Date_debut_contrat=NULL WHERE id='".$_POST['id']."'";
			else
				$question="UPDATE amap_viande_bovine SET Date_debut_contrat='".$_POST['date_deb_contrat']."' WHERE id='".$_POST['id']."'";
			mysqli_query($question);
			echo "maj table amap viande bovine = ".$question."<br /><br />";
			if($_POST['date_fin_contrat']=='')
				$question="UPDATE amap_viande_bovine SET Date_fin_contrat=NULL WHERE id='".$_POST['id']."'";
			else
				$question="UPDATE amap_viande_bovine SET Date_fin_contrat='".$_POST['date_fin_contrat']."' WHERE id='".$_POST['id']."'";
			mysqli_query($question);
			echo "maj table amap viande bovine = ".$question."<br /><br />";

		}
		?>
	</body>
</html>
