<?php

namespace Vivait\InspectorBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Vivait\Common\Model\Poly\PolymorphicRepositoryInterface;
use Vivait\Common\Model\Poly\PolymorphicRepositoryTrait;

class ActionRepository extends EntityRepository implements PolymorphicRepositoryInterface
{
    use PolymorphicRepositoryTrait;

    public function getFormTypes()
    {
        return array_map(
          function (Action $action) {
              return $action->getFormType();
          },
          $this->generateAllPolyObjects()
        );
    }
}
