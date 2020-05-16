<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 * @Vich\Uploadable()
 */
class Categorie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("picto:read")
     * @Groups("categorie:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     * @Groups("picto:read")
     * @Groups("categorie:read")
     */
    private $filename;

    /**
     * @Vich\UploadableField(mapping="categorie_images", fileNameProperty="filename")
     * @var File
     */
    private $imageFile;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pictogramme", mappedBy="categorie")
     */
    private $Categorie;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("picto:read")
     * @Groups("categorie:read")
     */
    private $name;

    /**
     * @Groups("picto:read")
     * @Groups("categorie:read")
     * @ORM\Column(type="smallint")
     */
    private $place;

    public function __construct()
    {
        $this->Categorie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function __toString()
    {
       return $this->name;
    }

        /**
     * @return Collection|Pictogramme[]
     */
    public function getCategorie(): Collection
    {
        return $this->Categorie;
    }

    public function addCategorie(Pictogramme $categorie): self
    {
        if (!$this->Categorie->contains($categorie)) {
            $this->Categorie[] = $categorie;
            $categorie->setCategorie($this);
        }

        return $this;
    }

    public function removeCategorie(Pictogramme $categorie): self
    {
        if ($this->Categorie->contains($categorie)) {
            $this->Categorie->removeElement($categorie);
            // set the owning side to null (unless already changed)
            if ($categorie->getCategorie() === $this) {
                $categorie->setCategorie(null);
            }
        }

        return $this;
    }

    /**
     *
     * @param null|File $imageFile
     * @return Categorie
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

    public function getPlace(): ?int
    {
        return $this->place;
    }

    public function setPlace(int $place): self
    {
        $this->place = $place;

        return $this;
    }
}
