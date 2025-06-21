<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Payment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\VirtualCard;
use App\Models\VirtualCardTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    // عرض كل عمليات الدفع
    public function index()
    {
        $payments = Payment::latest()->paginate(10);
        return view('payments.index', compact('payments'));
    }

    // عرض نموذج إنشاء دفعة
    public function create()
    {
        return view('payments.create');
    }

    // تخزين دفعة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string|max:255',
        ]);

        Payment::create($request->all());

        return redirect()->route('payments.index')
                         ->with('success', '✅ تم تسجيل الدفع بنجاح.');
    }

    // عرض صفحة الدفعات (اختياري)
    public function show()
    {
        return view('payments.index');
    }

    // عرض نموذج تعديل دفعة
    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        return view('payments.edit', compact('payment'));
    }

    // تحديث بيانات دفعة
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'payment_method' => 'required|string|max:255',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update($request->all());

        return redirect()->route('payments.index')
                         ->with('success', '✅ تم تعديل عملية الدفع بنجاح.');
    }

    // حذف دفعة
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('payments.index')
                         ->with('success', '🗑️ تم حذف الدفع بنجاح.');
    }

    // التحقق من الدفع وإنشاء الطلب باستخدام البطاقة الافتراضية
 public function verify(Request $request)
{
    $request->validate([
        'shipping_method' => 'required|string',
        'city' => 'required|string',
        'address' => 'required|string',
        'payment_method' => 'required|string',
        'card_number' => 'nullable|string|max:16',
    ]);

    $cart = session('cart', []);
    if (empty($cart)) {
        return redirect()->back()->with('error', 'سلة التسوق فارغة.');
    }

    // حساب إجمالي سعر المنتجات في السلة
    $total = collect($cart)->sum(function ($item) {
        return is_array($item) && isset($item['price'], $item['quantity']) ? $item['price'] * $item['quantity'] : 0;
    });

    // حساب رسوم الشحن الإضافية
    $shippingFee = 0;
    if ($request->shipping_method === 'express') {
        $shippingFee = 20; // قيمة الرسوم الإضافية للشحن السريع
    }

    // المجموع الكلي بعد إضافة رسوم الشحن
    $totalWithShipping = $total + $shippingFee;

    // حفظ بيانات الشحن في الجلسة
    session([
        'shipping' => [
            'shipping_method' => $request->shipping_method,
            'city' => $request->city,
            'address' => $request->address,
            'shipping_fee' => $shippingFee, // حفظ رسوم الشحن
        ],
    ]);

    DB::beginTransaction();

    try {
        // الدفع عبر البطاقة الافتراضية
        if ($request->payment_method === 'virtual_card') {
            $card = VirtualCard::where('card_number', $request->card_number)->lockForUpdate()->first();

            if (!$card) {
                return redirect()->back()->with('error', 'رقم البطاقة غير صالح.');
            }

            if ($card->balance < $totalWithShipping) {
                return redirect()->back()->with('error', 'الرصيد غير كافٍ لإتمام الطلب.');
            }

            $card->balance -= $totalWithShipping;
            $card->save();

            VirtualCardTransaction::create([
                'virtual_card_id' => $card->id,
                'type' => 'debit',
                'amount' => -$totalWithShipping,
                'balance_after' => $card->balance,
                'description' => 'خصم عند إنشاء طلب رقم ' . auth()->id(),
                'user_id' => auth()->id(),
            ]);
        }

        $shipping = session('shipping');

        // إنشاء الطلب مع إجمالي شامل رسوم الشحن
$order = Order::create([
    'user_id' => auth()->id(),
    'shipping_method' => $shipping['shipping_method'],
    'payment_method' => $request->payment_method,
    'city' => $shipping['city'],
    'address' => $shipping['address'],
    'shipping_fee' => $shipping['shipping_fee'],      
    'total' => $totalWithShipping,
    'status' => 'processing',
]);


        $order->is_paid = true;
        $order->save();

        // إنشاء عناصر الطلب (Order Items)
        foreach ($cart as $key => $item) {
            if (!is_array($item) || !isset($item['quantity'], $item['price'], $item['product_id'])) {
                continue;
            }

            $productId = intval(explode('_', $key)[0]);

            $order->items()->create([
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // تسجيل الدفع بالمجموع الكامل (منتجات + شحن)
        Payment::create([
            'order_id' => $order->id,
            'amount' => $totalWithShipping,
            'payment_method' => $request->payment_method,
        ]);

        DB::commit();

        // تفريغ السلة من قاعدة البيانات + الجلسة
        Cart::where('user_id', auth()->id())->delete();
        session()->forget(['cart', 'cart_count', 'shipping']);

        return redirect()->route('orders.index')->with('success', '✅ تم إنشاء الطلب والدفع بنجاح!');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Payment verification error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'حدث خطأ أثناء معالجة الطلب. الرجاء المحاولة لاحقًا.');
    }
}





}
