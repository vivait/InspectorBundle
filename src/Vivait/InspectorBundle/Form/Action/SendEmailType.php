<?php

namespace Vivait\InspectorBundle\Form\Action;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Vivait\InspectorBundle\Form\ConditionType;

/**
 * @DI\FormType
 */
class SendEmailType extends ConditionType
{
    protected $dataClass = 'Vivait\InspectorBundle\Entity\Action\SendEmail';
    protected $label     = 'Send Email';

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('recipient');
        $builder->add('sender');
        $builder->add('Message', 'textarea');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vivait_inspectorbundle_action_sendemail';
    }
}
