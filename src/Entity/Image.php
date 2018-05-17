<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cars", inversedBy="images")
     * @ORM\JoinColumn(nullable=true)
     */
    private $car;

    /**
     * @ORM\Column(name="url", type="string", nullable=false)
     */
    protected $url;

    /**
     * @ORM\Column(name="alt", type="string", nullable=false)
     */
    protected $alt = "ImageCar" ;


    public function __construct()
    {
        $this->car = new ArrayCollection();
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
    public function getCar(): Cars
    {
        return $this->car;
    }

    /**
     * @param mixed $car
     */
    public function setCar(Cars $car= null): void
    {
        $this->car = $car;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param mixed $alt
     */
    public function setAlt($alt): void
    {
        $this->alt = $alt;
    }






}
