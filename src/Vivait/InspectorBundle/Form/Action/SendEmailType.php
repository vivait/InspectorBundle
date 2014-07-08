<?php

namespace Vivait\InspectorBundle\Form\Action;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vivait\InspectorBundle\Form\ActionType;
use Vivait\InspectorBundle\Form\ConditionType;

class SendEmailType extends ActionType
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
