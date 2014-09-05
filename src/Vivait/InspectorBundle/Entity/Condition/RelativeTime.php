<?php

namespace Vivait\InspectorBundle\Entity\Condition;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Vivait\InspectorBundle\Entity\Condition;
use Vivait\InspectorBundle\Form\ActionType;
use Vivait\InspectorBundle\Model\InspectionAware;
use Vivait\InspectorBundle\Model\InspectionAwareTrait;
use Vivait\Voter\Model\ConditionInterface;

/**
 * Time
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class RelativeTime extends Condition implements InspectionAware
{
    /**
     * @var string
     *
     * @ORM\Column(name="expression", type="string")
     */
    private $expression;

    /**
     * Gets expression
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * Sets expression
     * @param string $expression
     * @return $this
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;

        return $this;
    }

    /**
     * @param ContainerInterface $container
     * @return object
     */
    public function loadService(ContainerInterface $container)
    {
        $queue = $container->get('vivait_inspector.condition.queue');
        $queue->setInspection($this->getInspection());

        return $queue;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('Expression: %s', $this->getExpression());
    }

    /**
     * @return ActionType
     */
    public function getFormType()
    {
        return 'vivait_inspectorbundle_condition_relativetime';
    }
}
