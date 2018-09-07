<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DemandeCongesRepository")
 */
class DemandeConges
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Conducteur", inversedBy="demandeConges")
     * @ORM\JoinColumn(nullable=false)
     */
    private $demandeCongeConducteur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDemande;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeDeConge;

    /**
     * @ORM\Column(type="datetime")
     */
    private $duDateConge;

    /**
     * @ORM\Column(type="datetime")
     */
    private $auDateConge;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statueDemande;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reponseDirection;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $accordRefusDirection;

    /**
     * @ORM\Column(type="boolean")
     */
    private $demandeCloturer;


    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->demandeCloturer = false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDemandeCongeConducteur(): ?Conducteur
    {
        return $this->demandeCongeConducteur;
    }

    public function setDemandeCongeConducteur(?Conducteur $demandeCongeConducteur): self
    {
        $this->demandeCongeConducteur = $demandeCongeConducteur;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTypeDeConge(): ?string
    {
        return $this->typeDeConge;
    }

    public function setTypeDeConge(string $typeDeConge): self
    {
        $this->typeDeConge = $typeDeConge;

        return $this;
    }

    public function getDuDateConge(): ?\DateTimeInterface
    {
        return $this->duDateConge;
    }

    public function setDuDateConge(\DateTimeInterface $duDateConge): self
    {
        $this->duDateConge = $duDateConge;

        return $this;
    }

    public function getAuDateConge(): ?\DateTimeInterface
    {
        return $this->auDateConge;
    }

    public function setAuDateConge(\DateTimeInterface $auDateConge): self
    {
        $this->auDateConge = $auDateConge;

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

    public function getReponseDirection(): ?string
    {
        return $this->reponseDirection;
    }

    public function setReponseDirection(?string $reponseDirection): self
    {
        $this->reponseDirection = $reponseDirection;

        return $this;
    }

    public function getAccordRefusDirection(): ?string
    {
        return $this->accordRefusDirection;
    }

    public function setAccordRefusDirection(?string $accordRefusDirection): self
    {
        $this->accordRefusDirection = $accordRefusDirection;

        return $this;
    }

    public function getDemandeCloturer(): ?bool
    {
        return $this->demandeCloturer;
    }

    public function setDemandeCloturer(bool $demandeCloturer): self
    {
        $this->demandeCloturer = $demandeCloturer;

        return $this;
    }
}
