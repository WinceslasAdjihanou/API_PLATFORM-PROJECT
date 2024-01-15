<?php

namespace App\Entity;

use ApiPlatform\Api\QueryParameterValidator\Validator\Length;
use ApiPlatform\Doctrine\Odm\State\Options;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;

use ApiPlatform\Metadata\ApiFilter;


use AppendIterator;
use Attribute;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ArticleRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ApiResource(
    // attributes: [
    //     'validation_groups' => []
    // ],
    normalizationContext: ['groups' => ['read:collection']],
    denormalizationContext: ['groups' => ['write:article']],
    paginationItemsPerPage: 2,
    paginationMaximumItemsPerPage: 2,
    paginationClientItemsPerPage: 2,  
),

ApiFilter(SearchFilter::class, properties:['id' => 'exact', 'title' => 'partial'])


]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:collection'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:collection', 'write:Article']),
    Length(min: 8, Groups: ['create:Article'])
    ]
    
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:collection', 'write:Article'])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read:item', 'write:Article'])] 
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['read:item' ])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    
    private ?\DateTimeInterface $updatedAt = null;


    // ... (autres propriétés)

    public function getOperations(): array
    {
        return [
            'put',
            'delete',
            'get' => [
                'normalization_context' => ['groups' => ['read:collection', 'read:item', 'read:article', 'read:Article']],
            ],
        ];
    }

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[Groups(['read:item', 'write:Article'])]
    private ?Category $category = null;

    // #[ORM\Column (Options = {"default":"0"})]
    #[ORM\Column]
     #[Groups(['read:collection'])]
    private ?bool $online = false;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function __construct() {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function isOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): static
    {
        $this->online = $online;

        return $this;
    }
}
