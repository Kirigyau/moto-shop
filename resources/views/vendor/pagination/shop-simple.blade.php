@if ($paginator->hasPages())
    <nav class="pagination-wrap" aria-label="Страницы каталога">
        <ul class="pagination">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">← Назад</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">← Назад</a>
                </li>
            @endif
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Вперёд →</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">Вперёд →</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
