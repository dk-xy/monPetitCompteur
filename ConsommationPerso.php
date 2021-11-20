<?php
namespace ch\comem;

use \Exception;

class ConsommationPerso {
    private $utilisateur_id;
    private $conso;
    private $db;

    /**
     * Instancie la liaison entre la classe et la base de données.
     * 
     * @param Database $db : la connection à la BDD, instanciée au préalable.
     * @return void
     */
    public function __construct(Database $db, $u_id)
    {
        if(isset($db)) {
            $this->utilisateur_id = $u_id;
            $this->conso = array();
            $this->db = $db;

            // Ajouter toutes les boissons que l'utilisateur à consommer
            $consommations_SQL = $db->executerRequete("SELECT * FROM consommation WHERE utilisateur_id = $this->utilisateur_id");
            
            foreach ($consommations_SQL as $ligneTableConso) {
                if($ligneTableConso['quantité'] > 1) {
                    for ($i=1; $i <= $ligneTableConso['quantité'] ; $i++) { 
                        // Ajoute N fois la boisson dans le tableau, si la quantité de boissons est supérieure à 1
                        array_push($this->conso, new BoissonConsommee($db, $ligneTableConso['boissons_id'], $ligneTableConso['date']));
                    }
                } else {
                    array_push($this->conso, new BoissonConsommee($db, $ligneTableConso['boissons_id'], $ligneTableConso['date']));
                }
            }
        } else {
            throw new Exception("La base de donnée n'existe pas.");
        }
    }

    /**
     * @param int  $u_id id de l'utilisateur
     * @param int  $b_id id de la boisson
     * @param date $cur_date date actuelle
     * @param int  $nb nombre de boissons consommées
     * 
     * @return null mais ajoute la boissons à la table consommation
     */
    public function consommeBoisson($u_id,$b_id,$cur_date,$nb){
        if($nb>0){
			$requete_insert = "INSERT INTO consommation VALUES('".$u_id."','".$b_id."','".$cur_date."','".$nb."');";
			$this->db->executerRequete($requete_insert); 
        }
    }

    //fonction d'affichage de consommation
    public function consoPerso($u_id){
        $req_day ="SELECT utilisateur_id,boissons_id FROM consommation WHERE utilisateur_id like".$u_id.";";
		$this->db->executerRequete($req_day);

    }

    /** 
     *  @param Boisson boisson a ajouter au tableau de consommation
     *  @return null mais ajoute la boisson au tableau de boisson de consommation
     * 
     */
    public function ajouteConso($bC){
        array_push($this->conso,$bC);
        }

            /**
     * @param array  $conso retour de la consommation par la requete
     * 
     * @return null mais affiche le tableau des boisson du type et de la periodicité séléctionnée
     */
    public function afficheTableConsommation(array $conso){
        echo '<table style="width:100%">;
        <th class="rowHeader">Boisson</th>
        <th class="rowHeader">Qt.</th>
        <th class="rowHeader">Date de consommation</th>';
        $i = 0;
        foreach ($conso as $req) {
            if($i%2 != 0){
                $styleRow ="class='rowWhite'";
            } else {
                $styleRow ="class='rowBlue'";
            }
            echo '<tr>';
            echo '<td ';echo $styleRow; echo'>'; echo $req['nom'];echo'</td>';
            echo '<td ';echo $styleRow; echo'>'; echo $req['quantité'];echo'</td>';
            echo '<td ';echo $styleRow; echo'>'; echo $req['date'];echo'</td>';
            echo '</tr>';
            // echo "<pre>";
            // print_r($req);
            // echo "</pre>";
            $i++;
            
        }
        echo '</table>';	
}

    /**
     * @param int  $count retour du comptage de la requete
     * @param char type le type de la boisson
     * 
     * @return null mais affiche le nombre de boissons du type et de la periodicité séléctionnée
     */
        public function affichageCompteur($count, $type){
            
                        echo '<div class=affichageConsoPerso>';
                            echo 'Vous avez bu: ';echo '<b>'.$count.'</b>'; echo' '; 
                                switch($type){
                                    case 'e':
                                        echo 'boissons hydratrantes';
                                        break;
                                    case 's':
                                        echo 'boissons sucrées';
                                        break;
                                    case 'a':
                                        echo 'boissons alcoolisées';
                                        break;
                                    case 't':
                                        echo 'boissons de tous types';
                                        break;
                            }
                            
                        }




        /**
         * @param array $conso tableau de consommation fait par la requete
         * @param char $typeAffiche type souhaité à afficher
         * 
         * @return null mais affiche la consommation des divers éléments.
         */
        public function calculeConsommation(array $conso, $typeAffiche){
            $totalAlcool=0.0;
            $totalSucre=0.0;
            $totalEau=0.0;
            $i=0;
            for ($i = 0; $i <= count($conso)-1; $i++){
            //foreach($conso as $cons){  
                $type = $conso[$i]['type'];
                $boisson = $conso[$i]['boissons_id'];
                switch($type){
                    case 't':
                        break;
                    case 'e':
                        $totalEau = $totalEau+0.33;
                        break;
                    case 'a':
                        $totalAlcool = $totalAlcool+0.20;
                        break;
                    case 's':
                        switch($boisson){
                            case 3:
                                $totalSucre=$totalSucre+36.3;
                                break;
                            case 4:
                                $totalSucre=$totalSucre+14;
                                break;
                        }
                        break;
                }
            }
            //boucle foreach pour afficher les choses selon le type choisi
            echo '<br>Vous avez consommé: <br>';
            switch($typeAffiche){
                case 't':
                    echo '<b>'.$totalEau.'</b> litres d\'eau <br>';
                    echo '<b>'.$totalSucre.'</b> grammes de sucre <br>';
                    echo '<b>'.$totalAlcool.'</b> grammes d\'alcool <br>';
                    break;
                case 'e':
                    echo $totalEau.' litres d\'eau';
                    break;
                case 's':
                    echo $totalSucre.' grammes de sucre';
                    break;
                case 'a':
                    echo $totalAlcool.' grammes d\'alcool';
                    break;
                

            }
            //ferme la div du compteur de la fonction au dessus
            echo '</div>';
            }
        }

    
        
    


