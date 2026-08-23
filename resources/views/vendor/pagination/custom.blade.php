@if ($paginator->hasPages())
    <div class="custom-pagination-container">
        
        <!-- Page Info -->
        <div class="pagination-info">
            Hal {{ $paginator->currentPage() }}/{{ max(1, $paginator->lastPage()) }} ({{ $paginator->total() }} data)
        </div>

        <!-- Per Page Selector (Visual Only) -->
        <div class="pagination-selector">
            <select class="form-select-custom" style="padding: 4px 8px; font-size: 0.85rem; height: auto;" onchange="changePerPage(this.value)">
                <option value="10" {{ request('per_page') == 10 || !request('per_page') ? 'selected' : '' }}>10 baris</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 baris</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 baris</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 baris</option>
            </select>
        </div>

        <!-- Pagination Links -->
        <div class="pagination-buttons">
            <!-- First Page Link -->
            @if ($paginator->onFirstPage())
                <span class="page-btn disabled">&laquo;</span>
            @else
                <a href="{{ $paginator->url(1) }}" class="page-btn">&laquo;</a>
            @endif

            <!-- Previous Page Link -->
            @if ($paginator->onFirstPage())
                <span class="page-btn disabled">&lsaquo;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="page-btn">&lsaquo;</a>
            @endif

            <!-- Pagination Elements -->
            @foreach ($elements as $element)
                <!-- "Three Dots" Separator -->
                @if (is_string($element))
                    <span class="page-btn disabled">{{ $element }}</span>
                @endif

                <!-- Array Of Links -->
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="page-btn active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            <!-- Next Page Link -->
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="page-btn">&rsaquo;</a>
            @else
                <span class="page-btn disabled">&rsaquo;</span>
            @endif

            <!-- Last Page Link -->
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="page-btn">&raquo;</a>
            @else
                <span class="page-btn disabled">&raquo;</span>
            @endif
        </div>

    </div>

    <style>
        .custom-pagination-container {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 24px;
            margin-top: 16px;
            font-size: 0.85rem;
            flex-wrap: wrap;
        }
        
        .pagination-buttons {
            display: flex;
            align-items: center;
            background-color: #e2e8f0; /* gray background for the group */
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid #cbd5e1;
        }

        .page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            color: #475569;
            text-decoration: none;
            background-color: transparent;
            font-weight: 500;
            border-right: 1px solid #cbd5e1;
            transition: all 0.2s;
        }

        .page-btn:last-child {
            border-right: none;
        }

        .page-btn:hover:not(.disabled):not(.active) {
            background-color: #cbd5e1;
            color: #0f172a;
        }

        .page-btn.active {
            background-color: #0056b3; /* Darker blue */
            color: white;
            font-weight: 600;
        }

        .page-btn.disabled {
            color: #94a3b8;
            cursor: not-allowed;
            background-color: #f1f5f9;
        }

        .pagination-info {
            color: #475569;
            background-color: #f8fafc;
            padding: 6px 12px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .pagination-selector select {
            background-color: white;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            color: #475569;
            cursor: pointer;
        }
    </style>

    <script>
        function changePerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            // Reset to page 1 when changing rows per page
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }
    </script>
@endif
