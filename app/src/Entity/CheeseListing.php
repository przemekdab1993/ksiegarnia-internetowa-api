<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use App\Repository\CheeseListingRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CheeseListingRepository::class)]
#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: ['get', 'put'],
    shortName: 'cheese',
    attributes: [
        'pagination_items_per_page' => 10,
        'formats' => [
            'jsonld', 'json', 'html', 'jsonhal', 'csv' => ['text/csv']
        ]
    ],
    denormalizationContext: ['groups' => ['cheeses_list:write'], 'swagger_definition_name'=>'Write'],
    normalizationContext: ['groups' => ['cheeses_list:read'], 'swagger_definition_name'=>'Read']
)]
#[ApiFilter(BooleanFilter::class, properties: ['isPublished'])]
#[ApiFilter(SearchFilter::class, properties: ['title'=>'partial'])]
#[ApiFilter(RangeFilter::class, properties: ['price'])]
#[ApiFilter(PropertyFilter::class)]
class CheeseListing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('cheeses_list:read')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['cheeses_list:read', 'cheeses_list:write'])]
    private $title;

    #[ORM\Column(type: 'text')]
    #[Groups('cheeses_list:read')]
    private $description;

    #[ORM\Column(type: 'integer')]
    #[Groups(['cheeses_list:read', 'cheeses_list:write'])]
    private $price;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'boolean')]
    private $isPublished = false;

    #[ORM\Column(type: 'integer')]
    #[Groups(['cheeses_list:read', 'cheeses_list:write'])]
    private $quantity;


    public function __construct(string $title = null)
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->title = $title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

//    public function setTitle(string $title): self
//    {
//        $this->title = $title;
//
//        return $this;
//    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    #[Groups('cheeses_list:read')]
    public function getShortDescription(): ?string
    {
        if (strlen($this->description) < 40) {
            return $this->description;
        }

        return substr($this->description, 0, 40).'...';
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    #[Groups('cheeses_list:write')]
    #[SerializedName('description')]
    public function setTextDescription(string $description): self
    {
        $this->description = nl2br($description);

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }


    #[Groups('cheeses_list:read')]
    public function getCreatedAgo(): string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
