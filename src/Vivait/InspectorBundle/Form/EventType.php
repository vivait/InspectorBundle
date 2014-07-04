<?php

namespace Vivait\InspectorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\FormType
 */
class EventType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add(
            'eventName',
            'choice',
            [
              'label'   => 'Triggered by',
              'choices' => [
                'Queue Change',
                'Each day'
              ]
            ]
          )
          ->add(
            'voterType',
            'choice',
            [
              'choices' => [
                'And',
                'Or'
              ]
            ]
          )
          ->add(
            'conditions',
            'infinite_form_polycollection',
            [
              'types'        => [
                'vivait_inspectorbundle_condition_expression'
              ],
              'allow_add'    => true,
              'allow_delete' => true,
              'by_reference' => false,
              'options'      => [
                'label_render'                   => false,
                'horizontal_input_wrapper_class' => "col-lg-8",
              ]
            ]
          )
          ->add(
            'actions',
            'infinite_form_polycollection',
            [
              'types'        => [
                'vivait_inspectorbundle_action_sendemail'
              ],
              'allow_add'    => true,
              'allow_delete' => true,
              'by_reference' => false,
              'options'      => [
                'label_render'                   => false,
                'horizontal_input_wrapper_class' => "col-lg-8",
              ]
            ]
          );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
          array(
            'data_class' => 'Vivait\InspectorBundle\Entity\Event'
          )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vivait_inspectorbundle_event';
    }
}
