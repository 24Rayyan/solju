@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto bg-gradient-to-br from-blue-50 to-white p-8 rounded-xl shadow-2xl border border-blue-100">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Tambah Order</h1>

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            
            <!-- Form Input Pelanggan -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Data Pelanggan</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Nama Pelanggan:</label>
                        <input type="text" name="customer_name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Masukkan nama pelanggan" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Alamat Pelanggan:</label>
                        <textarea name="customer_address" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Masukkan alamat pelanggan" rows="3" required></textarea>
                    </div>
                </div>
            </div>

            <!-- Tabel List Produk -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">List Produk</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                        <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                            <tr>
                                <th class="px-4 py-3 text-left">Nama Produk</th>
                                <th class="px-4 py-3 text-left">Jumlah</th>
                                <th class="px-4 py-3 text-left">Harga</th>
                                <th class="px-4 py-3 text-left">Total</th>
                                <th class="px-4 py-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="product-list">
                            <!-- Baris produk akan ditambahkan di sini secara dinamis -->
                        </tbody>
                    </table>
                </div>
                <button type="button" id="add-product" class="mt-4 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 shadow-lg">
                    + Tambah Produk
                </button>
            </div>

            <!-- Tombol Simpan -->
            <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold py-3 rounded-lg hover:from-blue-600 hover:to-blue-700 shadow-lg">
                Simpan Order
            </button>
        </form>
    </div>

    <!-- Template untuk Baris Produk (Hidden) -->
    <template id="product-row-template">
        <tr class="product-row border-b border-gray-200 hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3">
                <input type="text" name="products[][name]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Nama Produk" required>
            </td>
            <td class="px-4 py-3">
                <input type="number" name="products[][quantity]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Jumlah" required>
            </td>
            <td class="px-4 py-3">
                <input type="number" name="products[][price]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Harga" step="0.01" required>
            </td>
            <td class="px-4 py-3">
                <span class="total-price">0</span>
            </td>
            <td class="px-4 py-3">
                <button type="button" class="remove-product px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">Hapus</button>
            </td>
        </tr>
    </template>

    <!-- Script untuk Menambahkan dan Menghapus Produk -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const productList = document.getElementById('product-list');
            const addProductButton = document.getElementById('add-product');
            const template = document.getElementById('product-row-template').content;

            let productIndex = 0;

            // Fungsi untuk menambahkan produk
            addProductButton.addEventListener('click', function () {
                const newRow = document.importNode(template, true);
                
                // Update name attribute agar bisa dikirim dalam array
                newRow.querySelector('input[name="products[][name]"]').name = `products[${productIndex}][name]`;
                newRow.querySelector('input[name="products[][quantity]"]').name = `products[${productIndex}][quantity]`;
                newRow.querySelector('input[name="products[][price]"]').name = `products[${productIndex}][price]`;

                productList.appendChild(newRow);
                productIndex++;
            });

            // Fungsi untuk menghapus produk
            productList.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-product')) {
                    e.target.closest('.product-row').remove();
                }
            });

            // Fungsi untuk menghitung total harga
            productList.addEventListener('input', function (e) {
                if (e.target.name.includes('quantity') || e.target.name.includes('price')) {
                    const row = e.target.closest('.product-row');
                    const quantity = row.querySelector('input[name*="quantity"]').value || 0;
                    const price = row.querySelector('input[name*="price"]').value || 0;
                    const total = (quantity * price).toFixed(2);
                    row.querySelector('.total-price').textContent = total;
                }
            });
        });
    </script>
@endsection