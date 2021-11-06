<?php

namespace Aashan\Workflow\Commands\Order;

use Aashan\Workflow\Helpers\StorageHelper;
use Aashan\Workflow\Models\Order;
use Aashan\Workflow\Workflows\Order\OrderWorkflow;
use Aashan\Workflow\Workflows\Order\OrderWorkflowInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Workflow\Transition;

class OrderEditCommand extends Command
{

    protected static $defaultName = 'order:edit';
    protected StorageHelper $storageHelper;


    protected function configure()
    {
        $this->addArgument('order_id', InputArgument::REQUIRED, 'Enter the order ID:');
        $this->setDescription('Edit order');
        $this->storageHelper = new StorageHelper();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $orderId = $input->getArgument('order_id');
        $order = $this->storageHelper->getOrderCollection()->find($orderId);

        foreach ($order->getData() as $index => $value) {
            $helper = $this->getHelper('question');
            if ($index === 'status') {
                $workflow = new OrderWorkflow();
                $transitions = $workflow->getAvailableTransitions($order);
                $options = [
                    $order->getStatus()
                ];
                foreach ($transitions as $transition) {
                    $options[] = $transition;
                }
                $question = new ChoiceQuestion(sprintf('State (%s):' . PHP_EOL, $value), $options, 0);
            } else {
                $question = new Question(sprintf('%s(%s):' . PHP_EOL, $index, $value), $value);
            }

            $answer = $helper->ask($input, $output, $question);

            if ($index === 'status' && $order->getStatus() !== $value) {
                $workflow->apply($order, $value);
            } else {
                if (!$answer) {
                    $answer = $value;
                }
                $order->setData($index, $answer);
            }
            $order->save();
        }

        $output->writeln('<info>Order Edited Successfully!</info>');

        $command = $this->getApplication()->find('order:list');
        $command->run($input, $output);

        return Command::SUCCESS;
    }

}