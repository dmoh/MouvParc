<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConducteurRepository")
 */
class Conducteur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $matricule_conducteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom_conducteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom_conducteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse_postale;

    /**
     * @ORM\Column(type="string", length=6, nullable=true)
     */
    private $code_postale;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $num_ss;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_maj;


    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="conducteur", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;


    /**
     * @ORM\Column(name="conducteur_aps", type="boolean", nullable=true)
     */
    private $conducteur_aps;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DemandesConducteurs", mappedBy="conducteur", orphanRemoval=true, cascade={"persist"})
     */
    private $demandes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RapportHebdo", mappedBy="rapportConducteur", orphanRemoval=true)
     */
    private $rapportHebdos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DemandeConges", mappedBy="demandeCongeConducteur", orphanRemoval=true)
     */
    private $demandeConges;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DemandeAccompte", mappedBy="demandeAccompteConducteur", orphanRemoval=true)
     */
    private $demandeAccomptes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notifications", mappedBy="notifConducteur", orphanRemoval=true)
     */
    private $notifications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuestionsPaie", mappedBy="questionsPaieConducteur", orphanRemoval=true)
     */
    private $questionsPaies;




    public function __construct()
    {
        $this->date_maj = new \DateTime();
        $this->conducteur_aps = false;
        $this->demandes = new ArrayCollection();
        $this->rapportHebdos = new ArrayCollection();
        $this->demandeConges = new ArrayCollection();
        $this->demandeAccomptes = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->questionsPaies = new ArrayCollection();

    }

    public function getId()
    {
        return $this->id;
    }

    public function getMatriculeConducteur(): ?string
    {
        return $this->matricule_conducteur;
    }

    public function setMatriculeConducteur(?string $matricule_conducteur): self
    {
        $this->matricule_conducteur = $matricule_conducteur;

        return $this;
    }

    public function getNomConducteur(): ?string
    {
        return $this->nom_conducteur;
    }

    public function setNomConducteur(?string $nom_conducteur): self
    {
        $this->nom_conducteur = $nom_conducteur;

        return $this;
    }

    public function getPrenomConducteur(): ?string
    {
        return $this->prenom_conducteur;
    }

    public function setPrenomConducteur(?string $prenom_conducteur): self
    {
        $this->prenom_conducteur = $prenom_conducteur;

        return $this;
    }

    public function getAdressePostale(): ?string
    {
        return $this->adresse_postale;
    }

    public function setAdressePostale(?string $adresse_postale): self
    {
        $this->adresse_postale = $adresse_postale;

        return $this;
    }

    public function getCodePostale(): ?string
    {
        return $this->code_postale;
    }

    public function setCodePostale(?string $code_postale): self
    {
        $this->code_postale = $code_postale;

        return $this;
    }

    public function getNumSs(): ?string
    {
        return $this->num_ss;
    }

    public function setNumSs(?string $num_ss): self
    {
        $this->num_ss = $num_ss;

        return $this;
    }

    public function getDateMaj(): ?\DateTimeInterface
    {
        return $this->date_maj;
    }

    public function setDateMaj(\DateTimeInterface $date_maj): self
    {
        $this->date_maj = $date_maj;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser(User $user = null): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getConducteurAps()
    {
        return $this->conducteur_aps;
    }

    /**
     * @param mixed $conducteur_aps
     */
    public function setConducteurAps($conducteur_aps): void
    {
        $this->conducteur_aps = $conducteur_aps;
    }

    /**
     * @return Collection|DemandesConducteurs[]
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(DemandesConducteurs $demande): self
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes[] = $demande;
            $demande->setConducteur($this);
        }

        return $this;
    }

    public function removeDemande(DemandesConducteurs $demande): self
    {
        if ($this->demandes->contains($demande)) {
            $this->demandes->removeElement($demande);
            // set the owning side to null (unless already changed)
            if ($demande->getConducteur() === $this) {
                $demande->setConducteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RapportHebdo[]
     */
    public function getRapportHebdos(): Collection
    {
        return $this->rapportHebdos;
    }

    public function addRapportHebdo(RapportHebdo $rapportHebdo): self
    {
        if (!$this->rapportHebdos->contains($rapportHebdo)) {
            $this->rapportHebdos[] = $rapportHebdo;
            $rapportHebdo->setRapportConducteur($this);
        }

        return $this;
    }

    public function removeRapportHebdo(RapportHebdo $rapportHebdo): self
    {
        if ($this->rapportHebdos->contains($rapportHebdo)) {
            $this->rapportHebdos->removeElement($rapportHebdo);
            // set the owning side to null (unless already changed)
            if ($rapportHebdo->getRapportConducteur() === $this) {
                $rapportHebdo->setRapportConducteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DemandeConges[]
     */
    public function getDemandeConges(): Collection
    {
        return $this->demandeConges;
    }

    public function addDemandeConge(DemandeConges $demandeConge): self
    {
        if (!$this->demandeConges->contains($demandeConge)) {
            $this->demandeConges[] = $demandeConge;
            $demandeConge->setDemandeCongeConducteur($this);
        }

        return $this;
    }

    public function removeDemandeConge(DemandeConges $demandeConge): self
    {
        if ($this->demandeConges->contains($demandeConge)) {
            $this->demandeConges->removeElement($demandeConge);
            // set the owning side to null (unless already changed)
            if ($demandeConge->getDemandeCongeConducteur() === $this) {
                $demandeConge->setDemandeCongeConducteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DemandeAccompte[]
     */
    public function getDemandeAccomptes(): Collection
    {
        return $this->demandeAccomptes;
    }

    public function addDemandeAccompte(DemandeAccompte $demandeAccompte): self
    {
        if (!$this->demandeAccomptes->contains($demandeAccompte)) {
            $this->demandeAccomptes[] = $demandeAccompte;
            $demandeAccompte->setDemandeAccompteConducteur($this);
        }

        return $this;
    }

    public function removeDemandeAccompte(DemandeAccompte $demandeAccompte): self
    {
        if ($this->demandeAccomptes->contains($demandeAccompte)) {
            $this->demandeAccomptes->removeElement($demandeAccompte);
            // set the owning side to null (unless already changed)
            if ($demandeAccompte->getDemandeAccompteConducteur() === $this) {
                $demandeAccompte->setDemandeAccompteConducteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notifications[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notifications $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setNotifConducteur($this);
        }

        return $this;
    }

    public function removeNotification(Notifications $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getNotifConducteur() === $this) {
                $notification->setNotifConducteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|QuestionsPaie[]
     */
    public function getQuestionsPaies(): Collection
    {
        return $this->questionsPaies;
    }

    public function addQuestionsPaie(QuestionsPaie $questionsPaie): self
    {
        if (!$this->questionsPaies->contains($questionsPaie)) {
            $this->questionsPaies[] = $questionsPaie;
            $questionsPaie->setQuestionsPaieConducteur($this);
        }

        return $this;
    }

    public function removeQuestionsPaie(QuestionsPaie $questionsPaie): self
    {
        if ($this->questionsPaies->contains($questionsPaie)) {
            $this->questionsPaies->removeElement($questionsPaie);
            // set the owning side to null (unless already changed)
            if ($questionsPaie->getQuestionsPaieConducteur() === $this) {
                $questionsPaie->setQuestionsPaieConducteur(null);
            }
        }

        return $this;
    }



















}
