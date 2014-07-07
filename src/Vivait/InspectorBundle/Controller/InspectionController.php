<?php

namespace Vivait\InspectorBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Vivait\BootstrapBundle\Controller\Controller;
use JMS\DiExtraBundle\Annotation as DI;
use Vivait\InspectorBundle\Entity\Inspection;
use Vivait\InspectorBundle\Entity\InspectionRepository;
use Vivait\InspectorBundle\Event\InspectionEvent;

class InspectionController extends Controller {
    /**
     * @var InspectionRepository
     */
    protected $repository;

    /**
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.entity_manager"),
     * })
     */
    function __construct(EntityManagerInterface $em) {
        $this->repository        = $em->getRepository('VivaitInspectorBundle:Inspection');
    }

    public function newAction(Request $request) {
       
        return $this->editAction($request, new Inspection(), true);
    }

    public function editAction(Request $request, Inspection $inspection, $is_new = false) {
        $form = $this->createForm('vivait_inspectorbundle_inspection', $inspection);
        $form->handleRequest($request);

        if ($form->isValid()) {


            $this->repository->save($inspection);

            $this->dispatchEntityEvent(new InspectionEvent($inspection));

            return $this->redirectBack($request);
        }

        return $this->render('VivaitInspectorBundle:Inspection:form.html.twig', [
            'form' => [
              'title' => ($is_new ? 'New' : 'Edit') . ' Inspection',
              'form'  => $form->createView()
            ]
          ]);
    }

    private function delete(Inspection $inspection) {
        $this->repository->delete($inspection);
        $this->dispatchEntityEvent(new InspectionEvent($inspection));
    }
}
