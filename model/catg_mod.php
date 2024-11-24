<?php
class categories_mang{
    private ?int $id_categorie;
    private ?string $nom_categorie;

    // Constructor
    public function __construct(
        ?int $id_categorie = null,
        ?string $nom_categorie = null){
            $this->id_categorie = $id_categorie;
            $this->nom_categorie = $nom_categorie;
        }
    // Getters and Setters

    public function getId(): ?int {
        return $this->id_categorie;
    }

    public function setId(?int $id_categorie): void {
        $this->id_categorie = $id_categorie;
    }
    public function getNomcat(): ?string {
        return $this->nom_categorie;
    }

    public function setNomcat(?string $nom_categorie): void {
        $this->nom_categorie = $nom_categorie;
    }
}




