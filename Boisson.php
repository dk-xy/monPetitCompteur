<?php
namespace ch\comem;

use ch\comem\Database as Database;
use Exception;
use PDO;

ini_set('display_errors', 1);

class Boisson {
    private $id;
    private $nom;
    private $type;

    /**
     * Instancie la liaison entre la classe et la base de données.
     * 
     * @param Database $db : la connection à la BDD, instanciée au préalable.
     * @return void
     */
    public function __construct(Database $db, $b_id)
    {
        if(isset($db)) {
            $requete = $db->bdd->prepare("SELECT * FROM boissons WHERE id = '$b_id'");
            $requete->execute();

            $requete->setFetchMode(PDO::FETCH_ASSOC);
            $donnees = $requete->fetch();

            $this->id = $donnees['id'];
            $this->nom = $donnees['nom'];
            $this->type = $donnees['type'];

        } else {
            throw new Exception("La base de donnée n'existe pas.");
        }
    }

    public function rendId() {
        return $this->id;
    }

    public function rendNom() {
        return $this->nom;
    }

    public function retrouveNom() {
        return $this->nom;
    }

    public function rendType() {
        return $this->type;
    }
}

?>