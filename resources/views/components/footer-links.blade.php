@foreach ($pages as $page)
    <li>
        <a class="text-decoration-none text-reset text-capitalize p-0 text-small"
            href="{{ route($page->link) }}">{{ __('ui.' . $page->name) }}</a>
    </li>
@endforeach
