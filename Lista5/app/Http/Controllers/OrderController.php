<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Services\Orders\CreateOrderUseCase;
use App\Domain\Orders\Dto\CreateOrderDTO;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request, CreateOrderUseCase $useCase)
    {
        $dto = new CreateOrderDTO('019ce441-b6d8-72d8-82cd-963bf42debea', $request->input('product_name'), $request->input('amount'),);

        $order = $useCase->execute($dto);

        return response()->json($order->toArray(), 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
