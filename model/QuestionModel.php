<?php
class QuestionModel
{
    private ?int $id_quest = null;
    private ?string $titre_quest = null;
    private ?string $contenue = null;
    private ?int $id_user = null;
    private ?string $date = null;

    public function __construct($titre_quest, $contenue, $user_id, $id = null, $date = null)
    {
        $this->id_quest = $id;
        $this->titre_quest = $titre_quest;
        $this->contenue = $contenue;
        $this->id_user = (int)$user_id;
        $this->date = $date;

    }

    public function getIdQuestion()
    {
        return $this->id_quest;
    }

    public function getTitreQuest()
    {
        return $this->titre_quest;
    }

    public function setTitreQuest($titre_quest)
    {
        $this->titre_quest = $titre_quest;
        return $this;
    }

    public function getContenue()
    {
        return $this->contenue;
    }

    public function setContenue($contenue)
    {
        $this->contenue = $contenue;
        return $this;
    }

    public function getUserId()
    {
        return $this->id_user;
    }

    public function setUserId($user_id)
    {
        $this->id_user = (int)$user_id;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }
}
?>
