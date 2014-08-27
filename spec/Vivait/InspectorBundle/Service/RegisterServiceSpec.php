<?php

namespace spec\Vivait\InspectorBundle\Service;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Vivait\Voter\Model\ActionInterface;
use Vivait\InspectorBundle\Entity\Inspection;
use Vivait\InspectorBundle\Service\Inspection\RegisterInspection;
use Vivait\Voter\Model\VoterInterface;

/**
 * @mixin \Vivait\InspectorBundle\Service\Inspection\RegisterInspection
 */
class RegisterServiceSpec extends ObjectBehavior
{
    public function let(EventDispatcherInterface $dispatcher)
    {
        $this->beConstructedWith($dispatcher);
    }

    public function it_will_register_an_event(
      EventDispatcherInterface $dispatcher,
      VoterInterface $voter,
      Inspection $event,
      ActionInterface $action1
    ){
        $event->getActions()->willReturn([$action1]);
        $event->getVoter()->willReturn($voter);
        $event->getEventName()->willReturn('myevent');

        $dispatcher->addListener('myevent', Argument::any())->willReturn()->shouldBeCalled();

        $this->registerInspections($event);
    }

    public function it_will_register_an_array_of_events(
      EventDispatcherInterface $dispatcher,
      VoterInterface $voter,
      Inspection $event1,
      Inspection $event2,
      ActionInterface $action1,
      ActionInterface $action2
    ){
        $event1->getActions()->willReturn([$action1, $action2]);
        $event1->getVoter()->willReturn($voter);
        $event1->getEventName()->willReturn('myevent');

        $event2->getActions()->willReturn([$action2]);
        $event2->getVoter()->willReturn($voter);
        $event2->getEventName()->willReturn('anotherevent');

        $dispatcher->addListener('myevent', Argument::any())->willReturn()->shouldBeCalled();
        $dispatcher->addListener('anotherevent', Argument::any())->willReturn()->shouldBeCalled();

        $this->registerInspections([$event1, $event2]);
    }
}
