@props(['complex', 'class' => ''])

@php
  function formatPrice($price) {
    return number_format($price, 0, ',', '.') . ' IDR';
  }
@endphp

<div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 {{ $class }}">
  <div class="relative">
    <img
      src="{{ $complex->image }}"
      alt="{{ $complex->name }}"
      class="w-full h-64 object-cover"
    />
    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full">
      <div class="flex items-center space-x-1">
        {{-- Star icon --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-500 fill-current" viewBox="0 0 24 24" fill="currentColor" stroke="none">
          <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
        </svg>
        <span class="text-sm font-medium">Premium</span>
      </div>
    </div>
  </div>

  <div class="p-6">
    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $complex->name }}</h3>

    <div class="flex items-center text-gray-600 mb-3">
      {{-- MapPin icon --}}
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s8-4.5 8-10a8 8 0 10-16 0c0 5.5 8 10 8 10z" />
      </svg>
      <span class="text-sm">{{ $complex->location }}</span>
    </div>

    <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $complex->description }}</p>

    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center text-gray-600">
        {{-- Home icon --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75L12 4l9 5.75M12 4v16" />
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 22h6M9 14h6" />
        </svg>
        <span class="text-sm">{{ $complex->totalUnits }} Units</span>
      </div>
      <div class="text-right">
        <div class="text-sm text-gray-500">Starting from</div>
        <div class="text-lg font-bold text-blue-600">
          {{ formatPrice($complex->priceRange->min) }}
        </div>
      </div>
    </div>

    <div class="mb-4">
      <h4 class="text-sm font-semibold text-gray-900 mb-2">Key Features:</h4>
      <div class="flex flex-wrap gap-2">
        @foreach (array_slice($complex->features, 0, 4) as $feature)
          <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-full">{{ $feature }}</span>
        @endforeach
      </div>
    </div>

    <div class="mb-6">
      <h4 class="text-sm font-semibold text-gray-900 mb-2">Amenities:</h4>
      <div class="flex flex-wrap gap-2">
        @foreach (array_slice($complex->amenities, 0, 3) as $amenity)
          <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-full">{{ $amenity }}</span>
        @endforeach

        @if (count($complex->amenities) > 3)
          <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">
            +{{ count($complex->amenities) - 3 }} more
          </span>
        @endif
      </div>
    </div>

    <a href="{{ url('/complexes/' . $complex->id) }}"
       class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium"
    >
      Explore Complex
    </a>
  </div>
</div>
