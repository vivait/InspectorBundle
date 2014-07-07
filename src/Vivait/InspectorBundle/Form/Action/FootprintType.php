<?php

namespace Vivait\InspectorBundle\Form\Action;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Vivait\InspectorBundle\Form\ConditionType;

/**
 * @DI\FormType
 */
class FootprintType extends ConditionType
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
