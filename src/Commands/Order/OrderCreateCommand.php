<?php

namespace Aashan\Workflow\Commands\Order;

use Aashan\Workflow\Models\Order;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class OrderCreateCommand extends Command
{
    protected static $defaultName = 'order:create';

    protected function configure()
    {
        $this->setDescription('Create Order');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $questions = [
            'sku' => 'Product SKU',
            'quantity' => 'Product Quantity',
            'customer_name' => 'Customer full name',
            'billing_address' => 'Billing Address',
            'shipping_address' => 'Shipping Address',
            'payment_method' => [
                'title' => 'Payment Method',
                'options' => [
                    'Cash On Delivery',
                    'Khalti',
                    'eSewa',
                    'Paypal'
                ]
            ]
        ];
        $helper = $this->getHelper('question');
        $data = [
            'order_id' => rand(4200000000, 4209999999),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];
        foreach ($questions as $index => $question) {
            if (is_string($question) || is_numeric($question)) {
                $object = new Question($question . ": \n");
            } else if (is_array($question)) {
                $object = new ChoiceQuestion($question['title'], $question['options']);
            } else {
                throw new \Exception('Unknown question format!');
            }
            $data[$index] = $helper->ask($input, $output, $object);
        }
        $order = new Order($data);
        $order->save();

        $output->writeln('<info>Order Created Successfully!</info>');

        $command = $this->getApplication()->find('order:list');
        $command->run($input, $output);
        return Command::SUCCESS;
    }

}