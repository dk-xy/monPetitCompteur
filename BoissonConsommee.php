<?php
namespace ch\comem;
require_once 'Boisson.php';
use ch\comem\Boisson as Boisson;

class BoissonConsommee extends Boisson {
    private $date;

    public function __construct(Database $db, $b_id, $date)
    {
        parent::__construct($db, $b_id);
        $this->date = $date;
    }

    public function rendDate() {
        return $this->date;
    }

}