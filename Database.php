<?php 
namespace ch\comem;

use \Exception;
use \PDO;
use \PDOException;


class Database {

    private $serveur = "localhost";
    private $nomBaseDeDonnees = "daniel.khoury";
    private $utilisateur = "khoury";
    private $motDePasse = "obalie";
    
    public $bdd; // L'objet PDO sera stocké dans cette variable

    /**
     * Initialise la connection avec la BDD. À instancier avec $db = new Database dans chaque document ayant besoin d'une connection à la BDD.
     */
    public function __construct() {
        try {
            $this->bdd = new PDO('mysql:host='.$this->serveur.';dbname='.$this->nomBaseDeDonnees, $this->utilisateur, $this->motDePasse);

            return $this->bdd;

        } catch (PDOException $exception) {
            echo "Erreur !: ". $exception->getMessage()."<br/>";
            die();
        }
    }

    /**
     * Retourne la table $nomTable
     * 
     * @param string $nomTable : le nom de la table à retourner
     * @return array $donnees | false : un tableau de toutes les lignes de la table, ou `false` si la table est vide.
     */
    public function retourneTable($nomTable) {
        if(isset($this->bdd)) {
            // Préparation de la requête SQL
            $sql = "SELECT * FROM $nomTable;";

            $requete = $this->bdd->prepare($sql);
            $requete->execute();

            $requete->setFetchMode(PDO::FETCH_ASSOC);
            $donnees = $requete->fetchAll();
            
            return $donnees;

        } else {
            throw new Exception("La table n'est pas valide.");
        }
    }

    /**
     * Retourne une seule ligne, selon la requête SQL en paramètre
     * @param string $requeteSQL : la requête SQL à exécuter
     * @return array $donnees : un tableau des résultats de la requête, ou `false`.
     */
    public function retourneLigne($requeteSQL) {
        if(isset($this->bdd) && isset($requeteSQL) && $requeteSQL != null) {
            // Préparation de la requête SQL

            $requete = $this->bdd->prepare($requeteSQL);
            $requete->execute();

            $requete->setFetchMode(PDO::FETCH_ASSOC);
            $donnees = $requete->fetch();
            
            return $donnees;

        } else {
            throw new Exception("La requête n'est pas valide.");
        }
    }

    /**
     * Retourne les informations sur l'utilisateur, ou false si l'utilisateur et le mot de passe correspondant n'existe pas
     * 
     * @param string $pseudo : le pseudo de l'utilisateur
     * @param string $mdp : son mot de passe
     * 
     * @return array : ses informations personnelles, ou `false` s'il n'existe pas.
     */
    public function utilisateurExiste($pseudo, $mdp) {
        if(isset($this->bdd)) {
            $request = $this->bdd->prepare("SELECT * FROM utilisateur WHERE login = :login AND motDePasse = :motDePasse");
            $request->execute(['login' => $pseudo, 'motDePasse' => $mdp]);

            return $request->fetch();
        } else {
            throw new Exception("La requête n'est pas valide.");
        }
    }
    
    /**
     * Verifie si le login est inexistant
     * @param $pseudo le nom voulu
     * @return $request ou null si user inexistant
     */
    public function loginExiste($pseudo) {
        if(isset($this->bdd)) {
            $request = $this->bdd->prepare("SELECT * FROM utilisateur WHERE login = :login");
            $request->execute(['login' => $pseudo]);
            return $request->fetch();
        } else {
            return null;
        }
    }


    /**
     * Inscrit un utilisateur avec les informations
     */
    public function inscritUtilisateur($pseudo, $mdp, $count) {
        if(isset($this->bdd)) {
            $request = $this->bdd->prepare("INSERT INTO utilisateur VALUES('".$count."','".$pseudo."','".$mdp."')");
            //$request = $this->bdd->prepare("SELECT * FROM utilisateur WHERE login = :login AND motDePasse = :motDePasse");
            $request->execute();
            return $request->fetch();
        } else {
            throw new Exception("La requête n'est pas valide.");
        }
    }

    /**
     * Exécute une requête SQL particulière, et retourne son résultat.
     * 
     * @param string $requete_sql : la requête SQL
     * @return array : le résultat, ou false, si la requête ne retourne rien.
     */
    public function executerRequete(string $requete_sql) {
        if(isset($requete_sql) && $requete_sql != null) {

            $requete = $this->bdd->prepare($requete_sql);
            $requete->execute();

            $requete->setFetchMode(PDO::FETCH_ASSOC);
            $donnees = $requete->fetchAll();
            return $donnees;
        } else {
            throw new Exception("La requête n'est pas valide.");
        }

    }

    public function compteResultat(string $requete_sql) {
        if(isset($requete_sql) && $requete_sql != null) {
            return $this->bdd->query($requete_sql)->fetchColumn();
        } else {
            throw new Exception("La requête n'est pas valide.");
        }

    }


    




    

    
}
