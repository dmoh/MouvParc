<?php
/**
 * Created by PhpStorm.
 * User: UTILISATEUR
 * Date: 27/12/2017
 * Time: 21:18
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Cars
 * @package App\Entity
 * @ORM\Entity
 * @ORM\Table(name="cars")
 * @ORM\Entity(repositoryClass="App\Repository\CarsRepository")
 * @UniqueEntity(fields="immat",
 *     message="Ce véhicule existe déjà dans la bdd."
 * )
 */

class Cars
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="immat", type="string", length=100, unique=true)
     * @Assert\NotBlank()
     */
    protected $immat;

    /**
     * @ORM\Column(name="date", type="date")
     */
    protected $date;

    /**
     * @ORM\Column(name="compteur_panne", type="integer", nullable=true)
     */
    protected $compteur_panne = 0;

    /**
     * @ORM\Column(name="site", type="string", length=100)
     * @Assert\NotBlank
     * @Assert\Regex("/^\w+/")
     */
    protected $site;

    /**
     * @ORM\Column(name="num_serie", type="string", length=100)
     */
    protected $num_serie;

    /**
     * @ORM\Column(name="auteur", type="string", length=100, nullable=true)
     */
    protected $auteur;

    /**
     * @ORM\Column(name="marque", type="string", length=100, nullable=true)
     * @Assert\NotBlank()
     */
    protected $marque;

    /**
     * @ORM\Column(name="locataire", type="string", nullable=true)
     *
     */
    protected $locataire;

    /**
     * @ORM\Column(name="date_debut_garantie", type="date", nullable=true)
     */
    protected $date_prev;

    /**
     * @ORM\Column(name="date_mar", type="date", nullable=true)
     */
    protected $date_mar;


    /**
     * @ORM\Column(name="fin_garantie", type="date", nullable=true)
     */
    protected $fin_garantie;

    /**
     * @ORM\Column(name="action_engages", type="text", nullable=true)
     */
    protected $action_engages;

    /**
     * @ORM\Column(name="etat_car", type="string", nullable=true)
     */
    protected $etat_car;

    /**
     * @ORM\Column(name="date_fin_panne", type="date", nullable=true)
     */
    protected $date_fin_panne;

    /**
     * @ORM\Column(name="date_panne_deb", type="datetime", nullable=true)
     */
    protected $date_panne_deb;


    /**
     * @ORM\Column(name="date_panne_fin", type="datetime", nullable=true)
     */
    protected $date_panne_fin;

    /**
     * @ORM\Column(name="condition_garantie", type="string", nullable=true)
     *
     */
    protected $condition_garantie;


    /**
     * @ORM\Column(name="proprio_pneus", type="string", nullable=true)
     */
    protected $proprio_pneus;

    /**
     * @ORM\Column(name="p_encours", type="boolean", nullable=true)
     */
    protected $p_encours = false;

    /**
     * @ORM\Column(name="date_maj", type="datetime", nullable=true)
     */
    protected $date_maj;

    /**
     * @ORM\Column(name="duree_panne", type="string", nullable=true)
     */
    protected $duree_panne;


    /**
     * @ORM\Column(name="desc_panne_car", type="text", nullable=true)
     */
    protected $desc_panne_car;

    /**
     * @ORM\Column(name="garantie", type="text", nullable=true)
     */
    protected $garantie;

    /**
     * @ORM\Column(name="desc_panne_ano", type="text", nullable=true)
     */
    protected $desc_panne_ano;
    /**
     *
     * @ORM\Column(name="nature_panne_car", type="text", nullable=true)
     */
    protected $nature_panne_car;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Panne", mappedBy="cars", orphanRemoval=true, cascade={"persist", "remove", "merge"})
     */
    protected $pannes;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="car", orphanRemoval=true, cascade={"persist", "remove", "merge"})
     */
    protected $images;



    /**
     * @ORM\Column(name="memo_car", type="text", nullable=true)
     */
    protected $memo_car;

    /**
     * @ORM\Column(name="nb_places", type="integer", nullable= true)
     */
    protected $nb_places;

    /**
     * @ORM\Column(name="siege_guide", type="boolean", nullable= true)
     */
    protected $siege_guide = false;

    /**
     * @ORM\Column(name="euro", type="string", nullable= true)
     */
    protected $euro;

    /**
     * @ORM\Column(name="ct", type="date", nullable= true)
     */
    protected $ct;

    /**
     * @ORM\Column(name="date_extincteur", type="date", nullable= true)
     */
    protected $date_extincteur;


    /**
     * @ORM\Column(name="date_limiteur", type="date", nullable= true)
     */
    protected $date_limiteur;

    /**
     * @ORM\Column(name="date_tachy", type="date", nullable= true)
     */
    protected $date_tachy;

    /**
     * @ORM\Column(name="date_ethylo", type="date", nullable= true)
     */
    protected $date_ethylo;

    /**
     * @ORM\Column(name="wc", type="boolean", nullable= true)
     */
    protected $wc = false;

    /**
     * @ORM\Column(name="ufr", type="boolean", nullable= true)
     */
    protected $ufr = false;

    /**
     * @ORM\Column(name="usb", type="boolean", nullable= true)
     */
    protected $usb = false;

    /**
     * @ORM\Column(name="prises_elec", type="boolean", nullable= true)
     */
    protected $prises_elec = false;

    /**
     * @ORM\Column(name="porte_ski", type="boolean", nullable= true)
     */
    protected $porte_ski = false;


    /**
     * @ORM\Column(name="km", type="integer", nullable=true)
     */
    protected $km;






    /**
     * @ORM\Column(name="regulateur_vitesse", type="boolean", nullable=true)
     */
    protected $regulateur_vitesse;

    /**
     * @ORM\Column(name="gps", type="boolean", nullable=true)
     */
    protected $gps;


    /**
     * @ORM\Column(name="ralentisseur", type="string", length=255, nullable=true)
     */
    protected $ralentisseur;

    /**
     * @ORM\Column(name="abs", type="boolean", nullable=true)
     */
    protected $abs;


    /**
     * @ORM\Column(name="esp", type="boolean", nullable=true)
     */
    protected $esp;


    /**
     * @ORM\Column(name="asr", type="boolean", nullable=true)
     */
    protected $asr;

    /**
     * @ORM\Column(name="ceinture_securite", type="string", nullable=true)
     */
    protected $ceinture_securite;

    /**
     * @ORM\Column(name="repose_mollet", type="boolean", nullable=true)
     */
    protected $repose_mollet;

    /**
     * @ORM\Column(name="tachygraphe", type="string", nullable=true)
     */
    protected $tachygraphe;


    /**
     * @ORM\Column(name="video", type="string", nullable=true)
     */
    protected $video;


    /**
     * @ORM\Column(name="micro_conducteur", type="boolean", nullable=true)
     */
    protected $micro_conducteur;

    /**
     * @ORM\Column(name="camera", type="string", nullable=true)
     */
    protected $camera;

    /**
     * @ORM\Column(name="radio", type="string", nullable=true)
     */
    protected $radio;

    /**
     * @ORM\Column(name="micro_guide", type="boolean", nullable=true)
     */
    protected $micro_guide;


    /**
     * @ORM\Column(name="chauffage_independant", type="boolean", nullable=true)
     */
    protected $chauffage_independant;

    /**
     * @ORM\Column(name="buses_individuelles", type="boolean", nullable=true)
     */
    protected $buses_individuelles;

    /**
     * @ORM\Column(name="tablettes", type="boolean", nullable=true)
     */
    protected $tablettes;

    /**
     * @ORM\Column(name="sieges_decalables", type="boolean", nullable=true)
     */
    protected $sieges_decalables;


    /**
     * @ORM\Column(name="frigo", type="boolean", nullable=true)
     */
    protected $frigo;

    /**
     * @ORM\Column(name="girouette", type="boolean", nullable=true)
     */
    protected $girouette;

    /**
     * @ORM\Column(name="rideaux", type="boolean", nullable=true)
     */
    protected $rideaux;


    /**
     * @ORM\Column(name="clim", type="boolean", nullable=true)
     */
    protected $clim;

    /**
     * @ORM\Column(name="filets", type="boolean", nullable=true)
     */
    protected $filets;






    public function __construct()
    {
        $this->date = new \DateTime();
        $this->pannes = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getKm()
    {
        return $this->km;
    }

    /**
     * @param mixed $km
     */
    public function setKm($km): void
    {
        $this->km = $km;
    }


    public function addPanne(Panne $panne)
    {
        $this->pannes[] = $panne;

        $panne->setCars($this);

        return $this;
    }

    public function removePanne(Panne $panne)
    {
        $this->pannes->removeElement($panne);

        $panne->setCars(null);
    }



    public function addImage(Image $image)
    {
        $this->images[] = $image;

        $image->setCar($this);

        return $this;
    }

    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);

        $image->setCar(null);
    }





    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function setMarque($marque)
    {
         $this->marque = $marque;
    }

    /**
     * @return mixed
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * @return mixed
     */
    public function getImmat()
    {
        return $this->immat;
    }

    /**
     * @param mixed $immat
     */
    public function setImmat($immat): void
    {
        $this->immat = $immat;
    }

    /**
     * @param mixed $locataire
     */
    public function setLocataire($locataire): void
    {
        $this->locataire = $locataire;
    }

    /**
     * @return mixed
     */
    public function getLocataire()
    {
        return $this->locataire;
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
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getNumSerie()
    {
        return $this->num_serie;
    }

    /**
     * @param mixed $num_serie
     */
    public function setNumSerie($num_serie): void
    {
        $this->num_serie = $num_serie;
    }

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    public function setSite($site): void
    {
        $this->site = $site;
    }

    /**
     * @return mixed
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * @param mixed $type
     */
    public function setAuteur($auteur): void
    {
        $this->auteur = $auteur;
    }


    /**
     * @return mixed
     */
    public function getDatePrev()
    {
        return $this->date_prev;
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
    public function getDateMar()
    {
        return $this->date_mar;
    }

    /**
     * @param mixed $date_mar
     */
    public function setDateMar($date_mar): void
    {
        $this->date_mar = $date_mar;
    }


    /**
     * @return mixed
     */
    public function getFinGarantie()
    {
        return $this->fin_garantie;
    }

    /**
     * @param mixed $fin_garantie
     */
    public function setFinGarantie($fin_garantie): void
    {
        $this->fin_garantie = $fin_garantie;
    }

    /**
     * @param mixed $action_engages
     */
    public function setActionEngages($action_engages): void
    {
        $this->action_engages = $action_engages;
    }

    /**
     * @return mixed
     */
    public function getActionEngages()
    {
        return $this->action_engages;
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
    public function getEtatCar()
    {
        return $this->etat_car;
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
     * @return mixed
     */
    public function getDatePanneDeb()
    {
        return $this->date_panne_deb;
    }

    /**
     * @param mixed $date_panne_deb
     */
    public function setDatePanneDeb($date_panne_deb): void
    {
        $this->date_panne_deb = $date_panne_deb;
    }


    /**
     * @param mixed $condition_garantie
     */
    public function setConditionGarantie($condition_garantie): void
    {
        $this->condition_garantie = $condition_garantie;
    }

    /**
     * @return mixed
     */
    public function getConditionGarantie()
    {
        return $this->condition_garantie;
    }

    /**
     * @param mixed $proprio_pneus
     */
    public function setProprioPneus($proprio_pneus): void
    {
        $this->proprio_pneus = $proprio_pneus;
    }

    /**
     * @return mixed
     */
    public function getProprioPneus()
    {
        return $this->proprio_pneus;
    }

    /**
     * @return mixed
     */
    public function getPEncours()
    {
        return $this->p_encours;
    }

    /**
     * @param mixed $p_encours
     */
    public function setPEncours($p_encours): void
    {
        $this->p_encours = $p_encours;
    }


    public function __toString()
    {
       return  $this->immat;
    }

    /**
     * @return mixed
     */
    public function getDateMaj()
    {
        return $this->date_maj;
    }

    /**
     * @param mixed $date_maj
     */
    public function setDateMaj($date_maj): void
    {
        $this->date_maj = $date_maj;
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

    /**
     * @return mixed
     */
    public function getDatePanneFin()
    {
        return $this->date_panne_fin;
    }

    /**
     * @param mixed $date_panne_fin
     */
    public function setDatePanneFin($date_panne_fin): void
    {
        $this->date_panne_fin = $date_panne_fin;
    }


    /**
     * @return mixed
     */
    public function getDureePanne()
    {
        return $this->duree_panne;
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
    public function getDescPanneCar()
    {
        return $this->desc_panne_car;
    }

    /**
     * @param mixed $desc_panne_car
     */
    public function setDescPanneCar($desc_panne_car): void
    {
        $this->desc_panne_car = $desc_panne_car;
    }


    /*
     *@return Collection|Panne[]

    public function getPannes()
    {
        return $this->pannes;
    }


    public function removePanne(Panne $panne)
    {
        $this->pannes->removeElement($panne);
        $panne->setCars(null);
    }

    public function addPanne(Panne $panne)
    {
       $this->pannes[] = $panne;

       $panne->setCars($this);

       return $this;
    }*/

       /**
     * @param mixed $pannes
     */
    public function setPannes($pannes): void
    {
        $this->pannes = $pannes;
    }

    /*
     *@return Collection|Panne[]
     */
    public function getPannes()
    {
        return $this->pannes;
    }


    /**
     * @param mixed $images
     */
    public function setImages($images): void
    {
        $this->images = $images;
    }

    /*
     *@return Collection|Images[]
     */
    public function getImages()
    {
        return $this->images;
    }




    /**
     * @return mixed
     */
    public function getDescPanneAno()
    {
        return $this->desc_panne_ano;
    }

    /**
     * @param mixed $desc_panne_ano
     */
    public function setDescPanneAno($desc_panne_ano): void
    {
        $this->desc_panne_ano = $desc_panne_ano;
    }

    /**
     * @return mixed
     */
    public function getCompteurPanne()
    {
        return $this->compteur_panne;
    }

    /**
     * @param mixed $compteur_panne
     */
    public function setCompteurPanne($compteur_panne): void
    {
        $this->compteur_panne = $compteur_panne;
    }

    /**
     * @return mixed
     */
    public function getNaturePanneCar()
    {
        return $this->nature_panne_car;
    }

    /**
     * @param mixed $nature_panne_car
     */
    public function setNaturePanneCar($nature_panne_car): void
    {
        $this->nature_panne_car = $nature_panne_car;
    }

    /**
     * @return mixed
     */
    public function getMemoCar()
    {
        return $this->memo_car;
    }

    /**
     * @param mixed $memo_car
     */
    public function setMemoCar($memo_car): void
    {
        $this->memo_car = $memo_car;
    }

    /**
     * @return mixed
     */
    public function getNbPlaces()
    {
        return $this->nb_places;
    }

    /**
     * @param mixed $nb_places
     */
    public function setNbPlaces($nb_places): void
    {
        $this->nb_places = $nb_places;
    }

    /**
     * @return mixed
     */
    public function getSiegeGuide()
    {
        return $this->siege_guide;
    }

    /**
     * @param mixed $siege_guide
     */
    public function setSiegeGuide($siege_guide): void
    {
        $this->siege_guide = $siege_guide;
    }

    /**
     * @return mixed
     */
    public function getEuro()
    {
        return $this->euro;
    }

    /**
     * @param mixed $euro
     */
    public function setEuro($euro): void
    {
        $this->euro = $euro;
    }

    /**
     * @return mixed
     */
    public function getCt()
    {
        return $this->ct;
    }

    /**
     * @param mixed $ct
     */
    public function setCt($ct): void
    {
        $this->ct = $ct;
    }

    /**
     * @return mixed
     */
    public function getDateExtincteur()
    {
        return $this->date_extincteur;
    }

    /**
     * @param mixed $date_extincteur
     */
    public function setDateExtincteur($date_extincteur): void
    {
        $this->date_extincteur = $date_extincteur;
    }

    /**
     * @return mixed
     */
    public function getDateLimiteur()
    {
        return $this->date_limiteur;
    }

    /**
     * @param mixed $date_limiteur
     */
    public function setDateLimiteur($date_limiteur): void
    {
        $this->date_limiteur = $date_limiteur;
    }

    /**
     * @return mixed
     */
    public function getDateTachy()
    {
        return $this->date_tachy;
    }

    /**
     * @param mixed $date_tachy
     */
    public function setDateTachy($date_tachy): void
    {
        $this->date_tachy = $date_tachy;
    }

    /**
     * @return mixed
     */
    public function getDateEthylo()
    {
        return $this->date_ethylo;
    }

    /**
     * @param mixed $date_ethylo
     */
    public function setDateEthylo($date_ethylo): void
    {
        $this->date_ethylo = $date_ethylo;
    }

    /**
     * @return mixed
     */
    public function getWc()
    {
        return $this->wc;
    }

    /**
     * @param mixed $wc
     */
    public function setWc($wc): void
    {
        $this->wc = $wc;
    }

    /**
     * @return mixed
     */
    public function getUfr()
    {
        return $this->ufr;
    }

    /**
     * @param mixed $ufr
     */
    public function setUfr($ufr): void
    {
        $this->ufr = $ufr;
    }

    /**
     * @return mixed
     */
    public function getUsb()
    {
        return $this->usb;
    }

    /**
     * @param mixed $usb
     */
    public function setUsb($usb): void
    {
        $this->usb = $usb;
    }

    /**
     * @return mixed
     */
    public function getPrisesElec()
    {
        return $this->prises_elec;
    }

    /**
     * @param mixed $prises_elec
     */
    public function setPrisesElec($prises_elec): void
    {
        $this->prises_elec = $prises_elec;
    }

    /**
     * @return mixed
     */
    public function getPorteSki()
    {
        return $this->porte_ski;
    }

    /**
     * @param mixed $porte_ski
     */
    public function setPorteSki($porte_ski): void
    {
        $this->porte_ski = $porte_ski;
    }

    /**
     * @return mixed
     */
    public function getRegulateurVitesse()
    {
        return $this->regulateur_vitesse;
    }

    /**
     * @param mixed $regulateur_vitesse
     */
    public function setRegulateurVitesse($regulateur_vitesse): void
    {
        $this->regulateur_vitesse = $regulateur_vitesse;
    }

    /**
     * @return mixed
     */
    public function getGps()
    {
        return $this->gps;
    }

    /**
     * @param mixed $gps
     */
    public function setGps($gps): void
    {
        $this->gps = $gps;
    }

    /**
     * @return mixed
     */
    public function getRalentisseur()
    {
        return $this->ralentisseur;
    }

    /**
     * @param mixed $ralentisseur
     */
    public function setRalentisseur($ralentisseur): void
    {
        $this->ralentisseur = $ralentisseur;
    }

    /**
     * @return mixed
     */
    public function getAbs()
    {
        return $this->abs;
    }

    /**
     * @param mixed $abs
     */
    public function setAbs($abs): void
    {
        $this->abs = $abs;
    }

    /**
     * @return mixed
     */
    public function getEsp()
    {
        return $this->esp;
    }

    /**
     * @param mixed $esp
     */
    public function setEsp($esp): void
    {
        $this->esp = $esp;
    }

    /**
     * @return mixed
     */
    public function getAsr()
    {
        return $this->asr;
    }

    /**
     * @param mixed $asr
     */
    public function setAsr($asr): void
    {
        $this->asr = $asr;
    }

    /**
     * @return mixed
     */
    public function getCeintureSecurite()
    {
        return $this->ceinture_securite;
    }

    /**
     * @param mixed $ceinture_securite
     */
    public function setCeintureSecurite($ceinture_securite): void
    {
        $this->ceinture_securite = $ceinture_securite;
    }

    /**
     * @return mixed
     */
    public function getReposeMollet()
    {
        return $this->repose_mollet;
    }

    /**
     * @param mixed $repose_mollet
     */
    public function setReposeMollet($repose_mollet): void
    {
        $this->repose_mollet = $repose_mollet;
    }

    /**
     * @return mixed
     */
    public function getTachygraphe()
    {
        return $this->tachygraphe;
    }

    /**
     * @param mixed $tachygraphe
     */
    public function setTachygraphe($tachygraphe): void
    {
        $this->tachygraphe = $tachygraphe;
    }

    /**
     * @return mixed
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param mixed $video
     */
    public function setVideo($video): void
    {
        $this->video = $video;
    }

    /**
     * @return mixed
     */
    public function getMicroConducteur()
    {
        return $this->micro_conducteur;
    }

    /**
     * @param mixed $micro_conducteur
     */
    public function setMicroConducteur($micro_conducteur): void
    {
        $this->micro_conducteur = $micro_conducteur;
    }

    /**
     * @return mixed
     */
    public function getCamera()
    {
        return $this->camera;
    }

    /**
     * @param mixed $camera
     */
    public function setCamera($camera): void
    {
        $this->camera = $camera;
    }

    /**
     * @return mixed
     */
    public function getRadio()
    {
        return $this->radio;
    }

    /**
     * @param mixed $radio
     */
    public function setRadio($radio): void
    {
        $this->radio = $radio;
    }

    /**
     * @return mixed
     */
    public function getMicroGuide()
    {
        return $this->micro_guide;
    }

    /**
     * @param mixed $micro_guide
     */
    public function setMicroGuide($micro_guide): void
    {
        $this->micro_guide = $micro_guide;
    }

    /**
     * @return mixed
     */
    public function getChauffageIndependant()
    {
        return $this->chauffage_independant;
    }

    /**
     * @param mixed $chauffage_independant
     */
    public function setChauffageIndependant($chauffage_independant): void
    {
        $this->chauffage_independant = $chauffage_independant;
    }

    /**
     * @return mixed
     */
    public function getBusesIndividuelles()
    {
        return $this->buses_individuelles;
    }

    /**
     * @param mixed $buses_individuelles
     */
    public function setBusesIndividuelles($buses_individuelles): void
    {
        $this->buses_individuelles = $buses_individuelles;
    }

    /**
     * @return mixed
     */
    public function getTablettes()
    {
        return $this->tablettes;
    }

    /**
     * @param mixed $tablettes
     */
    public function setTablettes($tablettes): void
    {
        $this->tablettes = $tablettes;
    }

    /**
     * @return mixed
     */
    public function getSiegesDecalables()
    {
        return $this->sieges_decalables;
    }

    /**
     * @param mixed $sieges_decalables
     */
    public function setSiegesDecalables($sieges_decalables): void
    {
        $this->sieges_decalables = $sieges_decalables;
    }

    /**
     * @return mixed
     */
    public function getFrigo()
    {
        return $this->frigo;
    }

    /**
     * @param mixed $frigo
     */
    public function setFrigo($frigo): void
    {
        $this->frigo = $frigo;
    }

    /**
     * @return mixed
     */
    public function getGirouette()
    {
        return $this->girouette;
    }

    /**
     * @param mixed $girouette
     */
    public function setGirouette($girouette): void
    {
        $this->girouette = $girouette;
    }

    /**
     * @return mixed
     */
    public function getRideaux()
    {
        return $this->rideaux;
    }

    /**
     * @param mixed $rideaux
     */
    public function setRideaux($rideaux): void
    {
        $this->rideaux = $rideaux;
    }

    /**
     * @return mixed
     */
    public function getClim()
    {
        return $this->clim;
    }

    /**
     * @param mixed $clim
     */
    public function setClim($clim): void
    {
        $this->clim = $clim;
    }

    /**
     * @return mixed
     */
    public function getFilets()
    {
        return $this->filets;
    }

    /**
     * @param mixed $filets
     */
    public function setFilets($filets): void
    {
        $this->filets = $filets;
    }








}