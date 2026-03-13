<?php

namespace App\Infrastructure\Eloquent;

use App\Models\Order;
use App\Domain\Orders\Entity\Order as DomainOrder;
use App\Domain\Orders\Interfaces\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderRepository implements OrderRepositoryInterface
{
    public function save(DomainOrder $order): DomainOrder
    {
        $model = Order::create([
            'id'           => $order->getId(),
            'user_id'      => $order->getUserId(),
            'product_name' => $order->getProductName(),
            'amount'       => $order->getAmount(),
            'status'       => $order->getStatus()->value,
        ]);

        return DomainOrder::fromArray($model->toArray());
    }

    public function findById(string $id): DomainOrder
    {
        $model = Order::find($id);

        if (!$model) {
            throw new ModelNotFoundException("Pedido {$id} não encontrado.");
        }

        return DomainOrder::fromArray($model->toArray());
    }

    public function findAll(): array
    {
        $orders = [];

        foreach (Order::all() as $model) {
            $orders[] = DomainOrder::fromArray($model->toArray());
        }

        return $orders;
    }

    public function update(DomainOrder $order): DomainOrder
    {
        $model = Order::find($order->getId());

        if (!$model) {
            throw new  ModelNotFoundException("Pedido {$order->getId()} não encontrado.");
        }

        $model->update([
            'user_id'      => $order->getUserId(),
            'product_name' => $order->getProductName(),
            'amount'       => $order->getAmount(),
            'status'       => $order->getStatus()->value,
        ]);

        return DomainOrder::fromArray($model->fresh()->toArray());
    }

    public function delete(string $id): void
    {
        $model = Order::find($id);

        if (!$model) {
            throw new  ModelNotFoundException("Pedido {$id} não encontrado.");
        }

        $model->delete();
    }
}
