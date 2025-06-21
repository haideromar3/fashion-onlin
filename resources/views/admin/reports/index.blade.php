@extends('layouts.admin')

@section('title', 'تقارير المبيعات')

@section('content')
<div class="container mt-4">

    {{-- الفلاتر وأزرار التصدير --}}
    <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3 align-items-center mb-4">
        <div class="col-auto">
            <label for="from" class="form-label">من تاريخ:</label>
            <input type="date" id="from" name="from" class="form-control" value="{{ request('from', $from ?? '') }}">
        </div>
        <div class="col-auto">
            <label for="to" class="form-label">إلى تاريخ:</label>
            <input type="date" id="to" name="to" class="form-control" value="{{ request('to', $to ?? '') }}">
        </div>
        <div class="col-auto mt-4">
            <button type="submit" class="btn btn-primary">تصفية</button>
        </div>
        <div class="col-auto mt-4">
            <a href="{{ route('admin.reports.exportPDF', ['from' => request('from', $from ?? ''), 'to' => request('to', $to ?? '')]) }}" 
               class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> تصدير PDF
            </a>
            <a href="{{ route('admin.reports.exportCSV', ['from' => request('from', $from ?? ''), 'to' => request('to', $to ?? '')]) }}" 
               class="btn btn-success">
                <i class="fas fa-file-csv"></i> تصدير Excel
            </a>
        </div>
    </form>

    {{-- ملخص عام --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-bg-success shadow">
                <div class="card-body text-center">
                    <h5 class="card-title">إجمالي الطلبات</h5>
                    <p class="display-6">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-info shadow">
                <div class="card-body text-center">
                    <h5 class="card-title">إجمالي المبيعات</h5>
                    <p class="display-6">{{ number_format($totalSales, 2) }} $</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-warning shadow">
                <div class="card-body text-center">
                    <h5 class="card-title">عدد المنتجات المباعة</h5>
                    <p class="display-6">{{ $totalProductsSold }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- جدول تفصيلي حسب المنتج --}}
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            تقرير مفصل حسب المنتج
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0 text-center">
                <thead class="table-light">
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
                            <td>{{ $product->orders->sum('pivot.quantity') }}</td>
                            <td>{{ number_format($product->revenue ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- الرسوم البيانية --}}
    <div class="row mt-5">
        {{-- Bar Chart --}}
        <div class="col-md-12 mb-5">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">مخطط عمودي: المبيعات والإيرادات</div>
                <div class="card-body">
                    <canvas id="salesChart" height="120"></canvas>
                </div>
            </div>
        </div>

        {{-- Pie Chart --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">نسبة المبيعات حسب المنتج</div>
                <div class="card-body">
                    <canvas id="pieChart" height="120"></canvas>
                </div>
            </div>
        </div>

        {{-- Line Chart --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">الإيرادات اليومية</div>
                <div class="card-body">
                    <canvas id="lineChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const productNames = @json($productStats->pluck('name'));
    const productQuantities = @json($productStats->map(fn($p) => $p->orders->sum('pivot.quantity')));
    const productRevenues = @json($productStats->map(fn($p) => round($p->revenue, 2)));

    // بيانات الإيرادات اليومية (تمريرها من الـ controller)
    const dailyLabels = @json(array_keys($dailyRevenue ?? []));
    const dailyValues = @json(array_values($dailyRevenue ?? []));

    // Bar chart
    new Chart(document.getElementById('salesChart'), {
        type: 'bar',
        data: {
            labels: productNames,
            datasets: [
                {
                    label: 'عدد المبيعات',
                    data: productQuantities,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                },
                {
                    label: 'الإيرادات ($)',
                    data: productRevenues,
                    backgroundColor: 'rgba(255, 206, 86, 0.7)',
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'مبيعات المنتجات'
                }
            }
        }
    });

    // Pie chart
    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: productNames,
            datasets: [{
                label: 'نسبة المبيعات',
                data: productQuantities,
                backgroundColor: [
                    '#f87171', '#60a5fa', '#34d399', '#facc15', '#a78bfa', '#fb923c'
                ],
            }]
        }
    });

    // Line chart
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'الإيرادات اليومية ($)',
                data: dailyValues,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(99, 102, 241, 0.2)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'الإيرادات اليومية'
                }
            }
        }
    });
</script>
@endsection
