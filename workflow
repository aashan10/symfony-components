#!/usr/bin/php
<?php

require "vendor/autoload.php";
const BASE_PATH = __DIR__;

use Aashan\Workflow\Commands\Order\OrderCreateCommand;
use Aashan\Workflow\Commands\Order\OrderDeleteCommand;
use Aashan\Workflow\Commands\Order\OrderEditCommand;
use Aashan\Workflow\Commands\Order\OrderListCommand;
use Symfony\Component\Console\Application;

$application = new Application('Workflow Example', '1.0.1');
//$application->addCommands([new OrderCommand()]);
$application->addCommands([new OrderListCommand()]);
$application->addCommands([new OrderEditCommand()]);
$application->addCommands([new OrderDeleteCommand()]);
$application->addCommands([new OrderCreateCommand()]);

try {
    $application->run();
} catch (Exception $e) {
}