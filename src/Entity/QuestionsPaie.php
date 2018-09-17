<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionsPaieRepository")
 */
class QuestionsPaie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Conducteur", inversedBy="questionsPaies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $questionsPaieConducteur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDemande;

    /**
     * @ORM\Column(type="text")
     */
    private $objetDemande;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statueDemande;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $statueDemandeDirection;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reponseDirection;


    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->statueDemande = 1;
        $this->statueDemandeDirection = 1;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getQuestionsPaieConducteur(): ?Conducteur
    {
        return $this->questionsPaieConducteur;
    }

    public function setQuestionsPaieConducteur(?Conducteur $questionsPaieConducteur): self
    {
        $this->questionsPaieConducteur = $questionsPaieConducteur;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->dateDemande;
    }

    public function setDateDemande(\DateTimeInterface $dateDemande): self
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    public function getObjetDemande(): ?string
    {
        return $this->objetDemande;
    }

    public function setObjetDemande(string $objetDemande): self
    {
        $this->objetDemande = $objetDemande;

        return $this;
    }

    public function getStatueDemande(): ?bool
    {
        return $this->statueDemande;
    }

    public function setStatueDemande(bool $statueDemande): self
    {
        $this->statueDemande = $statueDemande;

        return $this;
    }

    public function getStatueDemandeDirection(): ?bool
    {
        return $this->statueDemandeDirection;
    }

    public function setStatueDemandeDirection(?bool $statueDemandeDirection): self
    {
        $this->statueDemandeDirection = $statueDemandeDirection;

        return $this;
    }

    public function getReponseDirection(): ?string
    {
        return $this->reponseDirection;
    }

    public function setReponseDirection(?string $reponseDirection): self
    {
        $this->reponseDirection = $reponseDirection;

        return $this;
    }
}
