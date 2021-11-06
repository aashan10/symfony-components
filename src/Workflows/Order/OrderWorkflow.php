<?php

namespace Aashan\Workflow\Workflows\Order;

use Aashan\Workflow\Models\Order;
use Aashan\Workflow\Workflows\AbstractWorkflow;
use Aashan\Workflow\Workflows\WorkflowInterface;
use Symfony\Component\Workflow\Transition;

class OrderWorkflow extends AbstractWorkflow implements OrderWorkflowInterface
{
    public function canCancel(Order $order): bool
    {
        return $this->workflow->can($order, self::CANCEL);
    }

    public function canDeliver(Order $order): bool
    {
        return $this->workflow->can($order, self::DELIVER);
    }

    public function canReturn(Order $order): bool
    {
        return $this->workflow->can($order, self::RETURN);
    }

    public function canVerifyPayment(Order $order): bool
    {
        return $this->workflow->can($order, self::VERIFY_PAYMENT);
    }

    public function canVerifyCod(Order $order): bool
    {
        return $this->workflow->can($order, self::VERIFY_COD);
    }

    public function canScheduleForDelivery(Order $order): bool
    {
        return $this->workflow->can($order, self::SCHEDULE_FOR_DELIVERY);
    }

    public function initializeTransitions(): WorkflowInterface
    {
        $this->transitions = [
            // Cancel Transitions
            new Transition(self::CANCEL, ['pending'], 'canceled'),
            new Transition(self::CANCEL, ['cod_authorized'], 'canceled'),
            new Transition(self::CANCEL, ['prepaid_authorized'], 'canceled'),
            new Transition(self::CANCEL, ['scheduled_for_delivery'], 'canceled'),

            // Deliver Transitions
            new Transition(self::DELIVER, ['shipped'], ['delivered']),
            new Transition(self::SCHEDULE_FOR_DELIVERY, ['prepaid_authorized'], 'scheduled_for_delivery'),
            new Transition(self::SCHEDULE_FOR_DELIVERY, ['cod_authorized'], 'scheduled_for_delivery'),

            // Payment Verification Transitions
            new Transition(self::VERIFY_COD, 'pending', 'cod_authorized'),
            new Transition(self::VERIFY_PAYMENT, 'pending', 'prepaid_authorized'),

            // Return Transitions
            new Transition(self::RETURN, 'delivered', 'returned'),

            // Ship Transitions
            new Transition(self::SHIP, 'scheduled_for_delivery', 'shipped')
        ];

        return $this;
    }

    public function initializePlaces(): WorkflowInterface
    {
        $this->places = [
            'pending',
            'cod_authorized',
            'prepaid_authorized',
            'scheduled_for_delivery',
            'delivered',
            'canceled',
            'shipped',
            'returned'
        ];
        return $this;
    }

    public function canShip(Order $order): bool
    {
        return $this->workflow->can($order, self::SHIP);
    }

    /**
     * @param Order $subject
     * @param string $state
     * @return order
     */
    public function apply(object $subject, string $state): object
    {
        return parent::apply($subject, $state);
    }
}
