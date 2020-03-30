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
            return $aRet;
        }
        if ($oResult->num_rows === 0) {
            $aRet = ["Errno" => $this->Db->errno, "ErrMsg" => "Table sys_menu vide !"];
            return $aRet;
        }
        $aRet = $oResult->fetch_all(MYSQLI_ASSOC);
        $oResult->free();   
        return $aRet; 
    }

    /**
     * getNews
     * 
     * Cette fonction interroge la base de données et renvoi le nombre de nouvelles
     * passé en paramètres
     * 
     * @param   int      $nbre    Nombre de nouvelles désiré
     * @return  array    tableau associatif des nouvelles
     */
    function getNews($iNbre)
    {
        $aRet = [];
        $sSQL = "SELECT * FROM news ORDER BY id DESC LIMIT 0, $iNbre";
        if( isset($this->Db) )
        {
            if (!$aResult = $this->Db->query($sSQL)) {
                $aRet = ["Errno" => $this->Db->errno, "ErrMsg" => $this->Db->error];
                return $aRet;
            }
            if ($aResult->num_rows === 0) {
                $aRet = ["Errno" => $this->Db->errno, "ErrMsg" => "Table news vide !"];
                return $aRet;
            }
            $aRet = $aResult->fetch_all(MYSQLI_ASSOC);
            $aResult->free();   
        }
        return $aRet;

    }
}