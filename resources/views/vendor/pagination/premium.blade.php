@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="pagination-premium">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="page-link disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-link" rel="prev" aria-label="{{ __('pagination.previous') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="page-numbers">
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $window = 2; // Cantidad de páginas a cada lado

                // Determinamos el rango inicial y final de la ventana
                $start = max(1, $currentPage - $window);
                $end = min($lastPage, $currentPage + $window);

                // Ajustamos si estamos al principio o al final para mantener 5 elementos si es posible
                if ($currentPage <= $window) {
                    $end = min($lastPage, 5);
                }
                if ($currentPage > $lastPage - $window) {
                    $start = max(1, $lastPage - 4);
                }
            @endphp

            {{-- Primera página y puntos si es necesario --}}
            @if ($start > 1)
                <a href="{{ $paginator->url(1) }}" class="page-link">1</a>
                @if ($start > 2)
                    <span class="page-link dots">...</span>
                @endif
            @endif

            {{-- Rango de páginas central --}}
            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $currentPage)
                    <span class="page-link active" aria-current="page">{{ $i }}</span>
                @else
                    <a href="{{ $paginator->url($i) }}" class="page-link">{{ $i }}</a>
                @endif
            @endfor

            {{-- Última página y puntos si es necesario --}}
            @if ($end < $lastPage)
                @if ($end < $lastPage - 1)
                    <span class="page-link dots">...</span>
                @endif
                <a href="{{ $paginator->url($lastPage) }}" class="page-link">{{ $lastPage }}</a>
            @endif
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-link" rel="next" aria-label="{{ __('pagination.next') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
        @else
            <span class="page-link disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </span>
        @endif
    </nav>
@endif
