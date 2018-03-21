<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="panne")
 * @ORM\Entity(repositoryClass="App\Repository\PanneRepository")
 */
class Panne
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="date", type="date")
     */
    protected $date;

    /**
     * @ORM\Column(name="auteur", type="string", length=100, nullable=true)
     */
    protected $auteur;

    /**
     * @ORM\Column(name="etat_car", type="string", length=100)
     */
    protected $etat_car;

    /**
     * @ORM\Column(name="desc_panne", type="text", nullable=true)
     */
    protected $desc_panne;


    /**
     * @ORM\Column(name="suites_donnes", type="text", nullable=true)
     */
    protected $suites_donnes;

    /**
     * @ORM\Column(name="date_deb_panne", type="datetime", nullable=true)
     */
    protected $date_deb_panne;

    /**
     * @ORM\Column(name="date_fin_panne", type="datetime", nullable=true)
     */
    protected $date_fin_panne;

    /**
     * @ORM\Column(name="date_deb_panne_manuel", type="datetime", nullable=true)
     */
    protected $date_deb_panne_manuel;


    /**
     * @ORM\Column(name="date_fin_panne_manuel", type="datetime", nullable=true)
     */
    protected $date_fin_panne_manuel;


    /**
     * @ORM\Column(name="duree_panne", type="string", nullable=true)
     */
    protected $duree_panne;

    /**
     * @ORM\Column(name="date_prev", type="datetime", nullable=true)
     */
    protected $date_prev;


    /**
     * @ORM\Column(name="date_effective", type="datetime", nullable=true)
     */
    protected $date_effective;

    /**
     * @ORM\Column(name="duree_panne_prev", type="string", nullable=true)
     */
    protected $duree_panne_prev;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cars", inversedBy="pannes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $cars;

    /**
     * @ORM\Column(name="desc_panne_ano_p", type="text", nullable=true)
     */
    protected $desc_panne_ano_p;

    /**
     * @ORM\Column(name="nature_panne", type="text", nullable=true)
     */
    protected $nature_panne;

    /**
     * @ORM\Column(name="garantie", type="string", nullable=true)
     */
    protected $garantie;


    /**
     * @return mixed
     */
    public function getCars(): Cars
    {
        return $this->cars;
    }

    /**
     * @param mixed $cars
     */
    public function setCars(Cars $cars = null): void
    {
        $this->cars = $cars;
    }



    public function __construct()
    {
        $this->date = new  \DateTime();
        $this->cars = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getEtatCar()
    {
        return $this->etat_car;
    }

    /**
     * @param mixed $etat_car
     */
    public function setEtatCar($etat_car): void
    {
        $this->etat_car = $etat_car;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * @param mixed $auteur
     */
    public function setAuteur($auteur): void
    {
        $this->auteur = $auteur;
    }

    /**
     * @return mixed
     */
    public function getDescPanne()
    {
        return $this->desc_panne;
    }

    /**
     * @param mixed $desc_panne
     */
    public function setDescPanne($desc_panne): void
    {
        $this->desc_panne = $desc_panne;
    }

    /**
     * @return mixed
     */
    public function getSuitesDonnes()
    {
        return $this->suites_donnes;
    }

    /**
     * @param mixed $suites_donnes
     */
    public function setSuitesDonnes($suites_donnes): void
    {
        $this->suites_donnes = $suites_donnes;
    }

    /**
     * @return mixed
     */
    public function getDateDebPanne()
    {
        return $this->date_deb_panne;
    }

    /**
     * @param mixed $date_deb_panne
     */
    public function setDateDebPanne($date_deb_panne): void
    {
        $this->date_deb_panne = $date_deb_panne;
    }

    /**
     * @param mixed $duree_panne
     */
    public function setDureePanne($duree_panne): void
    {
        $this->duree_panne = $duree_panne;
    }

    /**
     * @return mixed
     */
    public function getDureePanne()
    {
        return $this->duree_panne;
    }

    /**
     * @return mixed
     */
    public function getDateFinPanne()
    {
        return $this->date_fin_panne;
    }

    /**
     * @param mixed $date_fin_panne
     */
    public function setDateFinPanne($date_fin_panne): void
    {
        $this->date_fin_panne = $date_fin_panne;
    }

    /**
     * @param mixed $date_prev
     */
    public function setDatePrev($date_prev): void
    {
        $this->date_prev = $date_prev;
    }

    /**
     * @return mixed
     */
    public function getDatePrev()
    {
        return $this->date_prev;
    }


    /**
     * @return mixed
     */
    public function getDateEffective()
    {
        return $this->date_effective;
    }

    /**
     * @param mixed $date_effective
     */
    public function setDateEffective($date_effective): void
    {
        $this->date_effective = $date_effective;
    }

    /**
     * @return mixed
     */
    public function getDureePannePrev()
    {
        return $this->duree_panne_prev;
    }

    /**
     * @param mixed $duree_panne_prev
     */
    public function setDureePannePrev($duree_panne_prev): void
    {
        $this->duree_panne_prev = $duree_panne_prev;
    }

    /**
     * @param mixed $desc_panne_ano
     */
    public function setDescPanneAnoP($desc_panne_ano): void
    {
        $this->desc_panne_ano = $desc_panne_ano;
    }

    /**
     * @return mixed
     */
    public function getDescPanneAnoP()
    {
        return $this->desc_panne_ano_p;
    }

    /**
     * @return mixed
     */
    public function getNaturePanne()
    {
        return $this->nature_panne;
    }

    /**
     * @param mixed $nature_panne
     */
    public function setNaturePanne($nature_panne): void
    {
        $this->nature_panne = $nature_panne;
    }

    /**
     * @return mixed
     */
    public function getDateDebPanneManuel()
    {
        return $this->date_deb_panne_manuel;
    }

    /**
     * @return mixed
     */
    public function getDateFinPanneManuel()
    {
        return $this->date_fin_panne_manuel;
    }

    /**
     * @param mixed $date_deb_panne_manuel
     */
    public function setDateDebPanneManuel($date_deb_panne_manuel): void
    {
        $this->date_deb_panne_manuel = $date_deb_panne_manuel;
    }

    /**
     * @param mixed $date_fin_panne_manuel
     */
    public function setDateFinPanneManuel($date_fin_panne_manuel): void
    {
        $this->date_fin_panne_manuel = $date_fin_panne_manuel;
    }

    /**
     * @return mixed
     */
    public function getGarantie()
    {
        return $this->garantie;
    }

    /**
     * @param mixed $garantie
     */
    public function setGarantie($garantie): void
    {
        $this->garantie = $garantie;
    }
    


}
