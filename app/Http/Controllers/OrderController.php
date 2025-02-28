<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\OrderProduct;


class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $orders = Order::when($search, function ($query, $search) {
            return $query->where('customer_name', 'like', "%{$search}%");
        })->paginate(10);
    
        return view('pages.orders.index', compact('orders', 'search'));
    }
    

    public function create()
    {
        return view('pages.orders.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'customer_name' => 'required|string|max:255',
        'customer_address' => 'required|string',
        'products' => 'required|array|min:1',
        'products.*.name' => 'required|string',
        'products.*.quantity' => 'required|integer|min:1',
        'products.*.price' => 'required|numeric|min:0',
    ]);

    $order = Order::create([
        'customer_name' => $request->customer_name,
        'customer_address' => $request->customer_address,
        'invoice_number' => 'INV/SOLJU/' . time(), // Tambahkan invoice number unik
    ]);

    foreach ($request->products as $product) {
        OrderProduct::create([
            'order_id' => $order->id,
            'name' => $product['name'],
            'quantity' => $product['quantity'],
            'price' => $product['price'],
        ]);
    }

    return redirect()->route('orders.index')->with('success', 'Order berhasil dibuat!');
}

    public function show(Order $order)
    {
        $order->load('products'); // Memastikan relasi produk dimuat
        return view('pages.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('pages.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        // Validasi input
        $request->validate([
            'customer_name' => 'required|string',
            'customer_address' => 'required|string',
            'products' => 'required|array',
            'products.*.name' => 'required|string',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        // Update data order utama
        $order->update([
            'customer_name' => $request->customer_name,
            'customer_address' => $request->customer_address,
        ]);

        // Hapus produk lama yang terkait dengan order ini
        $order->orderProducts()->delete();

        // Simpan produk baru
        foreach ($request->products as $productData) {
            $order->orderProducts()->create([
                'name' => $productData['name'],
                'quantity' => $productData['quantity'],
                'price' => $productData['price'],
            ]);
        }

        // Redirect ke halaman daftar order dengan pesan sukses
        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }

    public function downloadInvoice(Order $order)
    {
        $pdf = Pdf::loadView('pages.invoices.download', compact('order'));
        return $pdf->download('Invoice'.$order->id.'.pdf');
    }
}
