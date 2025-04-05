<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px;
        }
        .header {
            display: flex;
            align-items: center; /* Membuat logo dan teks sejajar vertikal */
            margin-bottom: 20px;
        }
        .header img {
            height: 100px;
            margin-right: 20px; /* Jarak antara logo dan teks */
        }
        .header .invoice-text {
            flex-grow: 1; /* Membuat teks mengambil sisa ruang yang tersedia */
            text-align: center; /* Memposisikan teks di tengah */
        }
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
        .header p {
            font-size: 14px;
            margin: 0; /* Menghilangkan margin agar teks lebih rapat */
        }
        .company-info, .customer-info {
            margin-bottom: 20px;
        }
        .company-info {
            float: left;
            width: 50%;
        }
        .customer-info {
            float: right;
            width: 50%;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            clear: both;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .total {
            text-align: right;
            margin-top: 20px;
        }
        .total p {
            margin: 5px 0;
            font-size: 12px;
        }
        .total .grand-total {
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header Invoice dengan Logo di Kiri dan Teks di Tengah -->
    <div class="header">
        <img src="{{ public_path('resources/IMG/LOGO.png') }}" alt="Logo Solju">
        <div class="invoice-text" style="margin-bottom: 5%">
            <h1>INVOICE</h1>
            <p>{{ $order->invoice_number }}</p>
            <hr style="margin-top: 30px">
        </div>
    </div>

    <!-- Informasi Perusahaan -->
    <div class="company-info">
        <p><strong>Solju</strong></p>
        <p>Alamat: Jl. Contoh No. 123, Jakarta, Indonesia</p>
        <p>No. Fax: (021) 123-4567</p>
    </div>

    <!-- Informasi Pembeli -->
    <div class="customer-info">
        <p><strong>Tanggal Invoice:</strong> {{ date('d F Y', strtotime($order->created_at)) }}</p>
        <p><strong>Nama Pembeli:</strong> {{ $order->customer_name }}</p>
        <p><strong>Alamat Pembeli:</strong> {{ $order->customer_address }}</p>
    </div>

    <!-- Tabel Produk -->
    <table style="margin-top: 3%">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>Rp {{ number_format($product->price, 2, ',', '.') }}</td>
                    <td>Rp {{ number_format($product->quantity * $product->price, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total -->
    <div class="total">
        <p class="grand-total">Grand Total: Rp {{ number_format($order->products->sum(fn($p) => $p->quantity * $product->price), 2, ',', '.') }}</p>
    </div>
    <p style="margin-top: 15%"><i>*Dokumen ini valid dan telah diproses oleh sistem elektronik</i></p>

</body>
</html>