<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class OrdersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Order::with('products')->get()->flatMap(function ($order) {
            return $order->products->map(function ($product) use ($order) {
                return [
                    'Order ID' => $order->id,
                    'Order Date' => $order->created_at->format('Y-m-d'),
                    'Product Name' => $product->name,
                    'Quantity' => $product->pivot->quantity,
                    'Price per Unit' => $product->pivot->price,
                    'Total Price' => $product->pivot->price * $product->pivot->quantity,
                ];
            });
        });
    }

    public function headings(): array
    {
        return ['Order ID', 'Order Date', 'Product Name', 'Quantity', 'Price per Unit', 'Total Price'];
    }
}
