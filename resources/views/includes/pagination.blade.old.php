@if ($paginator->lastPage() > 1)
<ul class="uk-pagination">
    <li class="{{ ($paginator->currentPage() == 1) ? ' uk-disabled' : '' }}">
        <a href="{{ $paginator->url(1) }}"><i class="uk-icon-angle-left uk-text-bold"></i></a>
    </li>
    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
        <li class="{{ ($paginator->currentPage() == $i) ? ' uk-active' : '' }}">
            <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
        </li>
    @endfor
    <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' uk-disabled' : '' }}">
        <a href="{{ $paginator->url($paginator->currentPage()+1) }}"><i class="uk-icon-angle-right uk-text-bold"></i></a>
    </li>
</ul>
@endif