<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        // Ambil buyer_id dari tabel buyers berdasarkan user yang login
        $buyer = \App\Models\Buyer::where('user_id', auth()->id())->first();

        if (!$buyer) {
            return view('user.transaction.index', [
                'transactions' => collect([])
            ]);
        }

        $transactions = Transaction::where('buyer_id', $buyer->id)
            ->with(['store', 'transactiondetails.product.images'])
            ->latest()
            ->paginate(10);

        return view('user.transaction.index', compact('transactions'));
    }

    public function show($id)
    {
        $transaction = Transaction::where('buyer_id', auth()->id())
            ->with(['store', 'details.product.images'])
            ->findOrFail($id);

        return view('user.transaction.show', compact('transaction'));
    }

    public function cancel($id)
    {
        $transaction = Transaction::where('buyer_id', auth()->id())
            ->where('payment_status', 'pending')
            ->findOrFail($id);

        $transaction->update(['payment_status' => 'cancelled']);

        // Restore stock
        foreach ($transaction->details as $detail) {
            $detail->product->increment('stock', $detail->qty);
        }

        return back()->with('success', 'Order cancelled successfully');
    }
}