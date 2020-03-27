<?php
include_once("webmaster/define.php");
mysql_connect(hote, login, mot_passe_sql); // Connexion à MySQL
mysql_select_db(base_de_donnees); // Sélection de la base 
$question="UPDATE amap_produits_laitiers_cde SET Beurre_160g_sale='".$_POST['Elt1ID']."'";
$question.=", Beurre_160g_doux='".$_POST['Elt2ID']."'";
$question.=", Creme_250g='".$_POST['Elt3ID']."'";
$question.=", Yaourt_nature_5x125g='".$_POST['Elt4ID']."'";
$question.=", Yaourt_nature_vrac='".$_POST['Elt5ID']."'";
$question.=", Yaourt_aromatise_4x125g_peche='".$_POST['Elt6ID']."'";
$question.=", Yaourt_aromatise_vrac_peche='".$_POST['Elt7ID']."'";
$question.=", Yaourt_aromatise_4x125g_abricot='".$_POST['Elt8ID']."'";
$question.=", Yaourt_aromatise_vrac_abricot='".$_POST['Elt9ID']."'";
$question.=", Yaourt_aromatise_4x125g_vanille='".$_POST['Elt10ID']."'";
$question.=", Yaourt_aromatise_vrac_vanille='".$_POST['Elt11ID']."'";
$question.=", Yaourt_aromatise_4x125g_citron='".$_POST['Elt12ID']."'";
$question.=", Yaourt_aromatise_vrac_citron='".$_POST['Elt13ID']."'";
$question.=", Yaourt_aromatise_4x125g_framboise='".$_POST['Elt14ID']."'";
$question.=", Yaourt_aromatise_vrac_framboise='".$_POST['Elt15ID']."'";
$question.=", Yaourt_aromatise_4x125g_fraise='".$_POST['Elt16ID']."'";
$question.=", Yaourt_aromatise_vrac_fraise='".$_POST['Elt17ID']."'";
$question.=", Yaourt_aromatise_4x125g_mixte='".$_POST['Elt18ID']."'";
$question.=", Fromage_frais_nature='".$_POST['Elt19ID']."'";
$question.=", Fromage_frais_herbes_150g='".$_POST['Elt20ID']."'";
$question.=", Bleruchon_100_a_150g='".$_POST['Elt21ID']."'";
$question.=", Bleruchon_225_a_275g='".$_POST['Elt22ID']."'";
$question.=", Faisselle_500g='".$_POST['Elt23ID']."'";
$question.=", Fromage_blanc_500g='".$_POST['Elt24ID']."'";
$question.=", Fromage_blanc_maigre_500g='".$_POST['Elt25ID']."'";
$question.=", Lait_cru_2L='".$_POST['Elt26ID']."'";
$question.=", Lait_ribot_1L5='".$_POST['Elt27ID']."'";
$question.=", Lait_cru_1L_Yaourt_nature_2x125g='".$_POST['Elt28ID']."'";
$question.=", Date_modif='".date("Y-m-d",time())."'";

$question.=" WHERE id='".$_POST['ID']."'";

$reponse=mysql_query($question) or die(mysql_error());

mysql_close();
$page="Location: index.php";
header($page);
?>