<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RapportHebdoRepository")
 */
class RapportHebdo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Conducteur", inversedBy="rapportHebdos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rapportConducteur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateReclame;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $travailHorsTachy;

    /**
     * @ORM\Column(type="boolean")
     */
    private $repasMidi;

    /**
     * @ORM\Column(name="repasSoir",type="boolean")
     */
    private $repasSoir;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observationsRapport;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statuDemande;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reponseDemande;

    /**
     * @ORM\Column(type="integer")
     */
    private $compteurRapport;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $societeApsFle;


    /**
     * @ORM\Column(name="heureRapport", type="string", length=4, nullable = false)
     */
    private $heureRapport;


    /**
     * @ORM\Column(name="minRapport", type="string", length =4, nullable = true)
     */
    private $minRapport;


    /**
     * @ORM\Column(name="heureFinRapport", type="string", length =4, nullable = true)
     */
    private $heureFinRapport;


    /**
     * @ORM\Column(name="minFinRapport", type="string", length =4, nullable = true)
     */
    private $minFinRapport;


    /**
     * @ORM\Column(name="nbTotalRapportHebdo", type="integer", nullable = false)
     */
    private $nbTotalRapportHebdo;


    /**
     * @ORM\Column(name="rapportVudirection", type="boolean", nullable=true)
     */
    private $rapportVuDirection;

    /**
     * @ORM\Column(name="rapportVuRh", type="boolean", nullable=true)
     */
    private $rapportVuRh;



    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->rapportVuDirection = false;
        $this->rapportVuRh = false;
    }

    public function getId()
    {
        return $this->id;
    }


    public function getRapportConducteur(): ?Conducteur
    {
        return $this->rapportConducteur;
    }

    public function setRapportConducteur(?Conducteur $rapportConducteur): self
    {
        $this->rapportConducteur = $rapportConducteur;

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

    public function getDateReclame(): ?\DateTimeInterface
    {
        return $this->dateReclame;
    }

    public function setDateReclame(\DateTimeInterface $dateReclame): self
    {
        $this->dateReclame = $dateReclame;

        return $this;
    }

    public function getTravailHorsTachy(): ?string
    {
        return $this->travailHorsTachy;
    }

    public function setTravailHorsTachy(string $travailHorsTachy): self
    {
        $this->travailHorsTachy = $travailHorsTachy;

        return $this;
    }

    public function getRepasMidi(): ?bool
    {
        return $this->repasMidi;
    }

    public function setRepasMidi(bool $repasMidi): self
    {
        $this->repasMidi = $repasMidi;

        return $this;
    }

    public function getRepasSoir(): ?bool
    {
        return $this->repasSoir;
    }

    public function setRepasSoir(bool $repasSoir): self
    {
        $this->repasSoir = $repasSoir;

        return $this;
    }

    public function getObservationsRapport(): ?string
    {
        return $this->observationsRapport;
    }

    public function setObservationsRapport(?string $observationsRapport): self
    {
        $this->observationsRapport = $observationsRapport;

        return $this;
    }

    public function getStatuDemande(): ?bool
    {
        return $this->statuDemande;
    }

    public function setStatuDemande(bool $statuDemande): self
    {
        $this->statuDemande = $statuDemande;

        return $this;
    }

    public function getReponseDemande(): ?string
    {
        return $this->reponseDemande;
    }

    public function setReponseDemande(?string $reponseDemande): self
    {
        $this->reponseDemande = $reponseDemande;

        return $this;
    }

    public function getCompteurRapport(): ?int
    {
        return $this->compteurRapport;
    }

    public function setCompteurRapport(int $compteurRapport): self
    {
        $this->compteurRapport = $compteurRapport;

        return $this;
    }

    public function getSocieteApsFle(): ?string
    {
        return $this->societeApsFle;
    }

    public function setSocieteApsFle(?string $societeApsFle): self
    {
        $this->societeApsFle = $societeApsFle;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeureRapport()
    {
        return $this->heureRapport;
    }

    /**
     * @param mixed $heureRapport
     */
    public function setHeureRapport($heureRapport): void
    {
        $this->heureRapport = $heureRapport;
    }

    /**
     * @return mixed
     */
    public function getMinRapport()
    {
        return $this->minRapport;
    }

    /**
     * @param mixed $minRapport
     */
    public function setMinRapport($minRapport): void
    {
        $this->minRapport = $minRapport;
    }

    /**
     * @return mixed
     */
    public function getHeureFinRapport()
    {
        return $this->heureFinRapport;
    }

    /**
     * @param mixed $heureFinRapport
     */
    public function setHeureFinRapport($heureFinRapport): void
    {
        $this->heureFinRapport = $heureFinRapport;
    }

    /**
     * @return mixed
     */
    public function getMinFinRapport()
    {
        return $this->minFinRapport;
    }

    /**
     * @param mixed $minFinRapport
     */
    public function setMinFinRapport($minFinRapport): void
    {
        $this->minFinRapport = $minFinRapport;
    }

    /**
     * @return mixed
     */
    public function getNbTotalRapportHebdo()
    {
        return $this->nbTotalRapportHebdo;
    }

    /**
     * @param mixed $nbTotalRapportHebdo
     */
    public function setNbTotalRapportHebdo($nbTotalRapportHebdo): void
    {
        $this->nbTotalRapportHebdo = $nbTotalRapportHebdo;
    }

    /**
     * @return mixed
     */
    public function getRapportVuDirection()
    {
        return $this->rapportVuDirection;
    }

    /**
     * @param mixed $rapportVuDirection
     */
    public function setRapportVuDirection($rapportVuDirection): void
    {
        $this->rapportVuDirection = $rapportVuDirection;
    }

    /**
     * @return mixed
     */
    public function getRapportVuRh()
    {
        return $this->rapportVuRh;
    }

    /**
     * @param mixed $rapportVuRh
     */
    public function setRapportVuRh($rapportVuRh): void
    {
        $this->rapportVuRh = $rapportVuRh;
    }






}
