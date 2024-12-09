<?php

class Commande
{
    private $id_cmd;         // ID de la commande (auto-incrémenté)
    private $date_cmd;       // Date de la commande
    private $stat_cmd;       // Statut de la commande
    private $adress_cmd;     // Adresse de la commande
    private $desc_cmd;       // Description de la commande

    public function __construct($date_cmd, $stat_cmd, $adress_cmd, $desc_cmd)
    {
        // L'ID est auto-géré par la base de données ou un gestionnaire
        $this->date_cmd = $date_cmd;
        $this->stat_cmd = $stat_cmd;
        $this->adress_cmd = $adress_cmd;
        $this->desc_cmd = $desc_cmd;
    }

    // Getter pour ID de commande
    public function getIdCmd()
    {
        return $this->id_cmd;
    }

    // Getter pour date de commande
    public function getDateCmd()
    {
        return $this->date_cmd;
    }

    // Getter pour statut de commande
    public function getStatCmd()
    {
        return $this->stat_cmd;
    }

    // Getter pour adresse de commande
    public function getAdressCmd()
    {
        return $this->adress_cmd;
    }

    // Getter pour description de commande
    public function getDescCmd()
    {
        return $this->desc_cmd;
    }

    // Setter pour mettre à jour la date de commande
    public function setDateCmd($date_cmd)
    {
        $this->date_cmd = $date_cmd;
    }

    // Setter pour mettre à jour le statut de commande
    public function setStatCmd($stat_cmd)
    {
        $this->stat_cmd = $stat_cmd;
    }

    // Setter pour mettre à jour l'adresse de commande
    public function setAdressCmd($adress_cmd)
    {
        $this->adress_cmd = $adress_cmd;
    }

    // Setter pour mettre à jour la description de commande
    public function setDescCmd($desc_cmd)
    {
        $this->desc_cmd = $desc_cmd;
    }
}

?>
