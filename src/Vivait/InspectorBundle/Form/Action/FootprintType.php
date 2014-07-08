<?php

namespace Vivait\InspectorBundle\Form\Action;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vivait\InspectorBundle\Form\ActionType;
use Vivait\InspectorBundle\Form\ConditionType;

class FootprintType extends ActionType
{
    protected $dataClass = 'Vivait\InspectorBundle\Entity\Action\Footprint';
    protected $label     = 'Canned Message';

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        $builder->add('cannedmessage', 'entity', array(
            'class'       => 'VivaitFootprintBundle:CannedMessage',
            'required'    => true,
            'property'    => 'name',
            'group_by'    => 'category',
            'label'       => 'Canned message'
          ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vivait_inspectorbundle_action_footprint';
    }
}
