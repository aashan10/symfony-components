<?php

namespace Aashan\Workflow\Commands\Order;

use Aashan\Workflow\Helpers\StorageHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OrderDeleteCommand extends Command
{

    protected static $defaultName = 'order:delete';
    protected StorageHelper $storageHelper;

    protected function configure()
    {
        $this->addArgument('order_id', InputArgument::REQUIRED, 'Enter the order ID:');
        $this->setDescription('Delete order');
        $this->storageHelper = new StorageHelper();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $orderId = $input->getArgument('order_id');
        $order = $this->storageHelper->getOrderCollection()->find($orderId);

        if (!$order) {
            throw new \Exception(sprintf('Order id %s not found!', $orderId));
        }

        $orderCollection = $this->storageHelper->getOrderCollection()->filter(function($orderModel) use($order) {
            return $orderModel->getData('order_id') !== $order->getData('order_id');
        });

        $this->storageHelper->saveCollection('orders', $orderCollection);
        $output->writeln('<info>Order Deleted Successfully!</info>');

        $command = $this->getApplication()->find('order:list');
        $command->run($input, $output);
        return Command::SUCCESS;
    }

}