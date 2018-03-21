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
 * Class Rubrique
 * @package App\Entity
 * @ORM\Entity
 * @ORM\Table(name="rubrique")
 * @ORM\Entity(repositoryClass="App\Repository\RubriqueRepository")
 *
 */

class Rubrique
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="title", type="string", length=100, unique=true)
     * @Assert\NotBlank()
     */
    protected $title;


    /**
     * @ORM\Column(name="rubrique_text", type="text", nullable=true)
     */
    protected $rubrique_text;


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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getRubriqueText()
    {
        return $this->rubrique_text;
    }

    /**
     * @param mixed $rubrique_text
     */
    public function setRubriqueText($rubrique_text): void
    {
        $this->rubrique_text = $rubrique_text;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }


}