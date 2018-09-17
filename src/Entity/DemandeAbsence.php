<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DemandeAbsenceRepository")
 */
class DemandeAbsence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Conducteur", inversedBy="demandeAbsences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $demandeAbsenceConducteur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDemande;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statueDemande;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $heureDebutAbs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $heureFinAbs;

    /**
     * @ORM\Column(type="text")
     */
    private $motifConducteur;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaireDirection;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $statueDemandeDirection;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reponseDirection;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cloturer;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $reponseExploit;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaireExploit;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $statueDemandeExploit;



    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->statueDemande = 1;
        $this->statueDemandeDirection = 1;
        $this->cloturer = 0;
        $this->statueDemandeExploit = 1;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDemandeAbsenceConducteur(): ?Conducteur
    {
        return $this->demandeAbsenceConducteur;
    }

    public function setDemandeAbsenceConducteur(?Conducteur $demandeAbsenceConducteur): self
    {
        $this->demandeAbsenceConducteur = $demandeAbsenceConducteur;

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

    public function getStatueDemande(): ?bool
    {
        return $this->statueDemande;
    }

    public function setStatueDemande(bool $statueDemande): self
    {
        $this->statueDemande = $statueDemande;

        return $this;
    }

    public function getHeureDebutAbs(): ?string
    {
        return $this->heureDebutAbs;
    }

    public function setHeureDebutAbs(string $heureDebutAbs): self
    {
        $this->heureDebutAbs = $heureDebutAbs;

        return $this;
    }

    public function getHeureFinAbs(): ?string
    {
        return $this->heureFinAbs;
    }

    public function setHeureFinAbs(string $heureFinAbs): self
    {
        $this->heureFinAbs = $heureFinAbs;

        return $this;
    }

    public function getMotifConducteur(): ?string
    {
        return $this->motifConducteur;
    }

    public function setMotifConducteur(string $motifConducteur): self
    {
        $this->motifConducteur = $motifConducteur;

        return $this;
    }

    public function getCommentaireDirection(): ?string
    {
        return $this->commentaireDirection;
    }

    public function setCommentaireDirection(?string $commentaireDirection): self
    {
        $this->commentaireDirection = $commentaireDirection;

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

    public function getCloturer(): ?bool
    {
        return $this->cloturer;
    }

    public function setCloturer(bool $cloturer): self
    {
        $this->cloturer = $cloturer;

        return $this;
    }

    public function getReponseExploit(): ?bool
    {
        return $this->reponseExploit;
    }

    public function setReponseExploit(?bool $reponseExploit): self
    {
        $this->reponseExploit = $reponseExploit;

        return $this;
    }

    public function getCommentaireExploit(): ?string
    {
        return $this->commentaireExploit;
    }

    public function setCommentaireExploit(?string $commentaireExploit): self
    {
        $this->commentaireExploit = $commentaireExploit;

        return $this;
    }

    public function getStatueDemandeExploit(): ?bool
    {
        return $this->statueDemandeExploit;
    }

    public function setStatueDemandeExploit(?bool $statueDemandeExploit): self
    {
        $this->statueDemandeExploit = $statueDemandeExploit;

        return $this;
    }
}
