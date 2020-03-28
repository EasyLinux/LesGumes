<?php
/**
 * Main page
 */
//include_once("config/config.php");
// if( !defined("VERSION") )
// {
// 	die("Upgrade required");
// }
// TODO supprimer l'include
include_once("webmaster/define.php");
include_once("config/config.php");

require_once("class/autoload.php");
require_once("vendor/autoload.php");
$db = new MariaDb($Cfg);
$system = new System($db->getDb());
// Generateur de templates
$tmpl = new Smarty();
$ok=-1;

if (isset($_COOKIE['identification_amap'])) // Si la variable existe
{
	// on est connecté
	if (isset($_GET['id'])) // Si la variable existe
	{
		$ok=1;
		// mysqli_connect(hote, login, mot_passe_sql); // Connexion à MySQL
		// mysqli_select_db(base_de_donnees) or die ('Impossible de sélectionner la base de données : ' . mysqli_error()); // Sélection de la base 
		if($_GET['reponse']=='oui') $question = "UPDATE enquete SET Ca_marche='OK' WHERE id='".$_GET['id']."'";
		else $question = "UPDATE enquete SET Ca_marche='Pas bon!' WHERE id='".$_GET['id']."'";
		$reponse = mysqli_query($question) or die('Impossible de sélectionner la base de données : ' .mysqli_error());
		mysqli_close();
	}
	else
	{
		$ok=1;
		$id=$_COOKIE['identification_amap'];
	}		
}	
// Connecté ?
$Connected = false;
$User="";

// TODO Test only
//$Connected = true;
// TODO rendre dynamique "SELECT * FROM amap_generale WHERE id='".$_COOKIE['identification_amap']."'";
//$User = "Serge NOEL";


$tmpl->assign("Connected",$Connected);
$tmpl->assign("User",$User);

// Récupérer les éléments de menu
$aMenu = $system->getMenu();
if( isset($aMenu["Errno"])) 
{
	die("ERREUR: Table sys_menu, veuillez contacter le responsable du site");
}
$tmpl->assign("Menu",$aMenu);
// echo nl2br(print_r($aMenu,true));


// Afficher la page principale
$tmpl->display("main.smarty");

