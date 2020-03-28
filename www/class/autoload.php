<?php
/**
 * Autoload
 * 
 * ce fichier permet d'incluer automatiquement les classes définies dans ce répertoire.
 * 
 * Les classes doivent être de la forme <nom>.class.php
 */
if ($dh = opendir(__DIR__)) {
    // Parcourir le répertoire
    while (($file = readdir($dh)) !== false) {
        if( strstr($file,".class.") != "" ) {
            // Le fichier correspond
            require_once(__DIR__."/".$file);
        }
    }
    closedir($dh);
}
