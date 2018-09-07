<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DemandesConducteursRepository")
 */
class DemandesConducteurs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Conducteur", inversedBy="demandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $conducteur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_demande;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datej1;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datej2;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datej3;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datej4;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datej5;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datej6;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $travailHorsTachy;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $repasMidi;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $repasSoir;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observationsConducteur;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $demandeStatus;

    public function getId()
    {
        return $this->id;
    }

    public function getConducteur(): ?Conducteur
    {
        return $this->conducteur;
    }

    public function setConducteur(?Conducteur $conducteur): self
    {
        $this->conducteur = $conducteur;

        return $this;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->date_demande;
    }

    public function setDateDemande(\DateTimeInterface $date_demande): self
    {
        $this->date_demande = $date_demande;

        return $this;
    }

    public function getDatej1(): ?\DateTimeInterface
    {
        return $this->datej1;
    }

    public function setDatej1(?\DateTimeInterface $datej1): self
    {
        $this->datej1 = $datej1;

        return $this;
    }

    public function getDatej2(): ?\DateTimeInterface
    {
        return $this->datej2;
    }

    public function setDatej2(?\DateTimeInterface $datej2): self
    {
        $this->datej2 = $datej2;

        return $this;
    }

    public function getDatej3(): ?\DateTimeInterface
    {
        return $this->datej3;
    }

    public function setDatej3(?\DateTimeInterface $datej3): self
    {
        $this->datej3 = $datej3;

        return $this;
    }

    public function getDatej4(): ?\DateTimeInterface
    {
        return $this->datej4;
    }

    public function setDatej4(?\DateTimeInterface $datej4): self
    {
        $this->datej4 = $datej4;

        return $this;
    }

    public function getDatej5(): ?\DateTimeInterface
    {
        return $this->datej5;
    }

    public function setDatej5(?\DateTimeInterface $datej5): self
    {
        $this->datej5 = $datej5;

        return $this;
    }

    public function getDatej6(): ?\DateTimeInterface
    {
        return $this->datej6;
    }

    public function setDatej6(?\DateTimeInterface $datej6): self
    {
        $this->datej6 = $datej6;

        return $this;
    }

    public function getTravailHorsTachy(): ?string
    {
        return $this->travailHorsTachy;
    }

    public function setTravailHorsTachy(?string $travailHorsTachy): self
    {
        $this->travailHorsTachy = $travailHorsTachy;

        return $this;
    }

    public function getRepasMidi(): ?bool
    {
        return $this->repasMidi;
    }

    public function setRepasMidi(?bool $repasMidi): self
    {
        $this->repasMidi = $repasMidi;

        return $this;
    }

    public function getRepasSoir(): ?bool
    {
        return $this->repasSoir;
    }

    public function setRepasSoir(?bool $repasSoir): self
    {
        $this->repasSoir = $repasSoir;

        return $this;
    }

    public function getObservationsConducteur(): ?string
    {
        return $this->observationsConducteur;
    }

    public function setObservationsConducteur(?string $observationsConducteur): self
    {
        $this->observationsConducteur = $observationsConducteur;

        return $this;
    }

    public function getDemandeStatus(): ?bool
    {
        return $this->demandeStatus;
    }

    public function setDemandeStatus(?bool $demandeStatus): self
    {
        $this->demandeStatus = $demandeStatus;

        return $this;
    }
}
