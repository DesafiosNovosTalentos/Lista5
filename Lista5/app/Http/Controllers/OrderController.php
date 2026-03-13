<?php

namespace App\Http\Controllers;

use App\Domain\Orders\Dto\CreateOrderDTO;
use App\Domain\Orders\Dto\UpdateOrderDTO;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Services\Orders\CreateOrderUseCase;
use App\Services\Orders\DeleteOrderUseCase;
use App\Services\Orders\GetOrderUseCase;
use App\Services\Orders\ListOrdersUseCase;
use App\Services\Orders\UpdateOrderUseCase;

class OrderController extends Controller
{
    public function index(ListOrdersUseCase $use_case)
    {
        $orders = $use_case->execute();

        $data = [];
        foreach ($orders as $order) {
            $data[] = $order->toArray();
        }

        return response()->json($data, 200);
    }

    public function store(StoreOrderRequest $request, CreateOrderUseCase $useCase)
    {

        $dto = new CreateOrderDTO(
            '019ce6e1-89aa-737d-b3fe-dea3e41d487e',
            $request->validated('product_name'),
            $request->validated('amount'),
        );

        $order = $useCase->execute($dto);

        return response()->json($order->toArray(), 201);
    }

    public function show(GetOrderUseCase $use_case, string $id)
    {
        $order = $use_case->execute($id);

        return response()->json($order->toArray(), 200);
    }

    public function update(UpdateOrderRequest $request, UpdateOrderUseCase $use_case, string $id)
    {
        $dto = new UpdateOrderDTO(
            $id,
            $request->validated('product_name'),
            $request->validated('amount')
        );

        $order = $use_case->execute($dto);

        return response()->json($order->toArray(), 200);
    }

    public function delete(DeleteOrderUseCase $use_case, string $id)
    {
        $use_case->execute($id);

        return response()->json(null, 204);
    }
}
