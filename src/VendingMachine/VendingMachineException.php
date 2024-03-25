<?php

declare(strict_types=1);

namespace App\VendingMachine;

final class VendingMachineException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct(message: $message);
    }
}
