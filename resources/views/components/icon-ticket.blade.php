@props(['count' => 0, 'href' => '#', 'title' => 'Riwayat Pemesanan'])

<a href="{{ $href }}" class="p-2 bg-gray-700 rounded-full hover:bg-gray-600 transition relative" title="{{ $title }}">
    <!-- Heroicon Ticket -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
        stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M9 2H15C16.1046 2 17 2.89543 17 4V6.382C16.407 6.14 15.722 6 15 6C13.3431 6 12 7.34315 12 9C12 10.6569 13.3431 12 15 12C15.722 12 16.407 11.86 17 11.618V20C17 21.1046 16.1046 22 15 22H9C7.89543 22 7 21.1046 7 20V17.382C7.593 17.624 8.278 17.764 9 17.764C10.6569 17.764 12 16.421 12 14.764C12 13.1071 10.6569 11.764 9 11.764C8.278 11.764 7.593 11.904 7 12.146V4C7 2.89543 7.89543 2 9 2Z" />
    </svg>

    @if($count > 0)
        <span
            class="absolute -top-1 -right-1 bg-red-500 text-xs w-5 h-5 flex items-center justify-center rounded-full font-semibold">
            {{ $count }}
        </span>
    @endif
</a>
