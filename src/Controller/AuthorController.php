<?php

namespace App\Controller;

use App\Request\AuthorRequest;
use App\Entity\Author;
use App\Service\AuthorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthorController extends AbstractController
{
    #[Route('/authors', name: 'authors_store', methods: ['POST'])]
    public function store(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager, AuthorService $service): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $authorRequest = new AuthorRequest($entityManager, $data);
        $errors = $validator->validate($authorRequest);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return $this->json(['errors' => $errorsString]);
        }

        $service->store($data);

        return $this->json(['message' => 'Author was created']);
    }

    #[Route('/authors/{id}', name: 'authors_delete', methods: ['DELETE'])]
    public function delete(AuthorService $service, Author $author): JsonResponse
    {
        $service->delete($author);

        return $this->json(['message' => 'Author was deleted']);
    }
}
