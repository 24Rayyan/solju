@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-primary">Invoice Order #{{ $order->invoice_number }}</h1>
        <div class="card shadow-sm border-0 p-4">
            <h4 class="mb-3">Detail Order</h4>
            <p><strong>Nama Customer:</strong> {{ $order->customer_name }}</p>
            <p><strong>Nama Produk:</strong> {{ $order->product_name }}</p>
            <p><strong>Jumlah Produk:</strong> {{ $order->quantity }}</p>
            <p><strong>Total Harga:</strong> Rp{{ number_format($order->quantity * $order->price, 0, ',', '.') }}</p>
        </div>
        <div class="mt-4">
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('orders.downloadInvoice', $order) }}" class="btn btn-info">Download Invoice</a>
        </div>
    </div>
@endsection
