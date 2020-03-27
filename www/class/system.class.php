<?php

/**
 * Fonctionnalitées lièes au système du site
 */
class system 
{
    private $Db;
    private $Error;

    /**
     * Constructeur
     * 
     * @param  object   Base de données
     */
    function __construct($Db) {
        $this->Db = $Db;
        }

    /**
     * getMenu
     * 
     * Renvoyer les lignes de menu (table sys_menu) dans une array
     * 
     * @param void
     * @return array Résultats
     */
    function getMenu()
    {
        $aRet = [];
        $sSQL = "SELECT * FROM sys_menu;";
        if (!$oResult = $this->Db->query($sSQL)) {
            $aRet = ["Errno" => $this->Db->errno, "ErrMsg" => $this->Db->error];
            return $aRet;;
        }
        if ($oResult->num_rows === 0) {
            $aRet = ["Errno" => $this->Db->errno, "ErrMsg" => "Table sys_menu vide !"];
            return $aRet;;
        }
        $aRet = $oResult->fetch_all(MYSQLI_ASSOC);
        $oResult->free();   
        return $aRet; 
    }

}