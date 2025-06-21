<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <title>تقرير المبيعات PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            direction: rtl;
            text-align: right;
            font-size: 14px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #4CAF50;
            color: white;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>تقرير المبيعات من {{ $from }} إلى {{ $to }}</h2>

    <p>إجمالي الطلبات: {{ $totalOrders }}</p>
    <p>إجمالي المبيعات: {{ number_format($totalRevenue, 2) }} $</p>

    <table>
        <thead>
            <tr>
                <th>اسم المنتج</th>
                <th>عدد المبيعات</th>
                <th>الإيرادات (بالدولار)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productStats as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->total_sales }}</td>
                    <td>{{ number_format($product->revenue, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
