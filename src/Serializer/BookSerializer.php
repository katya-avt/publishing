<?php

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class BookSerializer
{
    public SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function getNormalizedData($books)
    {
        return $this->serializer->normalize($books, null,
            [
                AbstractNormalizer::ATTRIBUTES => [
                    'id',
                    'name',
                    'publication_year',
                    'publisher' => ['name'],
                    'authors' => ['surname']
                ]
            ]);
    }
}
