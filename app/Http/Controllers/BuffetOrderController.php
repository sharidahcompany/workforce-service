<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuffetOrderRequest;
use App\Http\Requests\DeleteRequest;
use App\Http\Resources\BuffetOrderResource;
use App\Models\Tenant\BuffetItem;
use App\Models\Tenant\BuffetOrder;
use App\Services\QueryBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BuffetOrderController extends Controller
{

    public function __construct(private QueryBuilderService $queryBuilder) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buffetOrders = BuffetOrder::query()->with('user', 'items', 'items.item');

        $result = $this->queryBuilder->applyQuery($request, $buffetOrders);

        return BuffetOrderResource::collection($result);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(BuffetOrderRequest $request)
    {
        $validated = $request->validated();

        $order = DB::transaction(function () use ($validated) {
            $order = BuffetOrder::create([
                'user_id' => $validated['user_id'],
                'notes' => $validated['notes'] ?? null,
                'status' => $validated['status'] ?? 'pending',
            ]);

            $grandTotal = 0;

            foreach ($validated['items'] as $row) {
                $item = BuffetItem::findOrFail($row['buffet_item_id']);

                $price = (float) $item->price;
                $quantity = (int) $row['quantity'];
                $lineTotal = $price * $quantity;

                $order->items()->create([
                    'buffet_item_id' => $item->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $lineTotal,
                    'notes' => $row['notes'] ?? null,
                ]);

                $grandTotal += $lineTotal;
            }

            $order->update([
                'total' => $grandTotal,
            ]);

            return $order->load([
                'user',
                'items.item',
            ]);
        });

        return response()->json([
            'data' => new BuffetOrderResource($order),
            'message' => trans('crud.created'),
        ], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $buffetOrder = BuffetOrder::with('user', 'items')->findOrFail($id);

        return response()->json(['data' => new BuffetOrderResource($buffetOrder)], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(BuffetOrderRequest $request, string $id)
    {
        $validated = $request->validated();

        $buffetOrder = BuffetOrder::findOrFail($id);

        $order = DB::transaction(function () use ($validated, $buffetOrder) {
            $buffetOrder->update([
                'user_id' => $validated['user_id'],
                'notes' => $validated['notes'] ?? null,
                'status' => $validated['status'] ?? 'pending',
            ]);

            $buffetOrder->items()->delete();

            $grandTotal = 0;

            foreach ($validated['items'] as $row) {
                $item = BuffetItem::findOrFail($row['buffet_item_id']);

                $price = (float) $item->price;
                $quantity = (int) $row['quantity'];
                $lineTotal = $price * $quantity;

                $buffetOrder->items()->create([
                    'buffet_item_id' => $item->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $lineTotal,
                    'notes' => $row['notes'] ?? null,
                ]);

                $grandTotal += $lineTotal;
            }

            $buffetOrder->update([
                'total' => $grandTotal,
            ]);

            return $buffetOrder->load([
                'user',
                'items.item',
            ]);
        });

        return response()->json([
            'data' => new BuffetOrderResource($order),
            'message' => trans('crud.updated'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteRequest $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            $buffetOrder = BuffetOrder::findOrFail($id);
            $buffetOrder->delete();
        }

        return response()->json(['message' => trans('crud.deleted')], 200);
    }
}
