@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4">
                <h2 class="text-2xl font-bold">Detail Order - Invoice #{{ $order->invoice_number }}</h2>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <!-- Informasi Customer -->
                <div class="mb-6">
                    <p class="text-gray-700"><strong>Nama Customer:</strong> {{ $order->customer_name }}</p>
                    <p class="text-gray-700"><strong>Alamat Customer:</strong> {{ $order->customer_address }}</p>
                </div>

                <!-- Tabel Produk -->
                <h4 class="text-xl font-semibold text-gray-800 mb-4">Produk yang Dibeli</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                        <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                            <tr>
                                <th class="px-4 py-3 text-left">Nama Produk</th>
                                <th class="px-4 py-3 text-left">Jumlah</th>
                                <th class="px-4 py-3 text-left">Harga Satuan</th>
                                <th class="px-4 py-3 text-left">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->products as $product)
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-gray-700">{{ $product->name }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $product->quantity }}</td>
                                    <td class="px-4 py-3 text-gray-700">Rp {{ number_format($product->price, 2, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-gray-700">Rp {{ number_format($product->quantity * $product->price, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Total Keseluruhan -->
                <div class="mt-6 text-right">
                    <p class="text-lg font-semibold text-gray-800">
                        <strong>Total Keseluruhan:</strong> 
                        Rp {{ number_format($order->products->sum(fn($p) => $p->quantity * $p->price), 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- Card Footer -->
            <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-4">
                <a href="{{ route('orders.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">Kembali</a>
                <a href="{{ route('orders.downloadInvoice', $order) }}" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 shadow-lg">Download Invoice</a>
            </div>
        </div>
    </div>
@endsection