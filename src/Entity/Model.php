<?php

namespace App\Entity;

use App\Repository\ModelRepository;
use Symfony\Component\Uid\Uuid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModelRepository::class)
 */
class Model
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $icon;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\OneToMany(targetEntity=LinkUser::class, mappedBy="model")
     */
    private $linkUsers;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->linkUsers = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection<int, LinkUser>
     */
    public function getLinkUsers(): Collection
    {
        return $this->linkUsers;
    }

    public function addLinkUser(LinkUser $linkUser): self
    {
        if (!$this->linkUsers->contains($linkUser)) {
            $this->linkUsers[] = $linkUser;
            $linkUser->setModel($this);
        }

        return $this;
    }

    public function removeLinkUser(LinkUser $linkUser): self
    {
        if ($this->linkUsers->removeElement($linkUser)) {
            // set the owning side to null (unless already changed)
            if ($linkUser->getModel() === $this) {
                $linkUser->setModel(null);
            }
        }

        return $this;
    }
}
