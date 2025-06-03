{{-- filepath: resources/views/components/price-summary.blade.php --}}
@props([
    'items' => [],
    'totalPrice' => 0,
])

<div class="bg-c rounded py-2 mb-3 form_input">
    @foreach ($items as $item)
        <div class="d-flex justify-content-between mb-2">
            <span>{{ $item['label'] }}</span>
            <span class="fw-semibold">€ {{ number_format($item['value'] ?? 0, 2, ',', '.') }}</span>
        </div>
    @endforeach
    <div class="d-flex justify-content-between border-top pt-2 mt-2">
        <span class="fw-bold">{{ __('ui.totalPrice') }}</span>
        <span class="fw-bold">€ {{ number_format($totalPrice ?? 0, 2, ',', '.') }}</span>
    </div>
</div>
