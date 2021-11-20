<?php
namespace ch\comem;

use Exception;

class Frigo {
    private $liste_boissons = array();

    public function __construct(Database $db) {
        if(isset($db)) {
            $boissons_SQL = $db->retourneTable('boissons');

            foreach($boissons_SQL as $boisson) {
                array_push($this->liste_boissons, new Boisson($db, $boisson['id']));
            }
        } else {
            throw new Exception("La base de donnée n'existe pas.");
        }
    }

    public function imprimeListeBoissons(){
        //imprime la liste 
        echo '<select name="boissons" id="boissons">';
        foreach ($this->liste_boissons as $boisson ){
            echo '<option value="'.$boisson->rendId() .'">'.$boisson->rendNom() .'</option>';
        }
        echo '</select>';
    }

    public function retrouveNom($no) {
        return $this->nom;
    }
}