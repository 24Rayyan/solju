<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solju Order Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f9fafb, #f3f4f6); /* Gradient background */
        }
        .container-main {
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            backdrop-filter: blur(10px); /* Blur effect */
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Soft shadow */
        }
    </style>
</head>
<body class="text-gray-800">
    @include('layouts.navbar')
    <div class="container mx-auto mt-8 p-8 container-main">
        @yield('content')
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const currencyInputs = document.querySelectorAll('.currency-input');
    
            currencyInputs.forEach(input => {
                // Format nilai saat input kehilangan fokus
                input.addEventListener('blur', function () {
                    let value = this.value.replace(/[^0-9]/g, ''); // Hapus semua karakter non-angka
                    value = parseInt(value); // Konversi ke bilangan bulat
                    this.value = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0, // Tidak ada desimal
                        maximumFractionDigits: 0  // Tidak ada desimal
                    }).format(value).replace('Rp', '').trim(); // Format sebagai mata uang
                });
    
                // Hapus format saat input mendapatkan fokus
                input.addEventListener('focus', function () {
                    let value = this.value.replace(/[^0-9]/g, ''); // Hapus semua karakter non-angka
                    this.value = value; // Tampilkan nilai tanpa format
                });
            });
        });
    </script>
</body>
</html>