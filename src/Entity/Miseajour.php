<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MiseajourRepository")
 */
class Miseajour
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="docExcel", type="string")
     *
     * @Assert\NotBlank(message="Ce n'est pas un fichier Excel.")
     * @Assert\File(mimeTypes={ "text/plain" })
     */
    private $docExcel;

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
    public function getDocExcel()
    {
        return $this->docExcel;
    }

    /**
     * @param mixed $docExcel
     */
    public function setDocExcel($docExcel): void
    {
        $this->docExcel = $docExcel;
    }



}
