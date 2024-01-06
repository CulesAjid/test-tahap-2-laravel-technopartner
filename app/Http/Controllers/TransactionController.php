<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Services\TransactionService;
use App\Traits\ApiResponse;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trx = Transaction::query()->orderBy('id', 'ASC')
            ->get()->toArray();

        return $this->success($trx);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $trx = Transaction::query()->create($request->all());

            DB::commit();

            return $this->success($trx->toArray(), 201);

        } catch (QueryException $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            throw new QueryException("Error transaction");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            $trx = Transaction::query()->findOrFail($id);
            $trx->update($request->all());

            DB::commit();

            return $this->success($trx->toArray(), 200);

        } catch (QueryException $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            throw new QueryException("Error transaction");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return $this->success();
    }

    public function insert(Request $request)
    {
        try {
            DB::beginTransaction();
            Transaction::query()->truncate();
            foreach ($request->all() as $transaction) {
                Transaction::query()->create($transaction);
            }

            DB::commit();

            return $this->success(null,201);

        } catch (QueryException $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            throw new QueryException("Error transaction");
        }
    }

    public function responseSample() {
        $transactions = Transaction::query()->orderBy('id', 'ASC')->get();

        $transaction = new TransactionService();
        $responseSample = $transaction->getSampleResponse($transactions);

        return $this->success($responseSample->toArray());
    }

    public function addTransactionValidation(TransactionRequest $request) {
        try {
            DB::beginTransaction();
            $trx = Transaction::query()->create($request->all());

            DB::commit();

            return $this->success($trx->toArray(), 201);

        } catch (QueryException $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            throw new QueryException("Error transaction");
        }
    }

    public function updateTransactionValidation(TransactionRequest $request, string $id) {
        try {
            DB::beginTransaction();
            $trx = Transaction::query()->findOrFail($id);
            $trx->update($request->all());

            DB::commit();

            return $this->success($trx->toArray(), 200);

        } catch (QueryException $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            throw new QueryException("Error transaction");
        }
    }
}
