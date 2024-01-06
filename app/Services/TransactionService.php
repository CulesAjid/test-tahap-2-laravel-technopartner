<?php

namespace App\Services;

use App\Enums\TypeTransaction;
use App\Models\Transaction;
use App\Traits\FormatNumber;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    use FormatNumber;

    private string $type;
    private int $qty;
    private int $price;
    private $date;


    public function storeTransaction()
    {
        $transaction = [];
        $lastTransaction = Transaction::query()->orderBy('id', 'DESC')->first()->toArray();

        $transaction['type'] = $this->type;
        $transaction['qty'] = $this->qty;
        $transaction['price'] = $this->price;
        $transaction['date'] = $this->date;
        $transaction['cost'] = $this->cost($lastTransaction, $transaction['price'], $transaction['type']);
        $transaction['total_cost'] = $this->totalCost($transaction['qty'], $transaction['cost']);
        $transaction['qty_balance'] = $this->qtyBalance($lastTransaction, $transaction['qty']);
        $transaction['value_balance'] = $this->valueBalance($lastTransaction, $transaction['total_cost']);
        $transaction['hpp'] = $this->hpp($transaction['value_balance'], $transaction['qty_balance']);

        $trx = Transaction::query()->create($transaction);

        return $trx->fresh();
    }

    public function getSampleResponse($transactions)
    {
        $response = collect();
        $lastTransaction = null;
        foreach ($transactions as $trx) {
            $transaction = [];
            $transaction['type'] = $trx->type;
            $transaction['date'] = Carbon::createFromFormat('Y-m-d', $trx->date)->format('Y/m/d');
            $transaction['qty'] = $trx->qty;
            $transaction['price'] = $trx->price;
            $transaction['cost'] = $this->formatDecimal($this->cost($lastTransaction, $transaction['price'], $transaction['type']), 4);
            $transaction['total_cost'] = $this->formatDecimal($this->totalCost($transaction['qty'], $transaction['cost']), 4);
            $transaction['qty_balance'] = $this->qtyBalance($lastTransaction, $transaction['qty']);
            $transaction['value_balance'] = $this->formatDecimal($this->valueBalance($lastTransaction, $transaction['total_cost']), 4);
            $transaction['hpp'] = $this->formatDecimal($this->hpp($transaction['value_balance'], $transaction['qty_balance']), 4);
            $transaction['created_at'] = $trx->created_at->format('Y/m/d');
            $lastTransaction = $transaction;
            $response->push($transaction);
        }

        return $response;
    }

    private function cost($lastTransaction, int $price, string $type)
    {
        $cost = 0;
        if ($type == TypeTransaction::PEMBELIAN->value) {
            $cost = $price;
        } else {
            $cost = is_null($lastTransaction) ? 0 : $lastTransaction['hpp'];
        }

        return (float)$cost;
    }

    private function totalCost(int $qty, float $cost)
    {
        return $qty * $cost;
    }

    private function qtyBalance($lastTransaction, $qty)
    {
        $qtyBalanceAfter = is_null($lastTransaction) ? 0 : $lastTransaction['qty_balance'];
        return $qtyBalanceAfter + $qty;
    }

    private function valueBalance($lastTransaction, float $totalCost)
    {
        $valueBalanceAfter = is_null($lastTransaction) ? 0 : $lastTransaction['value_balance'];
        return $valueBalanceAfter + $totalCost;
    }

    private function hpp(float $valueBalance, float $qtyBalance)
    {
        return $valueBalance / $qtyBalance;
    }


    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param int $qty
     */
    public function setQty(int $qty): void
    {
        $this->qty = $qty;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }


}
