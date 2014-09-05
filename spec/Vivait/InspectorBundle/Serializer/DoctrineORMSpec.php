<?php

namespace spec\Vivait\InspectorBundle\Serializer;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Vivait\InspectorBundle\Serializer\DoctrineORM;

/**
 * @mixin DoctrineORM
 */
class DoctrineORMSpec extends ObjectBehavior
{
    function let(EntityManagerInterface $entityManager, UnitOfWork $unitOfWork)
    {
        $entityManager->getUnitOfWork()
          ->willReturn($unitOfWork);
        $this->beConstructedWith($entityManager);
    }

    function it_can_serialize_a_scalar_array()
    {
        $data = [
          'I',
          'am' => 'an',
          2    => 'array',
          3
        ];

        $result = $this->serialize($data);
        $result->shouldBeJsonEncodable();

        $this->deserialize($result)->shouldBe($data);
    }

    function it_can_serialize_a_multidimensional_array()
    {
        $data = [
          'This',
          [
            'is' => 'a test'
          ]
        ];

        $result = $this->serialize($data);
        $result->shouldBeJsonEncodable();

        $this->deserialize($result)->shouldBe($data);
    }

    function it_can_serialize_an_entity(
      EntityManagerInterface $entityManager,
      UnitOfWork $unitOfWork,
      ObjectRepository $repository
    ) {
        $entity1 = new \stdClass();
        $entity1->id = 5;
        $entity1->name = 'Test';

        // These calls are used to find the primary key of an entity
        $entityManager->contains($entity1)->willReturn(true);
        $unitOfWork->getSingleIdentifierValue($entity1)->willReturn($entity1->id);

        $data = [
          $entity1
        ];

        $result = $this->serialize($data);
        $result->shouldBeJsonEncodable();

        // These calls are used to pull an entity from the repository/database
        $entityManager->getRepository(get_class($entity1))->willReturn($repository);
        $repository->find($entity1->id)->willReturn($entity1);

        $this->deserialize($result)->shouldBe($data);
    }

    public function getMatchers()
    {
        return [
          'beJsonEncodable' => function ($subject, &$decoded = null) {
              $encoded = json_encode($subject);
              $encoding_error = json_last_error();

              $decoded = json_decode($encoded, true);
              $decoding_error = json_last_error();

              return ($decoding_error === JSON_ERROR_NONE) && ($encoding_error === JSON_ERROR_NONE);
          }
        ];
    }
}
