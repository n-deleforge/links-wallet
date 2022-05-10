<?php

namespace App\Entity;

use App\Repository\LinkUserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LinkUserRepository::class)
 * @UniqueEntity(fields={"model"}, message= "Your readme already contains this model.")
 */
class LinkUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $username;

    /**
     * @ORM\ManyToOne(targetEntity=LinkModel::class, inversedBy="linkUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $model;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="linkUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getModel(): ?LinkModel
    {
        return $this->model;
    }

    public function setModel(?LinkModel $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
