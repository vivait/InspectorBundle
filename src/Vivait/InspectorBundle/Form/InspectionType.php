<?php

namespace Vivait\InspectorBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Vivait\InspectorBundle\Entity\Action;
use Vivait\InspectorBundle\Entity\Condition;
use Vivait\InspectorBundle\Service\VoterRegistryService;

class InspectionType extends AbstractType
{
    /**
     * @var ActionType[]
     */
    private $actionTypes;

    /**
     * @var ConditionType[]
     */
    private $conditionTypes;

    /**
     * @var array
     */
    private $voters;

    public function __construct(array $conditionTypes, array $actionTypes, array $voters)
    {
        $this->conditionTypes = $conditionTypes;
        $this->actionTypes = $actionTypes;
        $this->voters = $voters;
    }

    public static function factory(EntityManagerInterface $em, VoterRegistryService $registry)
    {
        return new static(
          array_map(
            function (Condition $condition) {
                return $condition->getFormType();
            },
            $em->getRepository('VivaitInspectorBundle:Condition')->generateAllPolyObjects()
          ),
          array_map(
            function (Action $action) {
                return $action->getFormType();
            },
            $em->getRepository('VivaitInspectorBundle:Action')->generateAllPolyObjects()
          ),
          $registry->getList()
        );
    }

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
              'label' => 'Triggered by',
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
              'choices' => $this->voters
            ]
          )
          ->add(
            'conditions',
            'infinite_form_polycollection',
            [
              'types' => $this->conditionTypes,
              'allow_add' => true,
              'allow_delete' => true,
              'by_reference' => false,
              'options' => [
                'show_child_legend' => true,
                'label_render' => false
              ]
            ]
          )
          ->add(
            'actions',
            'infinite_form_polycollection',
            [
              'types' => $this->actionTypes,
              'allow_add' => true,
              'allow_delete' => true,
              'by_reference' => false,
              'options' => [
                'show_child_legend' => true,
                'label_render' => false
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
            'data_class' => 'Vivait\InspectorBundle\Entity\Inspection'
          )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vivait_inspectorbundle_inspection';
    }
}
