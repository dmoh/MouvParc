<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DemandeAccompteRepository")
 */
class DemandeAccompte
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Conducteur", inversedBy="demandeAccomptes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $demandeAccompteConducteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime")
     */
    private $dateDemande;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer")
     */
    private $montantAccompte;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statueDemande;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $obsAccompteConducteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reponseDirection;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $obsDirection;

    public function getId()
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
    }

    public function getDemandeAccompteConducteur(): ?Conducteur
    {
        return $this->demandeAccompteConducteur;
    }

    public function setDemandeAccompteConducteur(?Conducteur $demandeAccompteConducteur): self
    {
        $this->demandeAccompteConducteur = $demandeAccompteConducteur;

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

    public function getMontantAccompte(): ?int
    {
        return $this->montantAccompte;
    }

    public function setMontantAccompte(int $montantAccompte): self
    {
        $this->montantAccompte = $montantAccompte;

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

    public function getObsAccompteConducteur(): ?string
    {
        return $this->obsAccompteConducteur;
    }

    public function setObsAccompteConducteur(?string $obsAccompteConducteur): self
    {
        $this->obsAccompteConducteur = $obsAccompteConducteur;

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

    public function getObsDirection(): ?string
    {
        return $this->obsDirection;
    }

    public function setObsDirection(?string $obsDirection): self
    {
        $this->obsDirection = $obsDirection;

        return $this;
    }
}
