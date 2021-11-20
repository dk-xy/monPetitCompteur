<?php

namespace ch\comem;

require_once 'Database.php';

use ch\comem\Database as Database;
use \Exception;

class Utilisateur
{
    private $id;
    private $login;
    private $db;

    /**
     * Instancie la liaison entre la classe et la base de données.
     * 
     * @param Database $db : la connection à la BDD, instanciée au préalable.
     * @return void
     */
    public function __construct(Database $db, int $user_id)
    {
        if (isset($db)) {
            $this->db = $db;
            $utilisateur = $db->retourneLigne("SELECT * FROM utilisateur WHERE id = $user_id");
            if ($utilisateur) {
                $this->id = $utilisateur['ID'];
                $this->login = $utilisateur['login'];
            } else {
                throw new Exception("L'utilisateur n'existe pas.");
            }
        } else {
            throw new Exception("La base de donnée n'existe pas.");
        }
    }

    public function rendId()
    {
        return $this->id;
    }

    public function rendLogin()
    {
        return $this->login;
    }

    //to finish
    public function afficheConsoTotale($u_id)
    {
        $requ_user_all = "SELECT * FROM consommation WHERE utilisateur_id=" . $u_id;
    }
    
    /**a enlever */

    public function inscritUtilisateur($u_id,$login,$mdp){
        $requete_insert = "INSERT INTO utilisateur VALUES(".$u_id.",'".$login."','".$mdp."')";
        //$requete = $this->db->prepare($requete_insert);
        // $requete->execute(); 
        //$requete_insert = "INSERT INTO consommation VALUES('".$u_id."','".$b_id."','".$cur_date."','".$nb."');";
        $this->db->executerRequete($requete_insert);
    
}

}
