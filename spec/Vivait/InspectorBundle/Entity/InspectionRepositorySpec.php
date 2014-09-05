<?php

namespace spec\Vivait\InspectorBundle\Entity;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Vivait\InspectorBundle\Entity\Inspection;
use Vivait\InspectorBundle\Entity\InspectionRepository;

/**
 * @mixin InspectionRepository
 */
class InspectionRepositorySpec extends ObjectBehavior
{
    function let(
      EntityManager $em,
      ClassMetadata $class,
      EntityManager $em,
      QueryBuilder $queryBuilder,
      AbstractQuery $query
    ) {
        $em->createQueryBuilder()->willReturn($queryBuilder);
        $queryBuilder->select(Argument::any())->willReturn($queryBuilder);
        $queryBuilder->from(Argument::any(), 'i', 'i.id')->willReturn($queryBuilder);
        $queryBuilder->leftJoin(Argument::any(), Argument::any(), Argument::any(), Argument::any())->willReturn(
          $queryBuilder
        );
        $queryBuilder->getQuery()->willReturn($query);

        $this->beConstructedWith($em, $class);
    }

    function it_implements_InspectionProviderInterface()
    {
        $this->shouldHaveType('Vivait\InspectorBundle\Model\InspectionProviderInterface');
    }

    function it_gets_an_array_of_inspections_indexed_by_id(
      AbstractQuery $query,
      Inspection $inspection1,
      Inspection $inspection2
    ) {
        $inspection1->getId()->willReturn(1);
        $inspection2->getId()->willReturn(2);

        $query->getResult()->willReturn(
          [
            1 => $inspection1,
            2 => $inspection2
          ]
        );

        $this->fetchInspections(false)->shouldBe(
          [
            1 => $inspection1,
            2 => $inspection2,
          ]
        );
    }

    function it_gets_an_array_of_lazy_inspections_indexed_by_id(
      AbstractQuery $query
    ) {
        $inspections = [
          1 => [
            'id'        => '1',
            'name'      => 'Inspection 1',
            'eventName' => 'sample.event'
          ],
          2 => [
            'id'        => '2',
            'name'      => 'Inspection 2',
            'eventName' => 'sample.event'
          ]
        ];

        $query->getResult()->willReturn($inspections);

        $this->fetchInspections(true)->shouldBe($inspections);
    }
}
