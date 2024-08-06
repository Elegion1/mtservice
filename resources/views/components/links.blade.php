@foreach ($pages as $page)
    @if ($page->show)
        <li class="nav-item">
            <a class="nav-link {{ $slot }} @if (Route::currentRouteName() == $page->link) active_nav @endif"
                href="{{ route($page->link) }}">{{ __('ui.' . $page->name) }}</a>
        </li>
    @endif
@endforeach
