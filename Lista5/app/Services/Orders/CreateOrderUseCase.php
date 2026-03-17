<?php

namespace App\Services\Orders;

use App\Domain\Orders\Dto\CreateOrderDTO;
use App\Domain\Orders\Entity\Order as DomainOrder;
use App\Domain\Orders\Interfaces\OrderRepositoryInterface;
use App\Jobs\SendOrderNotificationJob;

class CreateOrderUseCase
{
    public function __construct(
        private OrderRepositoryInterface $order_repository
    ) {}

    public function execute(CreateOrderDTO $dto): DomainOrder
    {
        $order = DomainOrder::createNew(
            $dto->user_id,
            $dto->product_name,
            $dto->amount,
        );

        $order = $this->order_repository->save($order);

        SendOrderNotificationJob::dispatch($order);

        return $order;
    }
}
