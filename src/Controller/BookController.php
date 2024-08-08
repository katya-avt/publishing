<?php

namespace App\Controller;

use App\Request\BookRequest;
use App\Entity\Book;
use App\Serializer\BookSerializer;
use App\Service\BookService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookController extends AbstractController
{
    #[Route('/books', name: 'books_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, BookSerializer $serializer): JsonResponse
    {
        $books = $entityManager->getRepository(Book::class)->findAll();

        $data = $serializer->getNormalizedData($books);

        return $this->json($data);
    }

    #[Route('/books', name: 'books_store', methods: ['POST'])]
    public function store(Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager, BookService $service): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $bookRequest = new BookRequest($entityManager, $data);
        $errors = $validator->validate($bookRequest);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return $this->json(['errors' => $errorsString]);
        }

        $service->store($data);

        return $this->json(['message' => 'Book was created']);
    }

    #[Route('/books/{id}', name: 'books_delete', methods: ['DELETE'])]
    public function delete(BookService $service, Book $book): JsonResponse
    {
        $service->delete($book);

        return $this->json(['message' => 'Book was deleted']);
    }
}
