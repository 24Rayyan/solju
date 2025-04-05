@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Chart Penjualan</h1>

        <!-- Form Filter -->
        <form method="GET" action="{{ route('orders.salesChart') }}" class="mb-6">
            <div class="flex gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="self-end">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Canvas untuk Chart -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
            <canvas id="salesChart" width="400" height="200"></canvas>
        </div>

        <!-- Grid untuk Tabel -->
        <div class="flex gap-6">
            <!-- Tabel Total Penjualan -->
            <div class="w-1/2 bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Total Penjualan</h2>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-sm font-semibold text-gray-600">Status</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-sm font-semibold text-gray-600">Total Harga (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">Locked True</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ number_format($totalLockedTrue, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">Locked False</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ number_format($totalLockedFalse, 0, ',', '.') }}</td>
                        </tr>
                        <!-- Baris Grand Total -->
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200 font-semibold">Grand Total</td>
                            <td class="py-2 px-4 border-b border-gray-200 font-semibold">{{ number_format($grandTotal, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tabel Produk Terlaris -->
            <div class="w-1/2 bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Produk Terlaris</h2>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-sm font-semibold text-gray-600">Nama Produk</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-sm font-semibold text-gray-600">Qty Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topProducts as $product)
                            <tr>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $product->name }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $product->total_qty }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sertakan Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Ambil data dari controller
        const labels = @json($labels);
        const dataLockedTrue = @json($dataLockedTrue);
        const dataLockedFalse = @json($dataLockedFalse);

        // Buat chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line', // Jenis chart (line, bar, pie, dll)
            data: {
                labels: labels, // Label sumbu X (tanggal)
                datasets: [
                    {
                        label: 'Penjualan Locked True (Rp)', // Label dataset
                        data: dataLockedTrue, // Data penjualan locked true
                        backgroundColor: 'rgba(59, 130, 246, 0.2)', // Warna background
                        borderColor: 'rgba(59, 130, 246, 1)', // Warna garis
                        borderWidth: 2 // Lebar garis
                    },
                    {
                        label: 'Penjualan Locked False (Rp)', // Label dataset
                        data: dataLockedFalse, // Data penjualan locked false
                        backgroundColor: 'rgba(255, 99, 132, 0.2)', // Warna background (merah)
                        borderColor: 'rgba(255, 99, 132, 1)', // Warna garis (merah)
                        borderWidth: 2 // Lebar garis
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true, // Mulai sumbu Y dari 0
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString(); // Format nilai sebagai mata uang
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.raw.toLocaleString(); // Format tooltip
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection