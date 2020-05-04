<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PictogrammeRepository")
 * @Vich\Uploadable()
 */
class Pictogramme
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("picto:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     * @Groups("picto:read")
     */
    private $filename;

    /**
     * @Vich\UploadableField(mapping="picotgramme_images", fileNameProperty="filename")
     * @var File
     */
    private $imageFile;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="Categorie")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("picto:read")
     */
    private $categorie;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("picto:read")
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function __toString()
    {
       return $this->name;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     *
     * @param null|File $imageFile
     * @return Pictogramme
     */
    public function setImageFile(File $imageFile = null)
    {
        $this->imageFile = $imageFile;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($this->imageFile instanceof UploadedFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
