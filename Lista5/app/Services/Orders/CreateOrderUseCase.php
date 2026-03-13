<?php

namespace App\Services\Orders;

use App\Domain\Orders\Dto\CreateOrderDTO;
use App\Domain\Orders\Entity\Order as DomainOrder;
use App\Domain\Orders\Interfaces\OrderRepositoryInterface;

class CreateOrderUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository
    ) {}

    public function execute(CreateOrderDTO $dto): DomainOrder
    {
        $order = DomainOrder::createNew(
            userId: $dto->userId,
            productName: $dto->productName,
            amount: $dto->amount,
        );

        return $this->orderRepository->save($order);
    }
}
