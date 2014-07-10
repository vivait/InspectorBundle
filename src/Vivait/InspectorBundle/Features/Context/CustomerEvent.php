<?php

namespace Vivait\InspectorBundle\Features\Context;

use Symfony\Component\EventDispatcher\Event;
use Viva\BravoBundle\Entity\Customer;
use Vivait\Voter\Model\EntityEvent as EntityEventInterface;

class CustomerEvent extends Event implements EntityEventInterface
{
    /**
     * @var Customer
     */
    private $customer;

    /**
     * @param Customer $customer
     */
    function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Gets customer
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Sets customer
     * @param Customer $customer
     * @return $this
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    public function provides()
    {
        return [
          'Viva\BravoBundle\Entity\Customer' => $this->customer
        ];
    }

    public function getEvents()
    {
        return ['customer.update' => 'Customer update'];
    }
}