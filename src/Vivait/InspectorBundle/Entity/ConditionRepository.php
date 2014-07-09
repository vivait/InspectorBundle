<?php

namespace Vivait\InspectorBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Vivait\Common\Model\Poly\PolymorphicRepositoryInterface;
use Vivait\Common\Model\Poly\PolymorphicRepositoryTrait;

class ConditionRepository extends EntityRepository implements PolymorphicRepositoryInterface
{
    use PolymorphicRepositoryTrait;
}
