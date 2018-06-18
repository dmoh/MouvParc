<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarrosserieRepository")
 */
class Carrosserie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $auteur;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $etat_car;

    /**
     * @ORM\Column(type="text")
     */
    private $desc_accro;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $suite_donnee;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_signalement;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_remise_etat;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $duree_incident;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="carrosserie", orphanRemoval=true, cascade={"persist", "remove", "merge"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $images;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cars", inversedBy="carrosseries")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $car;


    public function __construct()
    {
        $this->date = new  \DateTime();
        $this->car = new ArrayCollection();
    }


    public function getId()
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getEtatCar(): ?string
    {
        return $this->etat_car;
    }

    public function setEtatCar(string $etat_car): self
    {
        $this->etat_car = $etat_car;

        return $this;
    }

    public function getDescAccro(): ?string
    {
        return $this->desc_accro;
    }

    public function setDescAccro(string $desc_accro): self
    {
        $this->desc_accro = $desc_accro;

        return $this;
    }

    public function getSuiteDonnee(): ?string
    {
        return $this->suite_donnee;
    }

    public function setSuiteDonnee(?string $suite_donnee): self
    {
        $this->suite_donnee = $suite_donnee;

        return $this;
    }

    public function getDateSignalement(): ?\DateTimeInterface
    {
        return $this->date_signalement;
    }

    public function setDateSignalement(\DateTimeInterface $date_signalement): self
    {
        $this->date_signalement = $date_signalement;

        return $this;
    }

    public function getDateRemiseEtat(): ?\DateTimeInterface
    {
        return $this->date_remise_etat;
    }

    public function setDateRemiseEtat(?\DateTimeInterface $date_remise_etat): self
    {
        $this->date_remise_etat = $date_remise_etat;

        return $this;
    }

    public function getDureeIncident(): ?\DateTimeInterface
    {
        return $this->duree_incident;
    }

    public function setDureeIncident(?\DateTimeInterface $duree_incident): self
    {
        $this->duree_incident = $duree_incident;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param mixed $images
     */
    public function setImages($images): void
    {
        $this->images = $images;
    }

    /**
     * @return mixed
     */
    public function getCar(): Cars
    {
        return $this->car;
    }

    /**
     * @param mixed $car
     */
    public function setCar(Cars $cars = null): void
    {
        $this->car = $cars;
    }





}
