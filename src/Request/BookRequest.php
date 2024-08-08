<?php

namespace App\Request;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Publisher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class BookRequest
{
    #[Assert\Type("string")]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    public readonly string $name;

    #[Assert\Type("integer")]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(1000)]
    public readonly int $publication_year;

    #[Assert\Type("integer")]
    #[Assert\NotBlank]
    public readonly int $publisher_id;

    #[Assert\Type("integer")]
    #[Assert\NotBlank]
    public readonly int $author_id;

    public EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, array $data)
    {
        $this->entityManager = $entityManager;

        $this->name = $data['name'];
        $this->publication_year = $data['publication_year'];
        $this->publisher_id = $data['publisher_id'];
        $this->author_id = $data['author_id'];
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, mixed $payload): void
    {
        $uniqueFields = [
            'name' => $this->name,
            'publication_year' => $this->publication_year
        ];

        if (!$this->isBookUnique($uniqueFields)) {
            $context->buildViolation('Such a book already exists')
                ->atPath('name')
                ->addViolation();
        }

        if (!$this->isPublisherExist()) {
            $context->buildViolation('Such a publisher not found')
                ->atPath('publisher_id')
                ->addViolation();
        }

        if (!$this->isAuthorExist()) {
            $context->buildViolation('Such an author not found')
                ->atPath('author_id')
                ->addViolation();
        }

        if ($this->isPublicationYearExceedTheCurrentYear()) {
            $context->buildViolation('Publication year must not exceed the current year')
                ->atPath('publication_year')
                ->addViolation();
        }
    }

    public function isBookUnique(array $uniqueFields): bool
    {
        $book = $this->entityManager->getRepository(Book::class)->findOneBy($uniqueFields);

        return $book ? false : true;
    }

    public function isPublisherExist(): bool
    {
        $publisher = $this->entityManager->getRepository(Publisher::class)->find($this->publisher_id);

        return $publisher ? true : false;
    }

    public function isAuthorExist(): bool
    {
        $author = $this->entityManager->getRepository(Author::class)->find($this->author_id);

        return $author ? true : false;
    }

    public function isPublicationYearExceedTheCurrentYear(): bool
    {
        $currentYear = date("Y");

        return $this->publication_year > $currentYear;
    }
}
