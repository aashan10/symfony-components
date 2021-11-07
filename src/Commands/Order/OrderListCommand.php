<?php

namespace Aashan\Workflow\Commands\Order;

use Aashan\Workflow\Collections\Order\OrderCollection;
use Aashan\Workflow\Helpers\StorageHelper;
use Aashan\Workflow\Models\Order;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OrderListCommand extends Command
{

    protected static $defaultName = 'order:list';

    protected function configure()
    {
        $this->setDescription('List all orders!');

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $table = new Table($output);
        $table->setHeaderTitle('<info> Order List </info>');
        $table->setHeaders(['SN', 'Order Number', 'SKU', 'Quantity', 'Customer Name', 'Billing Address', 'Shipping Address', 'Status', 'Created At']);

        $rows = [];
        foreach ((new StorageHelper())->getCollection('orders', Order::class, OrderCollection::class) as $index => $order) {
            $rows[$index] = [
                'id' => $index + 1,
                'order_id' => $order->getId(),
                'sku' => $order->getData('sku'),
                'quantity' => $order->getData('quantity'),
                'customer_name' => $order->getData('customer_name'),
                'billing_address' => $order->getData('billing_address'),
                'shipping_address' => $order->getData('shipping_address'),
                'status' => $order->getData('status'),
                'created_at' => $order->getData('created_at')
            ];
        }
        $table->setRows($rows);
        $table->render();

        return Command::SUCCESS;
    }

}