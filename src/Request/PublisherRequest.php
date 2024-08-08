<?php

namespace App\Request;

use App\Entity\Publisher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class PublisherRequest
{
    #[Assert\Type("string")]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    public readonly string $name;

    #[Assert\Type("string")]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Assert\Regex(
        pattern: '/^House: \w+, Street: \w+, City: \w+, Country: \w+/',
        message: 'Address must look like this (replace the values in {}): House: {House}, Street: {Street}, City: {City}, Country: {Country}',
    )]
    public readonly string $address;

    public EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, array $data)
    {
        $this->entityManager = $entityManager;

        $this->name = $data['name'];
        $this->address = $data['address'];
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, mixed $payload): void
    {
        $uniqueFields = [
            'name' => $this->name,
            'address' => $this->address
        ];

        if (!$this->isPublisherUnique($uniqueFields)) {
            $context->buildViolation('Such a publisher already exists')
                ->atPath('name')
                ->addViolation();
        }
    }

    public function isPublisherUnique(array $uniqueFields): bool
    {
        $publisher = $this->entityManager->getRepository(Publisher::class)->findOneBy($uniqueFields);

        return $publisher ? false : true;
    }
}
