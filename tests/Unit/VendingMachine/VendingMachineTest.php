<?php

declare(strict_types=1);

namespace Unit\VendingMachine;

use App\VendingMachine\VendingMachine;
use App\VendingMachine\VendingMachineException;
use PHPUnit\Framework\TestCase;

class VendingMachineTest extends TestCase
{
    private array $coins;
    private array $products;

    public function setUp(): void
    {
        $this->products = ['Coca-cola' => 1.50, 'Snickers' => 1.20];
        $this->coins = [0.25, 0.5, 1];
    }

    /**
     * @covers App\VendingMachine\VendingMachine
     * @throws VendingMachineException
     */
    public function testVendingMachineSuccess()
    {
        $vendingMachine = new VendingMachine($this->products, $this->coins);

        $this->assertSame($vendingMachine->getProducts(), $this->products);

        $vendingMachine->selectProduct('Coca-cola');

        $this->assertSame($vendingMachine->getProductForPurchase(), 'Coca-cola');
        $this->assertSame($vendingMachine->getSelectedProductPrice(), 1.50);

        $this->assertNull($vendingMachine->insertCoin(0.5));

        $this->assertSame(0.5, $vendingMachine->getInsertedCoins());

        $this->assertNull($vendingMachine->insertCoin(0.5));

        $this->assertSame(0.5, $vendingMachine->insertCoin(1.0));

        $this->assertNull($vendingMachine->getProductForPurchase());
        $this->assertSame(0.0, $vendingMachine->getInsertedCoins());
    }

    /**
     * @covers App\VendingMachine\VendingMachine
     * @throws VendingMachineException
     */
    public function testVendingMachineErrors()
    {
        $vendingMachine = new VendingMachine($this->products, $this->coins);

        $this->expectException(VendingMachineException::class);

        $vendingMachine->selectProduct('Pepsi');

        $vendingMachine->selectProduct('Coca-cola');
        $this->assertSame($vendingMachine->getProductForPurchase(), 'Coca-cola');

        $this->expectException(VendingMachineException::class);
        $vendingMachine->insertCoin(2.0);
    }
}