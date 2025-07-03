<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purnama Karya Bersama</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        }
        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .booking-modal {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="font-sans">
  <!-- Navigation -->
  <nav class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo + Menu -->
        <div class="flex items-center space-x-8">
          <!-- Logo -->
          <div class="flex-shrink-0">
            <img
                src="/image/logo.png"
                alt="Logo PKB"
                class="h-8 w-auto"
            />
          </div>

          <!-- Menu (hidden di mobile) -->
          <div class="hidden md:flex space-x-6">
            <a href="#home" class="text-blue-900 px-3 py-2 rounded-md text-sm font-medium">Home</a>
            <a href="#properties" class="text-gray-600 hover:text-blue-900 px-3 py-2 rounded-md text-sm font-medium">Perumahan</a>
            <a href="#about" class="text-gray-600 hover:text-blue-900 px-3 py-2 rounded-md text-sm font-medium">Tentang Kami</a>
            <a href="#contact" class="text-gray-600 hover:text-blue-900 px-3 py-2 rounded-md text-sm font-medium">Kontak</a>
          </div>
        </div>

        <!-- Booking Button (hidden di mobile) -->
        <div class="hidden md:flex">
          <button
            onclick="openBookingModal()"
            class="bg-blue-900 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-800 transition"
          >
            Booking Sekarang
          </button>
        </div>
      </div>
    </div>
  </nav>

<!-- Hero Section -->
<section id="home" class="hero-gradient text-white py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="md:flex items-center justify-between">
      <!-- Text Content -->
      <div class="md:w-1/2 mb-10 md:mb-0">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Temukan Hunian Idaman Anda</h1>
        <p class="text-lg mb-6">Grand Property menghadirkan konsep perumahan modern dengan fasilitas lengkap dan lokasi strategis.</p>
        <div class="flex space-x-4">
          <button onclick="openBookingModal()" class="bg-white text-blue-900 px-6 py-3 rounded-md font-medium hover:bg-gray-100 transition">Booking Sekarang</button>
          <a href="#properties" class="border border-white text-white px-6 py-3 rounded-md font-medium hover:bg-white hover:text-blue-900 transition">Lihat Perumahan</a>
        </div>
      </div>

      <!-- Carousel -->
      <div class="md:w-1/2 relative">
        <div id="carousel" class="overflow-hidden rounded-xl shadow-2xl relative group">
          <div id="carousel-images" class="flex transition-transform duration-700 ease-in-out">
            <div class="relative w-full flex-shrink-0">
              <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/bac75b85-ceee-4f29-b790-f02dcdf001b0.png" alt="Rumah 1" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105">
              <div class="absolute bottom-4 left-4 text-white bg-blue-900/70 px-3 py-2 rounded">Rumah Tipe A</div>
            </div>
            <div class="relative w-full flex-shrink-0">
              <img src="https://images.unsplash.com/photo-1560448070-94f28f8e4f76?auto=format&fit=crop&w=800&q=80" alt="Rumah 2" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105">
              <div class="absolute bottom-4 left-4 text-white bg-blue-900/70 px-3 py-2 rounded">Rumah Tipe B</div>
            </div>
            <div class="relative w-full flex-shrink-0">
              <img src="https://images.unsplash.com/photo-1600585154517-3e3174eb47a5?auto=format&fit=crop&w=800&q=80" alt="Rumah 3" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105">
              <div class="absolute bottom-4 left-4 text-white bg-blue-900/70 px-3 py-2 rounded">Rumah Tipe C</div>
            </div>
          </div>

          {{-- <!-- Navigation -->
          <button id="prevBtn" class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white text-blue-900 rounded-full p-2 shadow hover:bg-blue-100 transition">‹</button>
          <button id="nextBtn" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white text-blue-900 rounded-full p-2 shadow hover:bg-blue-100 transition">›</button> --}}

          <!-- Dots -->
          <div class="absolute bottom-2 w-full flex justify-center gap-2">
            <span class="dot w-3 h-3 rounded-full bg-white opacity-50 hover:opacity-100 cursor-pointer transition"></span>
            <span class="dot w-3 h-3 rounded-full bg-white opacity-50 hover:opacity-100 cursor-pointer transition"></span>
            <span class="dot w-3 h-3 rounded-full bg-white opacity-50 hover:opacity-100 cursor-pointer transition"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section> 

<section id="properties" class="py-32 bg-gray-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Pilihan Perumahan Kami</h2>
      <p class="text-gray-600 max-w-xl mx-auto">Tersedia berbagai pilihan perumahan dengan fasilitas lengkap dan harga kompetitif.</p>
    </div>

<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 justify-center">
  <!-- Property Card -->
  <div class="bg-white rounded-md shadow-md hover:shadow-md transition-all duration-300 overflow-hidden max-w-xs max-h-[400px] mx-auto">
    <div class="h-48 w-full overflow-hidden">
      <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/fd581300-8d86-42ad-bbba-562a37f6af75.png" alt="Green Valley"
        class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
    </div>
    <div class="p-4">
      <h3 class="text-base font-semibold text-gray-900 mb-1">Green Valley Residence</h3>
      <p class="text-gray-600 text-xs mb-3">Cluster premium dengan konsep green living di lokasi strategis.</p>

      <div class="flex justify-between items-center mb-3">
        <span class="text-blue-900 font-semibold text-sm">Rp 500 Juta</span>
        <span class="bg-green-100 text-green-800 text-[10px] font-medium px-2 py-0.5 rounded">Tersedia</span>
      </div>

      <ul class="text-xs text-gray-600 space-y-1 mb-3">
        <li class="flex items-center">
          <svg class="w-3.5 h-3.5 mr-2 text-blue-900" fill="currentColor" viewBox="0 0 20 20"><path /></svg>
          Tipe 36/72 - 70/120
        </li>
        <li class="flex items-center">
          <svg class="w-3.5 h-3.5 mr-2 text-blue-900" fill="currentColor" viewBox="0 0 20 20"><path /></svg>
          Lokasi: Jakarta Selatan
        </li>
      </ul>

      <button onclick="openBookingModal('Green Valley Residence')" class="w-full py-2 px-4 text-sm bg-blue-900 text-white rounded-md hover:bg-blue-800 transition">
        Booking Sekarang
      </button>
    </div>
  </div>

  <!-- Property Card -->
  <div class="bg-white rounded-md shadow-md hover:shadow-md transition-all duration-300 overflow-hidden max-w-xs max-h-[400px] mx-auto">
    <div class="h-48 w-full overflow-hidden">
      <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/fd581300-8d86-42ad-bbba-562a37f6af75.png" alt="Green Valley"
        class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
    </div>
    <div class="p-4">
      <h3 class="text-base font-semibold text-gray-900 mb-1">Green Valley Residence</h3>
      <p class="text-gray-600 text-xs mb-3">Cluster premium dengan konsep green living di lokasi strategis.</p>

      <div class="flex justify-between items-center mb-3">
        <span class="text-blue-900 font-semibold text-sm">Rp 500 Juta</span>
        <span class="bg-green-100 text-green-800 text-[10px] font-medium px-2 py-0.5 rounded">Tersedia</span>
      </div>

      <ul class="text-xs text-gray-600 space-y-1 mb-3">
        <li class="flex items-center">
          <svg class="w-3.5 h-3.5 mr-2 text-blue-900" fill="currentColor" viewBox="0 0 20 20"><path /></svg>
          Tipe 36/72 - 70/120
        </li>
        <li class="flex items-center">
          <svg class="w-3.5 h-3.5 mr-2 text-blue-900" fill="currentColor" viewBox="0 0 20 20"><path /></svg>
          Lokasi: Jakarta Selatan
        </li>
      </ul>

      <button onclick="openBookingModal('Green Valley Residence')" class="w-full py-2 px-4 text-sm bg-blue-900 text-white rounded-md hover:bg-blue-800 transition">
        Booking Sekarang
      </button>
    </div>
  </div>
  <!-- Property Card -->
  <div class="bg-white rounded-md shadow-md hover:shadow-md transition-all duration-300 overflow-hidden max-w-xs max-h-[400px] mx-auto">
    <div class="h-48 w-full overflow-hidden">
      <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/fd581300-8d86-42ad-bbba-562a37f6af75.png" alt="Green Valley"
        class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
    </div>
    <div class="p-4">
      <h3 class="text-base font-semibold text-gray-900 mb-1">Green Valley Residence</h3>
      <p class="text-gray-600 text-xs mb-3">Cluster premium dengan konsep green living di lokasi strategis.</p>

      <div class="flex justify-between items-center mb-3">
        <span class="text-blue-900 font-semibold text-sm">Rp 500 Juta</span>
        <span class="bg-green-100 text-green-800 text-[10px] font-medium px-2 py-0.5 rounded">Tersedia</span>
      </div>

      <ul class="text-xs text-gray-600 space-y-1 mb-3">
        <li class="flex items-center">
          <svg class="w-3.5 h-3.5 mr-2 text-blue-900" fill="currentColor" viewBox="0 0 20 20"><path /></svg>
          Tipe 36/72 - 70/120
        </li>
        <li class="flex items-center">
          <svg class="w-3.5 h-3.5 mr-2 text-blue-900" fill="currentColor" viewBox="0 0 20 20"><path /></svg>
          Lokasi: Jakarta Selatan
        </li>
      </ul>

      <button onclick="openBookingModal('Green Valley Residence')" class="w-full py-2 px-4 text-sm bg-blue-900 text-white rounded-md hover:bg-blue-800 transition">
        Booking Sekarang
      </button>
    </div>
  </div>
    </div>
  </div>
</section>


    <!-- About Section -->
    <section id="about" class="py-48 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="md:flex items-center">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <div class="relative overflow-hidden rounded-lg shadow-xl h-96">
                        <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/2f107d8a-ed12-474c-b1c3-e899569d3ae0.png" alt="Kantor pusat Grand Property dengan desain modern dan tim profesional yang sedang bekerja" class="w-full h-full object-cover">
                    </div>
                </div>
                <div class="md:w-1/2 md:pl-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Tentang Grand Property</h2>
                    <p class="text-gray-600 mb-4">Grand Property adalah pengembang properti terpercaya dengan pengalaman lebih dari 15 tahun dalam membangun perumahan berkualitas di seluruh Indonesia.</p>
                    <p class="text-gray-600 mb-6">Kami berkomitmen untuk menyediakan hunian nyaman dengan fasilitas lengkap dan lingkungan yang asri bagi keluarga Anda.</p>

                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-blue-900" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-lg font-semibold text-gray-900">15+ Tahun Pengalaman</h4>
                                <p class="text-gray-600 text-sm">Berdiri sejak 2008 dengan puluhan proyek sukses</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-blue-900" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10a1 1 0 01-1.644 0l-7-10A1 1 0 014 7h4V2a1 1 0 011.3-.954z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-lg font-semibold text-gray-900">3.500+ Rumah Terbangun</h4>
                                <p class="text-gray-600 text-sm">Ribuan keluarga sudah mempercayakan huniannya pada kami</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-blue-900" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.625 1.132A1 1 0 017.14 1h5.72a1 1 0 01.515.132l3.915 2.2a1 1 0 01.245 1.589L12.73 6.695a1 1 0 01-.295.027H7.564a1 1 0 01-.294-.027L2.415 4.92a1 1 0 01.244-1.588L6.625 1.132zm11.325 3.125v6.25a1 1 0 01-1 1h-2.5a1 1 0 01-1-1v-4.5a1.5 1.5 0 00-1.5-1.5h-2a1.5 1.5 0 00-1.5 1.5v4.5a1 1 0 01-1 1h-2.5a1 1 0 01-1-1v-6.25l5.703 3.206a1 1 0 001.055 0l5.703-3.206z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-lg font-semibold text-gray-900">10+ Award</h4>
                                <p class="text-gray-600 text-sm">Penghargaan untuk kualitas dan inovasi properti</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-blue-900" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-lg font-semibold text-gray-900">Garansi 5 Tahun</h4>
                                <p class="text-gray-600 text-sm">Garansi struktur bangunan untuk ketenangan Anda</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Apa Kata Mereka?</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Testimonial dari para penghuni perumahan kami</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 mr-4">
                            <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/10831664-ed46-41f5-861b-64f2f78f6c71.png" alt="Foto pak Budi, penghuni Green Valley Residence" class="w-12 h-12 rounded-full">
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Budi Santoso</h4>
                            <p class="text-sm text-gray-600">Green Valley Residence</p>
                        </div>
                    </div>
                    <p class="text-gray-600">"Sangat puas dengan rumah dan lingkungannya. Fasilitas lengkap, keamanan terjaga, dan tetangga semua ramah. Rekomendasi banget!"</p>
                    <div class="flex mt-4 text-yellow-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 mr-4">
                            <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/fbf00276-415a-4919-b937-40d3f748d7c3.png" alt="Foto ibu Siti, penghuni Silver Lake Estate" class="w-12 h-12 rounded-full">
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Siti Rahayu</h4>
                            <p class="text-sm text-gray-600">Silver Lake Estate</p>
                        </div>
                    </div>
                    <p class="text-gray-600">"Clubhouse dan fasilitasnya sangat lengkap. Anak-anak senang banget karena ada playground yang bagus dan kolam renang yang bersih."</p>
                    <div class="flex mt-4 text-yellow-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 mr-4">
                            <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/8919bf4e-97c7-4eab-83fe-08b0c5ae99a9.png" alt="Foto keluarga pak Andi, penghuni Mountain View" class="w-12 h-12 rounded-full">
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Andi Wijaya</h4>
                            <p class="text-sm text-gray-600">Mountain View</p>
                        </div>
                    </div>
                    <p class="text-gray-600">"Udara segar dan suasana tenang membuat keluarga kami betah tinggal di sini. Akses tol dekat sangat memudahkan mobilitas ke Jakarta."</p>
                    <div class="flex mt-4 text-yellow-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="md:flex items-center">
                <div class="md:w-1/2 mb-10 md:mb-0">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Hubungi Kami</h2>
                    <p class="text-gray-600 mb-6">Kami siap membantu Anda menemukan hunian idaman dengan konsultasi tanpa biaya.</p>

                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-blue-900" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-lg font-semibold text-gray-900">Kantor Pusat</h4>
                                <p class="text-gray-600">Jl. Sudirman No. 45, Jakarta Selatan</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-blue-900" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2 3a1 1 0 011-1h14a1 1 0 011 1v14a1 1 0 01-1 1H3a1 1 0 01-1-1V3zm3 1a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V5a1 1 0 00-1-1H5zm8 0a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V5a1 1 0 00-1-1h-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-lg font-semibold text-gray-900">Showroom</h4>
                                <p class="text-gray-600">Grand Mall Lt. 3, Jakarta Pusat</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-blue-900" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h4a1 1 0 010 2H4v10h4a1 1 0 110 2H4a2 2 0 01-2-2V5zm8 0a2 2 0 012-2h4a2 2 0 012 2v10a2 2 0 01-2 2h-4a2 2 0 01-2-2V5zm3 1a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-lg font-semibold text-gray-900">WhatsApp</h4>
                                <p class="text-gray-600">0812-3456-7890</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-blue-900" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-lg font-semibold text-gray-900">Email</h4>
                                <p class="text-gray-600">info@grandproperty.co.id</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Jam Operasional</h4>
                        <p class="text-gray-600">Senin - Jumat: 08:00 - 17:00<br>Sabtu: 09:00 - 15:00</p>
                    </div>
                </div>

                <div class="md:w-1/2 md:pl-12">
                    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Kirim Pesan</h3>
                        <form>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-medium mb-2" for="name">Nama Lengkap</label>
                                <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent" id="name" type="text">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-medium mb-2" for="email">Email</label>
                                <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent" id="email" type="email">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-medium mb-2" for="phone">Nomor Telepon</label>
                                <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent" id="phone" type="tel">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-medium mb-2" for="subject">Subjek</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent" id="subject">
                                    <option value="" selected disabled>Pilih subjek</option>
                                    <option value="booking">Booking Rumah</option>
                                    <option value="info">Info Perumahan</option>
                                    <option value="kpr">Konsultasi KPR</option>
                                    <option value="lain">Lainnya</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-medium mb-2" for="message">Pesan</label>
                                <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent" id="message" rows="4"></input>
                            </div>
                            <button type="submit" class="w-full bg-blue-900 text-white py-2 rounded hover:bg-blue-800 transition">Kirim Pesan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Modal -->
    <div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Form Booking</h3>
                    <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form id="bookingForm">
                    <input type="hidden" id="propertyName">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="bookingName">Nama Lengkap</label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent" id="bookingName" type="text" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="bookingPhone">Nomor Telepon</label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent" id="bookingPhone" type="tel" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="bookingEmail">Email</label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent" id="bookingEmail" type="email" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="propertyName">Pilih Properti</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent" id="propertyName" required>
                            <option value="" selected disabled>Pilih properti</option>
                            <option value="Green Hill Residence">Green Hill Residence</option>
                            <option value="Emerald Garden">Emerald Garden</option>
                            <option value="Grand Palm City">Grand Palm City</option>
                            <option value="Sunrise Park">Sunrise Park</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="bookingDate">Tanggal Kunjungan</label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent" id="bookingDate" type="date" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="bookingNotes">Catatan Tambahan</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent" id="bookingNotes" rows="3"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-blue-900 text-white py-2 rounded hover:bg-blue-800 transition">Submit Booking</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/0b85b7d7-ecf7-4c35-b9ed-3762209bfb50.png" alt="Logo Grand Property versi putih untuk footer website" class="h-8 mb-4">
                    <p class="text-gray-400 text-sm">Grand Property menghadirkan perumahan berkualitas dengan fasilitas lengkap untuk keluarga Indonesia.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Perumahan</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#properties" class="hover:text-white transition">Green Valley Residence</a></li>
                        <li><a href="#properties" class="hover:text-white transition">Silver Lake Estate</a></li>
                        <li><a href="#properties" class="hover:text-white transition">Mountain View</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Link Cepat</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#home" class="hover:text-white transition">Home</a></li>
                        <li><a href="#properties" class="hover:text-white transition">Perumahan</a></li>
                        <li><a href="#about" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#contact" class="hover:text-white transition">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontak Kami</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            Jl. Sudirman No. 45, Jakarta Selatan
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.243 5.757a6 6 0 10-.986 9.284 1 1 0 111.087 1.678A8 8 0 1118 10a3 3 0 01-4.8 2.401A4 4 0 1114 10a1 1 0 102 0c0-1.537-.586-3.06-1.757-4.243zM12 10a2 2 0 10-4 0 2 2 0 004 0z" clip-rule="evenodd"></path>
                            </svg>
                            info@grandproperty.co.id
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                            </svg>
                            021-12345678
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm mb-4 md:mb-0">© 2023 Grand Property. All rights reserved.</p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"></path>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"></path>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"></path>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
  const images = document.getElementById('carousel-images');
  const dots = document.querySelectorAll('.dot');
  const totalSlides = images.children.length;
  let index = 0;

  function updateCarousel() {
    images.style.transform = `translateX(-${index * 100}%)`;
    dots.forEach((dot, i) => {
      dot.classList.toggle('opacity-100', i === index);
      dot.classList.toggle('opacity-50', i !== index);
    });
  }
  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      index = i;
      updateCarousel();
    });
  });

  // Optional: autoplay
  setInterval(() => {
    index = (index + 1) % totalSlides;
    updateCarousel();
  }, 5000);

  updateCarousel();

    
        function openBookingModal(propertyName = '') {
            document.getElementById('propertyName').value = propertyName;
            document.getElementById('bookingModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeBookingModal() {
            document.getElementById('bookingModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('bookingModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBookingModal();
            }
        });

document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const propertyName = document.getElementById('propertyName').value;
    const name = document.getElementById('bookingName').value;
    const phone = document.getElementById('bookingPhone').value;
    const email = document.getElementById('bookingEmail').value;
    const date = document.getElementById('bookingDate').value;
    const notes = document.getElementById('bookingNotes').value;

    // Format pesan WhatsApp
    const message = `Halo Grand Property,%0ASaya ingin booking properti berikut:%0A
- Properti: ${propertyName || '-'}%0A
- Nama: ${name}%0A
- No. HP: ${phone}%0A
- Email: ${email}%0A
- Tanggal Booking: ${date}%0A
- Catatan: ${notes || '-'}`;

    const phoneNumber = '6281234567890';

    window.open(`https://wa.me/${phoneNumber}?text=${message}`, '_blank');

    this.reset();
    closeBookingModal();
});

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();

                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>

