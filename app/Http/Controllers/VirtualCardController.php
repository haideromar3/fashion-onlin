<?php

namespace App\Http\Controllers;

use App\Models\VirtualCard;
use Illuminate\Http\Request;
use App\Models\VirtualCardTransaction;


class VirtualCardController extends Controller
{
    // عرض كل البطاقات الخاصة بالمستخدم (إن كانت هناك بطاقات مخصصة للمستخدمين)
    public function index()
    {
        // إذا أردت عرض كل البطاقات بغض النظر عن المستخدم:
        // $cards = VirtualCard::all();

        // أو عرض البطاقات المرتبطة بالمستخدم فقط
        $cards = VirtualCard::where('user_id', auth()->id())->get();

        return view('virtual_cards.index', compact('cards'));
    }

    public function transactions($card_id)
{
    $card = VirtualCard::findOrFail($card_id);

    // تأكد أن المستخدم له صلاحية عرض البطاقة
    if (auth()->id() !== $card->user_id) {
        abort(403, 'ليس لديك صلاحية الوصول إلى هذه البطاقة.');
    }

    $transactions = $card->transactions()->latest()->get();

    return view('virtual_cards.transactions', compact('card', 'transactions'));
}
}
