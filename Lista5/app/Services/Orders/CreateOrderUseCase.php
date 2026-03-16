<?php

namespace App\Services\Orders;

use App\Domain\Orders\Dto\CreateOrderDTO;
use App\Domain\Orders\Entity\Order as DomainOrder;
use App\Domain\Orders\Interfaces\OrderRepositoryInterface;
use App\Jobs\SendOrderNotificationJob;

class CreateOrderUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository
    ) {}

    public function execute(CreateOrderDTO $dto): DomainOrder
    {
        $order = DomainOrder::createNew(
            userId: $dto->user_id,
            productName: $dto->product_name,
            amount: $dto->amount,
        );

        $order = $this->orderRepository->save($order);

        SendOrderNotificationJob::dispatch($order);

        return $order;
    }
}
