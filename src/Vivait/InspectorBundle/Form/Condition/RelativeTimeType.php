<?php

namespace Vivait\InspectorBundle\Form\Condition;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vivait\InspectorBundle\Form\ConditionType;

class RelativeTimeType extends ConditionType
{
    protected $dataClass = 'Vivait\InspectorBundle\Entity\Condition\RelativeTime';
    protected $label     = 'Relative Time';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('expression', 'text', [
              'label' => 'Perform after'
          ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vivait_inspectorbundle_condition_relativetime';
    }
}
