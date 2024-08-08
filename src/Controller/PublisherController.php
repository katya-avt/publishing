<?php

namespace App\Controller;

use App\Request\PublisherRequest;
use App\Entity\Publisher;
use App\Service\PublisherService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PublisherController extends AbstractController
{
    #[Route('/publishers/{id}', name: 'publishers_update', methods: ['PUT'])]
    public function update(Request $request, ValidatorInterface $validator, PublisherService $service, EntityManagerInterface $entityManager, Publisher $publisher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $publisherRequest = new PublisherRequest($entityManager, $data);
        $errors = $validator->validate($publisherRequest);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return $this->json(['errors' => $errorsString]);
        }

        $service->update($publisher, $data);

        return $this->json(['message' => 'Publisher was updated']);
    }

    #[Route('/publishers/{id}', name: 'publishers_delete', methods: ['DELETE'])]
    public function delete(PublisherService $service, Publisher $publisher): JsonResponse
    {
        $service->delete($publisher);

        return $this->json(['message' => 'Publisher was deleted']);
    }
}
