@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Daftar Order</h1>
        <a href="{{ route('orders.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:from-blue-600 hover:to-blue-700 shadow-lg">
            Tambah Order
        </a>
        
        <!-- Search Bar -->
        <form method="GET" action="{{ route('orders.index') }}" class="mt-4" >
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Pelanggan" class="px-4 py-2 border rounded-lg w-full md:w-1/3 focus:ring focus:ring-blue-300">
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg ml-2 hover:bg-blue-600">Cari</button>
        </form>
        
        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 shadow-lg rounded-lg overflow-hidden">
                <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                    <tr>
                        <th class="py-3 px-6 text-left">Invoice</th>
                        <th class="py-3 px-6 text-left">Nama</th>
                        <th class="py-3 px-6 text-left">Alamat</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">{{ $order->invoice_number }}</td>
                            <td class="py-4 px-6">{{ $order->customer_name }}</td>
                            <td class="py-4 px-6">{{ $order->customer_address }}</td>
                            <td class="py-4 px-6 text-center space-x-2">
                                <!-- Tombol Detail -->
                                <a href="{{ route('orders.show', $order) }}" class="bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded-lg transition-colors">
                                    <i class="fas fa-eye"></i> <!-- Ikon Detail -->
                                </a>
                            
                                <!-- Tombol Edit -->
                                <a href="{{ route('orders.edit', $order) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded-lg transition-colors">
                                    <i class="fas fa-edit"></i> <!-- Ikon Edit -->
                                </a>
                            
                                <!-- Tombol Hapus -->
                                <form action="{{ route('orders.destroy', $order) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-lg transition-colors">
                                        <i class="fas fa-trash"></i> <!-- Ikon Hapus -->
                                    </button>
                                </form>
                            
                                <!-- Tombol Download -->
                                <a href="{{ route('orders.downloadInvoice', $order) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded-lg transition-colors">
                                    <i class="fas fa-download"></i> <!-- Ikon Download -->
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $orders->appends(['search' => request('search')])->links() }}
        </div>
    </div>
@endsection
