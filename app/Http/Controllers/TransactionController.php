<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\QueryFilters\EndDateFilter;
use App\QueryFilters\SortFilter;
use App\QueryFilters\StartDateFilter;
use App\QueryFilters\TransactionTypeFilter;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Pipeline;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $transactions = Pipeline::send(Transaction::query())
            ->through([
                TransactionTypeFilter::class,
                SortFilter::class,
                StartDateFilter::class,
                EndDateFilter::class,
            ])
            ->thenReturn();

        return response()->json([
            'transactions' => $transactions->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request): JsonResponse
    {
        $code = Response::HTTP_CREATED;
        DB::beginTransaction();

        try {
            $data = $this->getData($request);

            $request->user()->transactions()->create([
                'title' => $data['title'],
                'details' => json_encode($data['details']),
                'amount' => $data['amount'],
                'transaction_type' => $data['transaction_type']
            ]);

            $message = "Transaction record added.";
        } catch (Exception $e) {
            DB::rollBack();

            $message = $e->getMessage();
            $code = Response::HTTP_BAD_REQUEST;
        }

        DB::commit();
        return response()->json([
            'message' => $message
        ], $code);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction): JsonResponse
    {
        return response()->json([
            'transaction' => $transaction
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionRequest $request, Transaction $transaction): JsonResponse
    {
        $code = Response::HTTP_ACCEPTED;
        DB::beginTransaction();

        try {
            $data = $this->getData($request);

            $transaction->update([
                'title' => $data['title'],
                'details' => json_encode($data['details']),
                'amount' => $data['amount'],
            ]);

            $message = "Transaction record updated.";
        } catch (Exception $e) {
            DB::rollBack();

            $message = $e->getMessage();
            $code = Response::HTTP_BAD_REQUEST;
        }

        DB::commit();
        return response()->json([
            'message' => $message
        ], $code);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction): JsonResponse
    {
        $transaction->delete();

        return response()->json([
            'message' => 'Transaction deleted'
        ], Response::HTTP_ACCEPTED);
    }

    private function getData(TransactionRequest $request)
    {
        $data = $request->validated();
        $details = [];

        if (in_array($data['transaction_type'], ['LOAN_TAKEN', 'LOAN_GIVEN'])) {
            $details['paid'] = $data['paid'];
        } else {
            $details['details'] = $data['details'];
        }
        $data['details'] = $details;
        return $data;
    }
}
