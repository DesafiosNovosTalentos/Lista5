<?php

namespace App\Infrastructure\Eloquent;

use App\Domain\Orders\Dto\PaginatedOrdersDTO;
use App\Domain\Orders\Entity\Order as DomainOrder;
use App\Domain\Orders\Interfaces\OrderRepositoryInterface;
use App\Exceptions\RepositoryException;
use App\Models\Order;
use Illuminate\Database\QueryException;

class OrderRepository implements OrderRepositoryInterface
{
    public function save(DomainOrder $order): DomainOrder
    {
        try {
            $model = Order::create([
                'id' => $order->getId(),
                'user_id' => $order->getUserId(),
                'product_name' => $order->getProductName(),
                'amount' => $order->getAmount(),
                'status' => $order->getStatus(),
            ]);

            return DomainOrder::fromArray($model->toArray());
        } catch (QueryException) {
            throw new RepositoryException('Falha ao salvar o pedido.');
        }
    }

    public function findById(string $id): ?DomainOrder
    {

        try {
            $model = Order::find($id);

            if (! $model) {
                return null;
            }

            return DomainOrder::fromArray($model->toArray());
        } catch (QueryException) {
            throw new RepositoryException('Falha ao consultar o pedido.');
        }
    }

    public function findAll(int $page = 1, int $limit = 3): PaginatedOrdersDTO
    {

        try {
            $total = Order::count();
            $offset = ($page - 1) * $limit;

            $models = Order::offset($offset)->limit($limit)->get();

            $orders = [];
            foreach ($models as $model) {
                $orders[] = DomainOrder::fromArray($model->toArray());
            }

            return new PaginatedOrdersDTO(
                $orders,
                $total,
                $page,
                $limit
            );
        } catch (QueryException) {
            throw new RepositoryException('Falha ao consultar os pedidos.');
        }
    }

    public function update(DomainOrder $order): DomainOrder
    {
        try {
            Order::where('id', $order->getId())->update([
                'user_id' => $order->getUserId(),
                'product_name' => $order->getProductName(),
                'amount' => $order->getAmount(),
                'status' => $order->getStatus(),
            ]);

            return $order;
        } catch (QueryException) {
            throw new RepositoryException('Falha ao atualizar o pedido.');
        }
    }

    public function delete(string $id): void
    {
        try {
            Order::where('id', $id)->delete();
        } catch (QueryException) {
            throw new RepositoryException('Falha ao deletar o pedido.');
        }
    }
}
