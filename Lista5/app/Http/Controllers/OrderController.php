<?php

namespace App\Http\Controllers;

use App\Domain\Orders\Dto\CreateOrderDTO;
use App\Domain\Orders\Dto\UpdateOrderDTO;
use App\Http\Requests\Order\ListOrdersRequest;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Services\Orders\CreateOrderUseCase;
use App\Services\Orders\DeleteOrderUseCase;
use App\Services\Orders\GetOrderUseCase;
use App\Services\Orders\ListOrdersUseCase;
use App\Services\Orders\UpdateOrderUseCase;

class OrderController extends Controller
{
    public function index(ListOrdersRequest $request, ListOrdersUseCase $use_case)
    {
        $page = $request->validated('page', 1);
        $limit = $request->validated('limit', 3);

        $orders = $use_case->execute($page, $limit);

        $data = [];
        foreach ($orders->items as $order) {
            $data[] = $order->toArray();
        }

        return response()->json(
            [
                'data' => $data,
                'metadata' => [
                    'total' => $orders->total,
                    'current_page' => $orders->current_page,
                    'per_page' => $orders->per_page,
                    'last_page' => $orders->lastPage(),
                ],
            ],
            200
        );
    }

    public function store(StoreOrderRequest $request, CreateOrderUseCase $use_case)
    {
        $dto = new CreateOrderDTO(
            $request->user()->id,
            strip_tags($request->validated('product_name')),
            $request->validated('amount'),
        );

        $order = $use_case->execute($dto);

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
            strip_tags($request->validated('product_name')),
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
