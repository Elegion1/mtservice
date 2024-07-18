{{-- <li class="nav-item">
    <a class="nav-link {{ $slot }} " href="{{ route('transfer') }}">transfer</a>
</li>
<li class="nav-item">
    <a class="nav-link {{ $slot }} " href="{{ route('escursioni') }}">escursioni</a>
</li>
<li class="nav-item">
    <a class="nav-link {{ $slot }} " href="{{ route('noleggio') }}">noleggio
        auto</a>
</li>
<li class="nav-item">
    <a class="nav-link {{ $slot }} " href="{{ route('prezziDestinazioni') }}">prezzi e destinazioni</a>
</li>
<li class="nav-item">
    <a class="nav-link {{ $slot }} " href="{{ route('diconoDiNoi') }}">dicono di noi</a>
</li>
<li class="nav-item">
    <a class="nav-link {{ $slot }} " href="{{ route('faq') }}">FAQ</a>
</li>
<li class="nav-item">
    <a class="nav-link {{ $slot }} " href="#">su di noi</a>
</li>
<li class="nav-item">
    <a class="nav-link {{ $slot }} " href="{{ route('contattaci') }}">contatti</a>
</li>
<li class="nav-item">
    <a class="nav-link {{ $slot }} " href="{{ route('partners') }}">partners</a>
</li>
<li class="nav-item">
    <a class="nav-link {{ $slot }} " href="{{ route('dashboard') }}">Dashboard</a>
</li> --}}



@foreach ($pages as $page)
    @if ($page->show)
        <li class="nav-item">
            <a class="nav-link {{ $slot }} @if (Route::currentRouteName() == $page->link) active_nav @endif"
                href="{{ route($page->link) }}">{{ __('ui.' . $page->name) }}</a>
        </li>
    @endif
@endforeach
