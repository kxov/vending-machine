<?php

declare(strict_types=1);

namespace App\VendingMachine;

final class VendingMachine
{
    private ?string $productForPurchase = null;

    private float $insertedCoins = 0.0;

    public function __construct(
        private readonly array $products,
        private readonly array $supportCoins
    )
    {
    }

    /**
     * @throws VendingMachineException
     */
    public function selectProduct(string $productName): void
    {
        if (!array_key_exists($productName, $this->products)) {
            throw new VendingMachineException(sprintf('Not valid product %s', $productName));
        }

        $this->productForPurchase = $productName;
    }

    /**
     * @throws VendingMachineException
     */
    public function insertCoin(float $coin): ?float
    {
        if (!$this->productForPurchase) {
            throw new VendingMachineException('Please select product first');
        }

        if (!in_array($coin, $this->supportCoins)) {
            throw new VendingMachineException(sprintf('Not valid coin %s', $coin));
        }

        $this->insertedCoins += $coin;

        if ($this->insertedCoins >= $this->getSelectedProductPrice()) {
            $overCoins = $this->insertedCoins - $this->getSelectedProductPrice();
            $this->reset();

            return $overCoins;
        }

        return null;
    }

    public function getInsertedCoins(): float
    {
        return $this->insertedCoins;
    }

    public function getProductForPurchase(): ?string
    {
        return $this->productForPurchase;
    }

    public function getSelectedProductPrice()
    {
        return $this->products[$this->productForPurchase];
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function getSupportCoins(): array
    {
        return $this->supportCoins;
    }

    private function reset(): void
    {
        $this->insertedCoins = 0.0;
        $this->productForPurchase = null;
    }
}
