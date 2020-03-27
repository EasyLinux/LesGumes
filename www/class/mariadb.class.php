<?php
/**
 * mariadb: classe pour l'accès à la base de donnée
 * 
 */


/**
 * 
 */
class MariaDb
{
    private $Db;

    function __construct() {
        include_once("config/config.php");
        $this->Db = new mysqli(
            $Cfg["MariaDb"]["Host"], 
            $Cfg["MariaDb"]["User"], 
            $Cfg["MariaDb"]["Pass"], 
            $Cfg["MariaDb"]["Base"]);
      }

    function getDb()
    {
        return $this->Db;
    }
}
