@php
    // Se $linksToShow non è definito o è vuoto, mostra tutti i link normalmente
    $showAll = empty($linksToShow) || !is_array($linksToShow);

    // Separare i link da mostrare e quelli da mettere nel dropdown
    $visibleLinks = $showAll ? $pages : $pages->filter(fn($page) => in_array($page->link, $linksToShow));
    $dropdownLinks = $showAll ? collect() : $pages->filter(fn($page) => !in_array($page->link, $linksToShow));
    // Trova la pagina attiva nel dropdown
    $activeDropdownPage = $dropdownLinks->first(fn($page) => Route::currentRouteName() == $page->link);
    $dropdownLabel = $activeDropdownPage ? __('ui.' . $activeDropdownPage->name) : strtoupper(__('ui.more'));
@endphp

<ul class="navbar-nav">
    {{-- Mostra i link visibili --}}
    
    @foreach ($visibleLinks as $page)
        @if ($page->show)
            <li class="nav-item">
                <a class="nav-link {{ $slot }} @if (Route::currentRouteName() == $page->link) active_nav @endif"
                    href="{{ route($page->link) }}">{{ __('ui.' . $page->name) }}</a>
            </li>
        @endif
    @endforeach

    {{-- Mostra il dropdown se ci sono link rimanenti --}}
    @if ($dropdownLinks->isNotEmpty())
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{$slot}} @if ($activeDropdownPage) active_nav @endif" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                {{ $dropdownLabel }} 
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark bg_dropdown" aria-labelledby="navbarDropdown">
                @foreach ($dropdownLinks as $page)
                    @if ($page->show)
                        <li>
                            <a class="dropdown-item text-uppercase text-white text-shadow @if (Route::currentRouteName() == $page->link) active_nav @endif"
                                href="{{ route($page->link) }}">{{ __('ui.' . $page->name) }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </li>
    @endif
</ul>
