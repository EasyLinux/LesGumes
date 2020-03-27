<?php include("webmaster/define.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<!-- xmlns indique une adresse traitant du xHTML -->
<!-- xml:lang : sert � indiquer dans quelle langue est r�dig�e votre page -->
	<head>
		<title>AMAP Saint-S�bastien/Loire</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<!-- meta indique que l'on utilise des caract�res sp�cifiques au fran�ais ����... -->
		<link rel="stylesheet" media="screen" type="text/css" title="css_style" href="style.css" />
		<link rel="icon" type="image/jpeg" href="images/favicone-2.jpeg" />
	</head>
	<body>
		<?php 
		mysql_connect(hote, login, mot_passe_sql); // Connexion � MySQL
		mysql_select_db(base_de_donnees); // S�lection de la base 
		$question="SELECT * FROM amap_generale WHERE id='".$_GET['id']."'";
		$reponse = mysql_query($question) or die(mysql_error());
		$donnees = mysql_fetch_array($reponse);
		$nom=$donnees['Nom'];
		$prenom=$donnees['Prenom'];
		mysql_close();
		?>


<script language="javascript">
<!--
function ADDBSetRowColorByID(strHtmlID,nCurrent,nSelected){
	 var strClassName,objElem=document.getElementById(strHtmlID);
	 if (nSelected!=null){
	 if (nSelected){
	 ADDBAddClass(objElem,'DBEditRowSelected');
	 ADDBRemoveClass(objElem,'DBEditRowDefault');
	 ADDBRemoveClass(objElem,'DBEditRowCurrent');
	 nCurrent=null;
	 }
	 }
	 else if (ADDBContainClass(objElem,'DBEditRowSelected')) nCurrent=null;
	 if (nCurrent!=null){
	 if (nCurrent) {
	 ADDBAddClass(objElem,'DBEditRowCurrent');
	 ADDBRemoveClass(objElem,'DBEditRowDefault');
	 ADDBRemoveClass(objElem,'DBEditRowSelected');
	 }else{
	 ADDBAddClass(objElem,'DBEditRowDefault');
	 ADDBRemoveClass(objElem,'DBEditRowCurrent');
	 ADDBRemoveClass(objElem,'DBEditRowSelected');
	 }
	 }
} 

function ADDBEditSelectAll() {
	 var objForm = document.MForm ;
	 for ( var i=0 ; i < objForm.elements.length ; i++ ){
	 var e = objForm.elements[i] ;
	 if (e.type=='checkbox') {
	 e.checked=!e.checked ;
	 ADDBSetRowColorByID('TR-'+e.value,0,e.checked);
	 }
	 }
} 

function ADDBContainClass(objElem, strClassName){
	return ((' '+objElem.className+' ').indexOf(' '+strClassName+' ')>=0);
}

function ADDBAddClass(objElem, strClassName){
	var cls = objElem.className;
	if((' '+cls+' ').indexOf(' '+strClassName+' ')<0) objElem.className=cls+(cls?' ':'')+strClassName;
}

function ADDBRemoveClass(objElem, strClassName){
	 if(ADDBContainClass(objElem, strClassName)){
	 var classList=objElem.className.split(' ');
	 var from=0,len=classList.length;
	 for (;from< len;from++)
	 if (classList[from]===strClassName){
	 classList.splice(from,1);
	 objElem.className=classList.join(' ');
	 return;
	 }
	 }
} 
//-->
</script>


<script language="javascript">
<!--
function SelectItem( nRecordID, nActionID){
	document.MForm.SelectedRecID.value=nRecordID;
	bResult=SubmitAction(nActionID);
}
function createHiddenInput(strName, varValue)
{
	var objHidden = document.createElement("input");
	objHidden.setAttribute("type", "hidden");
	objHidden.setAttribute("name", strName);
	objHidden.setAttribute("value", varValue);
	return objHidden;
}
function createForm(strName, strMethod, strAction)
{
	var objForm = document.createElement("form");
	objForm.setAttribute("name", strName);
	objForm.setAttribute("method", strMethod);
	objForm.setAttribute("action", strAction);
	return objForm;
} 
function ConfirmDelete( strArticle, strPanier, strOrder){
	var strMsg;
	strMsg = "Supprimer le produit : #ORDERID# ?";
	var bResult = window.confirm(strMsg.replace(/#ORDERID#/gi, strArticle));

	if (bResult) {
		var objForm = createForm("ordermanager", "post", "hdb_supprime_ligne.php");
		objForm.appendChild(createHiddenInput("PanierID", strPanier));
		objForm.appendChild(createHiddenInput("ArticleID", strArticle));
		objForm.appendChild(createHiddenInput("OrderID", strOrder));
		document.body.appendChild(objForm);
		objForm.submit();
	}
} 

function ADDBConfirmeRecord(code) {
	var objForm = document.MForm ;
	var j = 0;
	var mail, adresse, commune, tel_1, tel_2;
	var regcommune=new RegExp("^[A-Z-]{2,40}$","");
	var regtel=new RegExp("^0[1-689]([-. ]?[0-9]{2}){4}$","");
	var regmail=new RegExp("^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$","");
	                        
	for ( var i=0; i< objForm.elements.length;i++) {
		var e = objForm.elements[i] ;
		if (e.name=='commune') {
			commune=e.value.toUpperCase();
			if(commune=='') j++;
			else { if (regcommune.test(commune)) j++; else window.alert("Format de commune invalide");}
		}
		if (e.name=='adresse') {
			adresse=e.value; j++;
		}
		if (e.name=='tel_1') {
			tel_1=e.value; 
			if(tel_1=='') j++;
			else { if(regtel.test(tel_1)) j++; else window.alert("Format du t�l�phone_1 invalide");}
		}
		if (e.name=='tel_2') {
			tel_2=e.value;
			if(tel_2=='') j++;
			else {if(regtel.test(tel_2)) j++; else window.alert("Format du t�l�phone_2 invalide");}
		}
		if (e.name=='mail') {
			mail=e.value; 
			if(mail=='') j++;
			else {if(regmail.test(mail)) j++; else window.alert("Format du mail invalide");}
		}
	}
	if(j==5 && commune=='' && adresse=='' && tel_1=='' && tel_2=='' && mail=='') { j=0; window.alert("Aucune infos n'a �t� saisie!!"); }
	if (j==5 && window.confirm("Enregistrer vos informations.")) {
		var objForm2 = createForm("ordermanager", "post", "repertoire_enregistre.php");
		objForm2.appendChild(createHiddenInput("CodeID", code));
		objForm2.appendChild(createHiddenInput("CommuneID", commune));
		objForm2.appendChild(createHiddenInput("AdresseID", adresse));
		objForm2.appendChild(createHiddenInput("Tel_1ID", tel_1));
		objForm2.appendChild(createHiddenInput("Tel_2ID", tel_2));
		objForm2.appendChild(createHiddenInput("MailID", mail));
		document.body.appendChild(objForm2);
		objForm2.submit();
	}
}
//-->
</script>

	<div id="menu_1" class="menubdhv">
			<span style="color:#FFFFFF; font-family:Arial; font-size:12pt; font-weight:bold; padding-left:5px;">Entrer les informations que vous voulez, laisser les autres vides</span><br />
		</div>
		<div id="menu_2" class="menubdhv">
			<button onclick="javascript:ADDBConfirmeRecord('<?php echo $_GET['id']; ?>')" name="BtnEnregistrer" type="Button" class="BtnStd">Enregistrer</button>
			<button onclick="document.location.href='repertoire.php?orderby=<?php echo $orderby; ?>'" name="BtnAnnuler" type="Button" class="BtnStd">Annuler</button><br />
		</div>
		<div id="menu_3" class="menubdhv">
		<form name="MForm">
			<table width="100%" cellpadding="2" cellspacing="2" style="text-align: left; border-collapse: collapse; background-color:#FFFFFF;">
				<tr>
					<td nowrap=""><label class="StaticText" for="nom">Nom :</label></td>
					<td width="100%">
						<span class="ADMainTxt" style="font-size:8pt;">Tel que enregistr�, non modifiable<br /></span>
						<input type="text" name="nom" id="nom" size="45" maxlength="40" disabled="disabled" value="<?php echo $nom; ?>" />
					</td>
				</tr>
				<tr>
					<td nowrap=""><label class="StaticText" for="prenom">Pr�nom :</label></td>
					<td width="100%">
						<span class="ADMainTxt" style="font-size:8pt;">Tel que enregistr�, non modifiable<br /></span>
						<input type="text" name="prenom" id="prenom" size="45" maxlength="40" disabled="disabled" value="<?php echo $prenom; ?>" />
					</td>
				</tr>
				<tr>
					<td nowrap=""><label class="StaticText" for="adresse">Adresse :</label></td>
					<td width="100%">
						<span class="ADMainTxt" style="font-size:8pt;">80 caract&egrave;res max<br /></span>
						<input type="text" name="adresse" id="adresse" size="85" maxlength="80" />
					</td>
				</tr>
				<tr>
					<td nowrap=""><label class="StaticText" for="commune">Commune :</label></td>
					<td width="100%">
						<span class="ADMainTxt" style="font-size:8pt;">Que des majuscules, pas d&rsquo;espace, utiliser le tiret<br /></span>
						<input type="text" name="commune" id="commune" size="45" maxlength="40" />
					</td>
				</tr>
				<tr>
					<td nowrap=""><label class="StaticText" for="tel_1">T�l�phone_1 :</label></td>
					<td width="100%">
						<span class="ADMainTxt" style="font-size:8pt;">10 chiffres, s�parateurs possibles : tiret, point, espace, ou rien<br /></span>
						<input type="text" name="tel_1" id="tel_1" size="16" maxlength="14" />
					</td>
				</tr>
				<tr>
					<td nowrap=""><label class="StaticText" for="tel_2">T�l�phone_2 :</label></td>
					<td width="100%">
						<span class="ADMainTxt" style="font-size:8pt;">10 chiffres, s�parateurs possibles : tiret, point, espace, ou rien<br /></span>
						<input type="text" name="tel_2" id="tel_2" size="16" maxlength="14" />
					</td>
				</tr>
				<tr>
					<td nowrap=""><label class="StaticText" for="mail">Adresse mail :</label></td>
					<td width="100%">
						<span class="ADMainTxt" style="font-size:8pt;">Mettre une adresse valide<br /></span>
						<input type="text" name="mail" id="mail" size="45" maxlength="40" />
					</td>
				</tr>
			</table>
		</form>
		</div>
	</body>
</html>