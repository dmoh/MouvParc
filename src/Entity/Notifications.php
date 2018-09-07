<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationsRepository")
 */
class Notifications
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Conducteur", inversedBy="notifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $notifConducteur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sujetNotif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statueNotif;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;


    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->setStatueNotif(1);
    }


    public function getId()
    {
        return $this->id;
    }

    public function getNotifConducteur(): ?Conducteur
    {
        return $this->notifConducteur;
    }

    public function setNotifConducteur(?Conducteur $notifConducteur): self
    {
        $this->notifConducteur = $notifConducteur;

        return $this;
    }

    public function getSujetNotif(): ?string
    {
        return $this->sujetNotif;
    }

    public function setSujetNotif(string $sujetNotif): self
    {
        $this->sujetNotif = $sujetNotif;

        return $this;
    }

    public function getStatueNotif(): ?bool
    {
        return $this->statueNotif;
    }

    public function setStatueNotif(bool $statueNotif): self
    {
        $this->statueNotif = $statueNotif;

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
}
