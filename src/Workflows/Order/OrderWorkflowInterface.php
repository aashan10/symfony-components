<?php

namespace Aashan\Workflow\Workflows\Order;

use Aashan\Workflow\Models\Order;
use Aashan\Workflow\Workflows\WorkflowInterface;

interface OrderWorkflowInterface extends WorkflowInterface
{
    const CANCEL = 'cancel';
    const DELIVER = 'deliver';
    const RETURN = 'return';
    const VERIFY_PAYMENT = 'verify_payment';
    const VERIFY_COD = 'verify_cod';
    const SCHEDULE_FOR_DELIVERY = 'schedule_for_delivery';
    const SHIP = 'ship';

    public function canCancel(Order $order): bool;
    public function canDeliver(Order $order): bool;
    public function canReturn(Order $order): bool;
    public function canShip(Order $order): bool;
    public function canVerifyPayment(Order $order): bool;
    public function canVerifyCod(Order $order): bool;
    public function canScheduleForDelivery(Order $order): bool;

}