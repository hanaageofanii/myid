<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT. Purnama Karya Bersama</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <div class="text-2xl font-bold text-green-600 flex items-center gap-2">
                    <img src="/image/logo.png" alt="Logo PT. Purnama Karya Bersama" class="h-20 w-auto !important">
                </div>                             
                <!-- Menu Navbar -->
                <div class="hidden md:flex space-x-8">
                    <a href="#" class="text-gray-700 hover:text-green-600">Home</a>
                    <a href="#" class="text-gray-700 hover:text-green-600">About</a>
                    <a href="#" class="text-gray-700 hover:text-green-600">Booking</a>
                    <a href="/admin/login" class="text-gray-700 hover:text-green-600">Login</a>
                </div>
                <!-- Tombol Mobile Menu (Muncul di Layar Kecil) -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile Menu (Muncul saat Tombol di Klik) -->
        <div id="mobile-menu" class="hidden md:hidden bg-white">
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-green-50">Home</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-green-50">About</a>
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-green-50">Booking</a>
            <a href="/admin/login" class="block px-4 py-2 text-gray-700 hover:bg-green-50">Login</a>
        </div>
    </nav>

    <!-- Header -->
    <header class="bg-gradient-to-r from-orange-600 to-yellow-600 text-white py-16">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-5xl font-bold mb-4">Perumahan Berkualitas untuk Masa Depan Anda</h1>
            <p class="text-xl">Temukan rumah impian Anda di tiga perumahan terbaik kami.</p>
        </div>
    </header>

    <!-- Section Perumahan -->
    <section class="py-16">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Perumahan 1 -->
                <div class="bg-white rounded-xl shadow-2xl overflow-hidden transform transition duration-500 hover:scale-105">
                    <img src="perumahan1.jpg" alt="Perumahan Green Valley" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold mb-2 text-green-700">Perumahan Green Valley</h2>
                        <p class="text-gray-600">Lokasi strategis dengan fasilitas lengkap. Hunian nyaman di tengah kota.</p>
                        <a href="#" class="mt-4 inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-300">Lihat Detail</a>
                    </div>
                </div>

                <!-- Perumahan 2 -->
                <div class="bg-white rounded-xl shadow-2xl overflow-hidden transform transition duration-500 hover:scale-105">
                    <img src="perumahan2.jpg" alt="Perumahan Sunset Hills" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold mb-2 text-orange-600">Perumahan Sunset Hills</h2>
                        <p class="text-gray-600">Pemandangan indah, udara segar, dan lingkungan yang asri.</p>
                        <a href="#" class="mt-4 inline-block bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition duration-300">Lihat Detail</a>
                    </div>
                </div>

                <!-- Perumahan 3 -->
                <div class="bg-white rounded-xl shadow-2xl overflow-hidden transform transition duration-500 hover:scale-105">
                    <img src="perumahan3.jpg" alt="Perumahan Ocean Breeze" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold mb-2 text-blue-600">Perumahan Ocean Breeze</h2>
                        <p class="text-gray-600">Dekat dengan pantai, cocok untuk Anda yang mencintai suasana pantai.</p>
                        <a href="#" class="mt-4 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-orange-600 to-yellow-600 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                <!-- Bagian Hak Cipta -->
                <div class="mb-8 md:mb-0">
                    <h3 class="text-xl font-bold mb-4 text-white">PT. Purnama Karya Bersama</h3>
                    <p class="text-white-400">&copy; 2024 PT. Purnama Karya Bersama. All rights reserved.</p>
                </div>

                <!-- Bagian Alamat -->
                <div class="mb-8 md:mb-0">
                    <h3 class="text-xl font-bold mb-4">Alamat Kami</h3>
                    <p class="text-white">
                        Jl. Raya Perumahan No. 123<br>
                        Kota Bandung, Jawa Barat<br>
                        Indonesia, 40123
                    </p>
                </div>

                <!-- Bagian Kontak -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Hubungi Kami</h3>
                    <p class="text-white">
                        Telepon: <a href="tel:+622112345678" class="hover:text-green-500 transition duration-300">+62 21 1234 5678</a><br>
                        Email: <a href="mailto:info@purnamakarya.com" class="hover:text-green-500 transition duration-300">info@purnamakarya.com</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Script untuk Mobile Menu -->
    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>