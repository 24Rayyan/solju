<nav class="bg-gradient-to-r from-pink-400 to-pink-500 p-4 shadow-lg">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ route('orders.index') }}" class="text-2xl font-bold text-white hover:text-pink-100 transition duration-300">Solju</a>
        <ul class="flex gap-8">
            <li>
                <a href="{{ route('orders.create') }}" class="text-white hover:text-pink-100 hover:underline transition duration-300">
                    Tambah Order
                </a>
            </li>
        </ul>
    </div>
</nav>