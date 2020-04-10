<?php

/**
 * Fonctionnalitées lièes au système du site
 */
class cSystem 
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
    function getMenu($aRights)
    {
        $aRet = ["Errno" => 0, "ErrMsg" => ""];

        $sSQL  = "SELECT * FROM sys_menu WHERE type='up' ";
        if( count($aRights) < 1 ){
            die("Youston: we've got a problem !");
        } 
        if( count($aRights) == 1){
            $sOR = "AND idRight=".$aRights[0]["id"];
        } else {
            $sOR = "AND (";
            $bFirst = true;
            foreach($aRights as $sAccess )
            {
                if( $bFirst){
                    $bFirst=false;
                    $sOR .= "idRight=".$sAccess["id"]." ";
                } else {
                    $sOR .= "OR idRight=".$sAccess["id"]." ";
                }
            }
            $sOR .= ")";
        }
        $sSQL .= $sOR . ";";
       
        // SELECT * FROM sys_menu WHERE type='up' AND (idRight=5 OR idRight=1 OR idRight=3 ) 
        if (!$oResult = $this->Db->query($sSQL)) {
            $aRet = ["Errno" => $this->Db->errno, "ErrMsg" => $this->Db->error];
            return $aRet;
        }
        if ($oResult->num_rows === 0) {
            $aRet = ["Errno" => $this->Db->errno, "ErrMsg" => "Table sys_menu vide !"];
            return $aRet;
        }
        $aRet['up'] = $oResult->fetch_all(MYSQLI_ASSOC);

        $sSQL = str_replace("up", "gauche", $sSQL);
        $oResult = $this->Db->query($sSQL); 
        $aTmp = $oResult->fetch_all(MYSQLI_ASSOC);
        // réorganisation des données pour avoir une vue hiérarchique
        foreach($aTmp AS $menuItem )
        {
            if( $menuItem["parent"] == 0){
                $aRet['gauche'][] = $menuItem;
            }
        }  
        // Deuxième niveau / troisème niveau
       $Idx=0;
       while( $Idx < count($aRet['gauche']))
       {
            // Parcours menu de niveau 0
            $Idy=0;
            $IdNew=0;
            while( $Idy < count($aTmp) )
            {
                // S'il existe des sous-menu
                if( $aTmp[$Idy]["parent"] == $aRet['gauche'][$Idx]["id"]){
                    // Entree de second niveau
                    $aRet['gauche'][$Idx]["childs"][]=$aTmp[$Idy];
                    
                    $Idz = 0;
                    while( $Idz < count($aTmp) )
                    {
                        if($aTmp[$Idz]["parent"] == $aTmp[$Idy]["id"]){
                            $aRet['gauche'][$Idx]["childs"][$IdNew]["childs"][]=$aTmp[$Idz];
                    }
                    $Idz++;
                    }
                $IdNew++;
                }
                $Idy++;
        }
        $Idx++;
       }
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
        $sSQL = "SELECT * FROM sys_news ORDER BY id DESC LIMIT 0, $iNbre";
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
            $aTmps = $aResult->fetch_all(MYSQLI_ASSOC);
            foreach($aTmps as $aTmp )
            {
                $aRet[] = ["id" => $aTmp["id"],
                           "titre" => $aTmp["sTitre"],
                           "contenu" => stripslashes($aTmp["tContenu"]),
                           "date" => $aTmp["dateCreation"]
                    ];
            }            
            $aResult->free();   
        }
        return $aRet;
    }

    /**
     * getPublicRight
     * 
     * Cette fonction interroge la base de données et renvoi l'identifiant corrsepondant à
     * un accès publique
     * 
     * @param   void
     * @return  array    tableau associatif
     */
    function getPublicRight()
    {
        $aRet = [];
        $sSQL = "SELECT id FROM sys_right WHERE sLabel='Public';";
        $aResult = $this->Db->query($sSQL);
        return $aResult->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * getUserRights
     * 
     * Cette fonction interroge la base de données et renvoi l'identifiant corrsepondant à
     * un accès publique
     * 
     * @param   int      $idUser    identifiant de l'utilisateur
     * @return  array    tableau associatif
     */
    function getUserRights($idUser)
    {
        $aRet = [];
        $sSQL = "SELECT idRights AS id FROM sys_user_rights WHERE idUser=$idUser;";
        $aResult = $this->Db->query($sSQL);
        return $aResult->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * getUser
     * 
     * Retreive users informations, use user credentials
     * 
     * @param string $login  Compte utilisateur
     * @param string $mdp    Mot de passe utilisateur
     * @return array         identification, Errno != 0 en cas d'erreur
     */
    function getUser($sLogin, $sPass)
    {
        // Le mot de passe est stocké encrypté en sha1
        $sSha1Pass = sha1($sPass);
        // Construire la requête
        $sSQL = "SELECT * FROM sys_user WHERE Mot_passe='$sSha1Pass' AND Login='$sLogin'";
        // Le résultat doit retourner une seule ligne
        $oResult = $this->Db->query($sSQL);
        if( $oResult->num_rows != 1) {
            $aResult = [
                "Errno"   => -1,
                "ErrMsg"  => "Compte ou mot de passe incorrect !"
            ];
        }
        else {
            $aResult = [
                "Errno" => 0,
                "ErrMsg" => "OK",
                "User" => $oResult->fetch_all(MYSQLI_ASSOC)[0]
            ];
            unset($aResult["User"]["Mot_passe"]);
        }
        return($aResult);     
    }

}
