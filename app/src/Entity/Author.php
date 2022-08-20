<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[ApiResource(
    attributes: [],
    denormalizationContext: ["groups"=>["author:write"]],
    normalizationContext: ["groups"=>["author:read"]]
)]
#[ApiFilter(PropertyFilter::class)]
#[UniqueEntity(fields: ["email"])]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups([
        "author:read",
        "author:write",
        "ebook:item:get",
        "ebook:write"
    ])]
    #[NotBlank]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups([
        "author:read",
        "author:write",
        "ebook:item:get",
        "ebook:write"
    ])]
    #[NotBlank]
    private $lastName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups([
        "author:read",
        "author:write",
        "ebook:item:get",
        "ebook:write"
    ])]
    #[NotBlank]
    #[Email]
    private $email;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(["author:read"])]
    private $description;

    #[ORM\Column(type: 'datetime')]
    #[Groups(["author:read"])]
    private $createdAt;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Book::class, cascade: ['persist'], orphanRemoval: true)]
    #[Groups(["author:read", "author:write"])]
    #[Valid]
    #[ApiSubresource]
    private $books;


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    #[Groups(["author:write"])]
    public function setTextDescription(?string $description): self
    {
        $this->description = nl2br($description);

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->setAutor($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getAutor() === $this) {
                $book->setAutor(null);
            }
        }

        return $this;
    }

}
