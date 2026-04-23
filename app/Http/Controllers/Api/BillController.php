<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Bill;
use App\Http\Requests\StoreBillRequest;
use App\Http\Resources\BillResource;

class BillController extends Controller
{
    // 📌 index
    public function index()
    {
        $bills = Bill::with(['patient', 'appointment'])
            ->latest()
            ->paginate(10);

        return BillResource::collection($bills);
    }

    // 📌 store
    public function store(StoreBillRequest $request)
    {
        $bill = Bill::create([
            ...$request->validated(),
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Bill created successfully',
            'data' => new BillResource(
                $bill->load(['patient','appointment'])
            )
        ]);
    }

    // 📌 show (Route Model Binding)
    public function show(Bill $bill)
    {
        return new BillResource(
            $bill->load(['patient','appointment'])
        );
    }

    // 📌 mark as paid
    public function pay(Bill $bill)
    {
        $bill->update([
            'status' => 'paid',
            'paid_at' => now()
        ]);

        return response()->json([
            'message' => 'Bill paid successfully',
            'data' => new BillResource($bill)
        ]);
    }

    // 📌 delete (soft delete)
    public function destroy(Bill $bill)
    {
        $bill->delete();

        return response()->json([
            'message' => 'Bill deleted successfully'
        ]);
    }

    // 📌 restore
    public function restore($id)
    {
        $bill = Bill::withTrashed()->findOrFail($id);
        $bill->restore();

        return response()->json([
            'message' => 'Bill restored successfully'
        ]);
    }
}