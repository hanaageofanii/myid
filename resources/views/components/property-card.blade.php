@props(['property', 'class' => ''])

@php
  function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' IDR';
  }

  // Fungsi untuk ubah complexId jadi judul proper (contoh: royal-residence -> Royal Residence)
  function formatComplexId($complexId) {
    return ucwords(str_replace('-', ' ', $complexId));
  }
@endphp

<div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 {{ $class }}">
  <div class="relative">
    <img
      src="{{ $property->image }}"
      alt="{{ $property->title }}"
      class="w-full h-48 object-cover"
    />
    <button class="absolute top-3 right-3 p-2 bg-white/80 backdrop-blur-sm rounded-full hover:bg-white transition-colors" type="button">
      {{-- Heart icon --}}
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 010 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
      </svg>
    </button>
    <div class="absolute bottom-3 left-3 bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-medium">
      {{ formatComplexId($property->complexId) }}
    </div>
  </div>

  <div class="p-6">
    <div class="flex items-start justify-between mb-2">
      <h3 class="text-xl font-bold text-gray-900 line-clamp-1">{{ $property->title }}</h3>
      <span class="text-2xl font-bold text-blue-600">{{ formatPrice($property->price) }}</span>
    </div>

    <div class="flex items-center text-gray-600 mb-4">
      {{-- MapPin icon --}}
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s8-4.5 8-10a8 8 0 10-16 0c0 5.5 8 10 8 10z" />
      </svg>
      <span class="text-sm">{{ $property->location }}</span>
    </div>

    <div class="flex items-center justify-between mb-4 text-gray-600">
      <div class="flex items-center space-x-4">
        <div class="flex items-center">
          {{-- Bed icon --}}
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18v4H3v-4z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 14v5M19 14v5" />
          </svg>
          <span class="text-sm">{{ $property->bedrooms }}</span>
        </div>
        <div class="flex items-center">
          {{-- Bath icon --}}
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 16h6" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v3M8 6h8a2 2 0 012 2v7a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2z" />
          </svg>
          <span class="text-sm">{{ $property->bathrooms }}</span>
        </div>
        <div class="flex items-center">
          {{-- Square icon --}}
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <rect width="18" height="14" x="3" y="7" rx="2" ry="2" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18" />
          </svg>
          <span class="text-sm">{{ $property->area }}m²</span>
        </div>
      </div>
    </div>

    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $property->description }}</p>

    <div class="flex flex-wrap gap-2 mb-4">
      @foreach (array_slice($property->features, 0, 3) as $feature)
        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">{{ $feature }}</span>
      @endforeach
      @if (count($property->features) > 3)
        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">
          +{{ count($property->features) - 3 }} more
        </span>
      @endif
    </div>

    <a href="{{ url('/properties/' . $property->id) }}"
       class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium"
    >
      View Details
    </a>
  </div>
</div>
