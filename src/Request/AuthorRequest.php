<?php

namespace App\Request;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AuthorRequest
{
    #[Assert\Type("string")]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    public readonly string $name;

    #[Assert\Type("string")]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    public readonly string $surname;

    public EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, array $data)
    {
        $this->entityManager = $entityManager;

        $this->name = $data['name'];
        $this->surname = $data['surname'];
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, mixed $payload): void
    {
        $uniqueFields = [
            'name' => $this->name,
            'surname' => $this->surname
        ];

        if (!$this->isAuthorUnique($uniqueFields)) {
            $context->buildViolation('Such an author already exists')
                ->atPath('name')
                ->addViolation();
        }
    }

    public function isAuthorUnique(array $uniqueFields): bool
    {
        $author = $this->entityManager->getRepository(Author::class)->findOneBy($uniqueFields);

        return $author ? false : true;
    }
}
