<?php

namespace App\Http\Controllers;

use App\Models\VirtualCardTransaction;
use Illuminate\Http\Request;

class VirtualCardTransactionController extends Controller
{
    public function index()
    {
        $transactions = VirtualCardTransaction::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('user.transactions.virtual_card', compact('transactions'));
    }
}
