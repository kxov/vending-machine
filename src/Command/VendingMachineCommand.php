<?php

declare(strict_types=1);

namespace App\Command;

use App\VendingMachine\VendingMachine;
use App\VendingMachine\VendingMachineException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

final class VendingMachineCommand extends Command
{
    public function __construct(
        private readonly VendingMachine $vendingMachine
    )
    {
        parent::__construct('Vending machine');
    }

    /**
     * @throws VendingMachineException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Please select your product',
            $this->vendingMachine->getProducts(),
            0
        );
        $question->setErrorMessage('Product %s is invalid.');

        $selectedProduct = $helper->ask($input, $output, $question);
        $this->vendingMachine->selectProduct($selectedProduct);

        $output->writeln('You have just selected: ' . $selectedProduct);

        $selectedProductPrice = $this->vendingMachine->getSelectedProductPrice();
        $output->writeln('Price of selected product: ' . $selectedProductPrice);

        $overCoins = null;
        while (is_null($overCoins)) {
            $output->writeln('Insert coin:');
            $coin = $helper->ask($input, $output, new ChoiceQuestion(
                'Choose a coin',
                $this->vendingMachine->getSupportCoins()
            ));

            $overCoins = $this->vendingMachine->insertCoin($coin);

            if ($this->vendingMachine->getInsertedCoins() > 0) {
                $output->writeln('Total inserted: ' . $this->vendingMachine->getInsertedCoins());
            }
        }

        $output->writeln('Thank you for your purchase!');

        if ($overCoins > 0) {
            $output->writeln('Get back odd money: ' . $overCoins);
        }

        return Command::SUCCESS;
    }
}
