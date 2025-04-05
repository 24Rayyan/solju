<nav class="bg-gradient-to-r from-gray-700 to-gray-900 p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <!-- Logo atau Nama Brand -->
        <a href="{{ route('orders.index') }}" class="text-2xl font-bold text-white hover:text-gray-300 transition duration-300">
            Solju
        </a>

        <!-- Menu Navigasi -->
        <ul class="flex gap-8">
            <!-- Link ke Tambah Order -->
            <li>
                <a href="{{ route('orders.create') }}" class="text-white hover:text-gray-300 hover:underline transition duration-300">
                    Tambah Order
                </a>
            </li>

            <!-- Link ke Sales Chart -->
            <li>
                <a href="{{ route('orders.salesChart') }}" class="text-white hover:text-gray-300 hover:underline transition duration-300">
                    Sales Chart
                </a>
            </li>
        </ul>
    </div>
</nav>