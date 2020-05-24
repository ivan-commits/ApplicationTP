<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups("main")
     * @Assert\Email( message = "L'email '{{ value }}' n'est pas une adresse mail valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("main")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire minimum 8 caractères")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password",message="Votre mot de passe doit être identique")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("main")
     */
    private $surname;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("main")
     */
    private $firstname_parent1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("main")
     */
    private $surname_parent1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("main")
     */
    private $firstname_parent2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("main")
     */
    private $surname_parent2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("main")
     */
    private $firstname_educateur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("main")
     */
    private $surname_educateur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("main")
     */
    private $firstname_orthophoniste;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("main")
     */
    private $surname_orthophoniste;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $confirmationToken;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ConfirmationResetPassword;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_de_naissance;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLogin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        if($this->getEmail() !== "admin@terreplurielle.fr"){
            $roles[] = 'ROLE_USER';
        }elseif($this->getEmail() == "admin@terreplurielle.fr"){
            $roles[] = 'ROLE_ADMIN';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }


    public function getFirstNameParent1(): ?string
    {
        return $this->firstname_parent1;
    }

    public function setFirstNameParent1(?string $first_name_parent1): self
    {
        $this->firstname_parent1 = $first_name_parent1;

        return $this;
    }

    public function getSurnameParent1(): ?string
    {
        return $this->surname_parent1;
    }

    public function setSurnameParent1(?string $surname_parent1): self
    {
        $this->surname_parent1 = $surname_parent1;

        return $this;
    }

    public function getFirstnameParent2(): ?string
    {
        return $this->firstname_parent2;
    }

    public function setFirstnameParent2(?string $firstname_parent2): self
    {
        $this->firstname_parent2 = $firstname_parent2;

        return $this;
    }

    public function getSurnameParent2(): ?string
    {
        return $this->surname_parent2;
    }

    public function setSurnameParent2(?string $surname_parent2): self
    {
        $this->surname_parent2 = $surname_parent2;

        return $this;
    }

    public function getFirstnameEducateur(): ?string
    {
        return $this->firstname_educateur;
    }

    public function setFirstnameEducateur(?string $firstname_educateur): self
    {
        $this->firstname_educateur = $firstname_educateur;

        return $this;
    }

    public function getSurnameEducateur(): ?string
    {
        return $this->surname_educateur;
    }

    public function setSurnameEducateur(?string $surname_educateur): self
    {
        $this->surname_educateur = $surname_educateur;

        return $this;
    }

    public function getFirstnameOrthophoniste(): ?string
    {
        return $this->firstname_orthophoniste;
    }

    public function setFirstnameOrthophoniste(?string $firstname_orthophoniste): self
    {
        $this->firstname_orthophoniste = $firstname_orthophoniste;

        return $this;
    }

    public function getSurnameOrthophoniste(): ?string
    {
        return $this->surname_orthophoniste;
    }

    public function setSurnameOrthophoniste(?string $surname_orthophoniste): self
    {
        $this->surname_orthophoniste = $surname_orthophoniste;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;
        return $this;
    }

    public function getConfirmationResetPassword(): ?string
    {
        return $this->ConfirmationResetPassword;
    }

    public function setConfirmationResetPassword(string $ConfirmationResetPassword): self
    {
        $this->ConfirmationResetPassword = $ConfirmationResetPassword;

        return $this;
    }

    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->date_de_naissance;
    }

    public function setDateDeNaissance(?\DateTimeInterface $date_de_naissance): self
    {
        $this->date_de_naissance = $date_de_naissance;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }


}
