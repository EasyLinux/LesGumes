<?php
/**
 * mariadb: classe pour l'accès à la base de donnée
 * 
 */


/**
 * 
 */
class cMariaDb
{
    private $Db;
    private $Result;
    private $Errno;
    private $ErrMsg;

    /**
     * contruct
     * 
     * Connection au serveur identifié par $Cfg, 
     * positionne les erreurs
     * 
     * @param   array   $Cfg    Chaine de configuration
     * @return  object          pointe sur l'object MariaDb
     */
    function __construct($Cfg) {
        $this->Db = new mysqli(
            $Cfg["MariaDb"]["Host"], 
            $Cfg["MariaDb"]["User"], 
            $Cfg["MariaDb"]["Pass"], 
            $Cfg["MariaDb"]["Base"]);
        if($this->Db->connect_errno) {
            $this->Errno = $this->Db->connect_errno;
            $this->ErrMsg = $this->Db->connect_error;
        }
      }

    /**
     * getDb
     * 
     * get db object opened by this class
     * 
     * @param  void
     * @return object  mysqli object
     */
    function getDb()
    {
        return $this->Db;
    }

    /**
     * Query
     * 
     * do a SQL request on the server, this function does not return
     * affected rows
     * 
     * @param   string $sSQL  SQL command to be executed
     * @return  int    error code (0 if OK)
     */
    function Query($sSQL)
    {
        $this->Result = $this->Db->query($sSQL);
        if( $this->Db->errno )
        {
            $this->Errno = $this->Db->errno;
            $this->ErrMsg = $this->Db->error;
        }
    }

    /**
     * getErrorMsg
     * 
     * retreive error of last SQL command
     * @param  void
     * @return string  SQL Message
     */
    function getErrorMsg()
    {
        return $this->ErrMsg;
    }

    /**
     * getAllFetch
     * 
     * Do a sql request and return an array with results
     * 
     * @param   string    $sSQL  sql request
     * @return  array     result
     */
    function getAllFetch($sSQL)
    {
        $aRet = [];
        if (!$oResult = $this->Db->query($sSQL)) {
            $this->Errno = $this->Db->errno;
            $this->ErrMsg = $this->Db->error;
            return false;
        }
        if ($oResult->num_rows === 0) {
            return [];
        }
        $aRet = $oResult->fetch_all(MYSQLI_ASSOC);
        $oResult->free();   
        return $aRet; 
    }    

    /**
     * getLastId
     * 
     * renvoi l'id du dernier enregistrement 
     * 
     * @param  void
     * @return int   id de l'enregistrement
     */
    function getLastId()
    {
        return mysqli_insert_id($this->Db);
    }

    /** getNumRows
     * 
     */
    function getNumRows()
    {
        return $this->Result->num_rows;
    }
}
