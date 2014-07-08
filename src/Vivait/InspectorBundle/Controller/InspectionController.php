<?php

namespace Vivait\InspectorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Vivait\BootstrapBundle\Controller\Controller;
use Vivait\InspectorBundle\Entity\Inspection;
use Vivait\InspectorBundle\Entity\InspectionRepository;
use Vivait\InspectorBundle\Event\InspectionEvent;

class InspectionController extends Controller
{
    /**
     * @var InspectionRepository
     */
    private $repository;

    function getRepository()
    {
        if (!$this->repository) {
            $this->repository = $this->get('doctrine.orm.entity_manager')->getRepository(
              'VivaitInspectorBundle:Inspection'
            );
        }

        return $this->repository;
    }

    public function indexAction() {
        $inspections = $this->getRepository()->findAll();

        return $this->render('VivaitInspectorBundle:Inspection:list.html.twig', array('inspections' => $inspections));
    }

    public function newAction(Request $request)
    {
        return $this->editAction($request, new Inspection(), true);
    }

    public function editAction(Request $request, Inspection $inspection, $is_new = false)
    {
        $form = $this->createForm('vivait_inspectorbundle_inspection', $inspection, [
              'delete_button' => [$this, 'delete']
          ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getRepository()->save($inspection);

            $this->dispatchEntityEvent(new InspectionEvent($inspection));

            return $this->redirectBack($request);
        }

        return $this->render(
          'VivaitInspectorBundle:Inspection:form.html.twig',
          [
            'form' => [
              'title' => ($is_new ? 'New' : 'Edit') . ' Inspection',
              'form' => $form->createView()
            ]
          ]
        );
    }

    public function delete(Inspection $inspection)
    {
        $this->getRepository()->delete($inspection);
        $this->dispatchEntityEvent(new InspectionEvent($inspection));
    }
}
