<?php

namespace Vivait\InspectorBundle\Entity\Condition;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Vivait\InspectorBundle\Entity\Condition;
use Vivait\InspectorBundle\Form\ActionType;
use Vivait\Voter\Model\ConditionInterface;

/**
 * Condition
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Vivait\InspectorBundle\Entity\ExpressConditionRepository")
 */
class Expression extends Condition implements ConditionInterface
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
     * @return mixed
     */
    public function requires()
    {
        return [];
    }

    /**
     * Gets the lower-case short name of a class.
     *
     * @param string $className
     *
     * @return string
     */
    private function getShortName($className)
    {
        if (strpos($className, "\\") === false) {
            return strtolower($className);
        }

        $parts = explode("\\", $className);
        return strtolower(end($parts));
    }

    /**
     * @param mixed $entities
     * @return boolean
     */
    public function result($entities)
    {
        $language = new ExpressionLanguage();
        $map = [];

        foreach ($entities as $className =>$entity) {
            $map[$this->getShortName($className)] = $entity;
        }

        return $language->evaluate(
          $this->expression,
          $map
        );
    }

    /**
     * @param ContainerInterface $container
     * @return object
     */
    public function loadService(ContainerInterface $container)
    {
        return $this;
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
        return 'vivait_inspectorbundle_condition_expression';
    }
}
