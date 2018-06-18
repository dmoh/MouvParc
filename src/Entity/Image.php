<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Cars", inversedBy="images", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $car;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Carrosserie", inversedBy="images", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $carrosserie;

    /**
     * @ORM\Column(name="url", type="string", nullable=false)
     */
    protected $url;

    /**
     * @ORM\Column(name="alt", type="string", nullable=false)
     */
    protected $alt = "ImageCar" ;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @ORM\Column(name="carro", type="boolean", nullable=true)
     */
    protected $carro;

    /**
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }


    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }


    public function __construct()
    {
        $this->car = new ArrayCollection();
        $this->carrosserie = new ArrayCollection();
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }


    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }


    public function getUploadDir()
    {
        return 'photosCar';
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../public/'.$this->getUploadDir();
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        // la propriété « file » peut être vide si le champ n'est pas requis
        if (null === $this->file) {
            return;
        }

        if ($this->path != $this->file->getClientOriginalName()) {
            $this->path = $this->file->getClientOriginalName();
        }
    }

    public function deleteFiles($filename)
    {
        $file_path = $this->getUploadRootDir().'/'.$filename;

        //Suppresion du fichier
        if(file_exists($file_path))unlink($file_path);


        $fileTmp = $this->path;
        //suppression du fichier temporaire
        if(file_exists($fileTmp)) unlink($fileTmp);
    }


    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // la propriété « file » peut être vide si le champ n'est pas requis
        if (null === $this->file) {
            return;
        }
        $extensionImage = $this->file->guessExtension();
        $file_name = $this->url;

        // la méthode « move » prend comme arguments le répertoire cible et
        // le nom de fichier cible où le fichier doit être déplacé
        if (!file_exists($this->getUploadRootDir())) {
            mkdir($this->getUploadRootDir(), 0775, true);
        }
        $this->file->move(
            $this->getUploadRootDir(), $file_name
        );
        $this->file = null;
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

    public function generateUniqueFileName()
    {
        return md5(uniqid());
    }



    /**
     * Set path
     *
     * @param string $path
     *
     * @return Image
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getCarrosserie(): Carrosserie
    {
        return $this->carrosserie;
    }

    /**
     * @param mixed $carrosserie
     */
    public function setCarrosserie(Carrosserie $carrosserie): void
    {
        $this->carrosserie = $carrosserie;
    }

    /**
     * @return mixed
     */
    public function getCarro()
    {
        return $this->carro;
    }

    /**
     * @param mixed $carro
     */
    public function setCarro($carro): void
    {
        $this->carro = $carro;
    }







}
