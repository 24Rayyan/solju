<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;



class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $orders = Order::when($search, function ($query, $search) {
            return $query->where('customer_name', 'like', "%{$search}%");
        })
        ->orderBy('locked', 'asc') // Urutkan berdasarkan kolom locked (false di atas, true di bawah)
        ->paginate(10);
    
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
        if ($order->is_locked) {
            return redirect()->route('orders.index')->with('error', 'Order ini sudah dikunci dan tidak bisa diubah!');
        }
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
        if ($order->is_locked) {
            return redirect()->route('orders.index')->with('error', 'Order ini sudah dikunci dan tidak bisa dihapus!');
        }
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order berhasil dihapus!');
    }

    public function downloadInvoice(Order $order)
    {
        $pdf = Pdf::loadView('pages.invoices.download', compact('order'));
        return $pdf->download('Invoice'.$order->id.'.pdf');
    }
    public function lock(Order $order)
    {
        // Debugging: Cek apakah method dipanggil
        logger('Lock method called for order: ' . $order->id);
    
        // Update kolom locked
        $order->update(['locked' => true]);
    
        // Redirect dengan pesan sukses
        return redirect()->route('orders.index')->with('success', 'Order berhasil dikunci!');
    }

    public function salesChart(Request $request)
    {
        // Ambil rentang tanggal dari request
        $startDate = $request->input('start_date', '2025-02-28');
        $endDate = $request->input('end_date', '2025-05-30');
    
        // Ambil data penjualan untuk locked = true
        $lockedTrueData = Order::where('locked', true)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('products')
            ->get()
            ->map(function ($order) {
                return [
                    'date' => $order->created_at->format('Y-m-d'),
                    'total_sales' => $order->total,
                    'locked' => true, // Tandai sebagai locked true
                ];
            });
    
        // Ambil data penjualan untuk locked = false
        $lockedFalseData = Order::where('locked', false)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('products')
            ->get()
            ->map(function ($order) {
                return [
                    'date' => $order->created_at->format('Y-m-d'),
                    'total_sales' => $order->total,
                    'locked' => false, // Tandai sebagai locked false
                ];
            });
    
        // Gabungkan data
        $salesData = $lockedTrueData->concat($lockedFalseData)
            ->groupBy('date') // Kelompokkan berdasarkan tanggal
            ->map(function ($group) {
                return [
                    'date' => $group->first()['date'],
                    'total_sales_locked_true' => $group->where('locked', true)->sum('total_sales'),
                    'total_sales_locked_false' => $group->where('locked', false)->sum('total_sales'),
                ];
            })
            ->values(); // Ambil nilai array
    
        // Format data untuk chart
        $labels = $salesData->pluck('date');
        $dataLockedTrue = $salesData->pluck('total_sales_locked_true');
        $dataLockedFalse = $salesData->pluck('total_sales_locked_false');
    
        // Hitung total harga untuk locked = true dan locked = false
        $totalLockedTrue = $lockedTrueData->sum('total_sales');
        $totalLockedFalse = $lockedFalseData->sum('total_sales');
    
        // Hitung Grand Total
        $grandTotal = $totalLockedTrue + $totalLockedFalse;
    
        // Ambil data produk terlaris berdasarkan nama produk yang sama dan rentang tanggal
        $topProducts = DB::table('order_products') // Gunakan tabel order_products
            ->join('orders', 'order_products.order_id', '=', 'orders.id') // Join dengan tabel orders
            ->whereBetween('orders.created_at', [$startDate, $endDate]) // Filter berdasarkan tanggal
            ->select('order_products.name', DB::raw('SUM(order_products.quantity) as total_qty')) // Ambil nama produk dan total qty
            ->groupBy('order_products.name') // Kelompokkan berdasarkan nama produk
            ->orderByDesc('total_qty') // Urutkan dari yang terlaris
            ->limit(10) // Ambil 10 produk terlaris
            ->get();
    
        return view('pages.orders.sales-chart', compact('labels', 'dataLockedTrue', 'dataLockedFalse', 'startDate', 'endDate', 'totalLockedTrue', 'totalLockedFalse', 'grandTotal', 'topProducts'));
    }

    
}
