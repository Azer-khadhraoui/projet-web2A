<?php

class Pannier
{
    private $id_prod;
    private $qt_prod;
    private $mode_paiement;
    private $statut_pannier;

    public function __construct($id_prod, $qt_prod, $mode_paiement, $statut_pannier)
    {
        $this->id_prod = $id_prod;
        $this->qt_prod = $qt_prod;
        $this->mode_paiement = $mode_paiement;
        $this->statut_pannier = $statut_pannier;
    }

    public function getIdProd()
    {
        return $this->id_prod;
    }

    public function getQtProd()
    {
        return $this->qt_prod;
    }
    

    public function getModePaiement()
    {
        return $this->mode_paiement;
    }

    public function getStatutPannier()
    {
        return $this->statut_pannier;
    }
}

?>

