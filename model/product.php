<?php

class TravelOffer {
    private ?int $id;
    private ?string $nom_prod;
    private ?string $description;
    private ?float $prix;
    private ?int $qte;
    private ?string $url_img;
    private ?int $cat;

    // Constructor
    public function __construct(
        ?int $id = null,
        ?string $nom_prod = null,
        ?string $description = null,
        ?float $prix = null,
        ?int $qte = null,
        ?string $url_img = null,
        ?int $cat = null
    ) {
        $this->id = $id;
        $this->nom_prod = $nom_prod;
        $this->description = $description;
        $this->prix = $prix;
        $this->qte = $qte;
        $this->url_img = $url_img;
        $this->cat = $cat;
    }

    // Getters and Setters

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getNomProd(): ?string {
        return $this->nom_prod;
    }

    public function setNomProd(?string $nom_prod): void {
        $this->nom_prod = $nom_prod;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    public function getPrix(): ?float {
        return $this->prix;
    }

    public function setPrix(?float $prix): void {
        $this->prix = $prix;
    }

    public function getQte(): ?int {
        return $this->qte;
    }

    public function setQte(?int $qte): void {
        $this->qte = $qte;
    }

    public function getUrlImg(): ?string {
        return $this->url_img;
    }

    public function setUrlImg(?string $url_img): void {
        $this->url_img = $url_img;
    }

    public function getCat(): ?int {
        return $this->cat;
    }

    public function setCat(?int $cat): void {
        $this->cat = $cat;
    }
}

?>
