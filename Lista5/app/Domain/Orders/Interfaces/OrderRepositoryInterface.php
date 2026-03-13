<?php

namespace App\Domain\Orders\Interfaces;

use App\Domain\Orders\Entity\Order;

interface OrderRepositoryInterface
{
    public function save(Order $order): Order;
    public function findById(string $id): Order;
    public function findAll(): array;
    public function delete(string $id): void;
    public function update(Order $order): Order;
}
