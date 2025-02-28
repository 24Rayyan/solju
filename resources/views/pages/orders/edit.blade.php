@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4">
                <h2 class="text-2xl font-bold">Edit Order - Invoice #{{ $order->invoice_number }}</h2>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <form action="{{ route('orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                
                    <!-- Nama Customer -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Nama Customer:</label>
                        <input type="text" name="customer_name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ $order->customer_name }}" required>
                    </div>
                
                    <!-- Alamat Customer -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Alamat Customer:</label>
                        <textarea name="customer_address" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" rows="3" required>{{ $order->customer_address }}</textarea>
                    </div>
                
                    <!-- Daftar Produk -->
                    <h4 class="text-xl font-semibold text-gray-800 mb-4">Produk yang Dibeli</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                            <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                                <tr>
                                    <th class="px-4 py-3 text-left">Nama Produk</th>
                                    <th class="px-4 py-3 text-left">Jumlah</th>
                                    <th class="px-4 py-3 text-left">Harga Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderProducts as $index => $product)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3">
                                            <input type="hidden" name="products[{{ $index }}][id]" value="{{ $product->id }}">
                                            <input type="text" name="products[{{ $index }}][name]" class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $product->name }}" required>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" name="products[{{ $index }}][quantity]" class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $product->quantity }}" required>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" name="products[{{ $index }}][price]" class="w-full px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ $product->price }}" step="0.01" required>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                
                    <!-- Tombol Submit -->
                    <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold py-3 rounded-lg hover:from-green-600 hover:to-green-700 shadow-lg mt-6">
                        Update Order
                    </button>
                </form>
            </div>

            <!-- Card Footer -->
            <div class="bg-gray-50 px-6 py-4">
                <a href="{{ route('orders.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">Kembali</a>
            </div>
        </div>
    </div>
@endsection