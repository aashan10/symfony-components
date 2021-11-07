<?php

namespace Aashan\Workflow\Commands\Order;

use Aashan\Workflow\Models\Order;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class OrderViewCommand extends Command
{

    protected static $defaultName = 'order:view';

    protected function configure()
    {
        $this->setDescription('View Order Information');
        $this->addArgument('order_id', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $orderId = $input->getArgument('order_id');
        $order = Order::find($orderId);

        if(!$order) {
            throw new \Exception(sprintf('Order with id %s was not found!', $orderId));
        }

        $styledOutput = new SymfonyStyle($input, $output);
        $styledOutput->section('Order Information');
        $styledOutput->writeln(sprintf("\t<info>Order ID</info>: \t %s", $order->getId()));
        $styledOutput->writeln(sprintf("\t<info>SKU</info>: \t\t %s", $order->getData('sku')));
        $styledOutput->writeln(sprintf("\t<info>Quantity</info>: \t %s", $order->getData('quantity')));
        $styledOutput->writeln(sprintf("\t<info>Status</info>: \t %s", $order->getData('status')));
        $styledOutput->section('Customer Information');
        $styledOutput->writeln(sprintf("\t<info>Customer Name</info>: \t\t %s", $order->getData('customer_name')));
        $styledOutput->writeln(sprintf("\t<info>Billing Address</info>: \t %s", $order->getData('billing_address')));
        $styledOutput->writeln(sprintf("\t<info>Shipping Address</info>: \t %s", $order->getData('shipping_address')));

        $styledOutput->section('Payment Information');
        $styledOutput->writeln(sprintf("\t<info>Payment Method</info>: \t %s", $order->getData('payment_method')));

        return Command::SUCCESS;
    }

}