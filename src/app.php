<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Command\VendingMachineCommand;
use App\VendingMachine\VendingMachine;
use Symfony\Component\Console\Application;

$products = require_once __DIR__ . '/products.php';
$coins = require_once __DIR__ . '/coins.php';

$application = new Application();

$vendingMachine = new VendingMachine($products, $coins);
$vendingMachineCommand = new VendingMachineCommand($vendingMachine);
$application->add($vendingMachineCommand);

$application->setDefaultCommand($vendingMachineCommand->getName(), true);
try {
    $application->run();
} catch (Exception $e) {
}