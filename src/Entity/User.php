<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=UserLink::class, mappedBy="user")
     */
    private $userLinks;

    /**
     * @ORM\OneToOne(targetEntity=UserDetail::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $userDetail;

    public function __construct()
    {
        $this->userLinks = new ArrayCollection();
    }

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
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
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
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, UserLink>
     */
    public function getUserLinks(): Collection
    {
        return $this->userLinks;
    }

    public function addUserLink(UserLink $userLink): self
    {
        if (!$this->userLinks->contains($userLink)) {
            $this->userLinks[] = $userLink;
            $userLink->setUser($this);
        }

        return $this;
    }

    public function removeUserLink(UserLink $userLink): self
    {
        if ($this->userLinks->removeElement($userLink)) {
            // set the owning side to null (unless already changed)
            if ($userLink->getUser() === $this) {
                $userLink->setUser(null);
            }
        }

        return $this;
    }

    public function getUserDetail(): ?UserDetail
    {
        return $this->userDetail;
    }

    public function setUserDetail(UserDetail $userDetail): self
    {
        // set the owning side of the relation if necessary
        if ($userDetail->getUser() !== $this) {
            $userDetail->setUser($this);
        }

        $this->userDetail = $userDetail;

        return $this;
    }
}
