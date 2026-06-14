{{-- resources/views/partials/tool-card.blade.php --}}
{{-- Usage: @include('partials.tool-card', ['tool' => $tool, 'delay' => 0]) --}}

@php
    $delay = $delay ?? 0;
    $imgSrc = $tool->primaryImage?->public_url ?? ($tool->images->first()?->media?->public_url ?? null);
@endphp

<article class="tool-card part-card revealed" data-reveal data-reveal-delay="{{ $delay }}">

    {{-- Image --}}
    <a href="{{ route('tools.show', $tool->slug) }}" class="tool-card-img part-card-img" tabindex="-1">
        @if ($imgSrc)
            <img src="{{ $imgSrc }}" alt="{{ $tool->name }}" loading="lazy">
        @else
            <div class="tool-card-img-placeholder">
                <i class="fa-solid fa-hammer"></i>
            </div>
        @endif

        {{-- Badges --}}
        <div class="tool-card-badges">
            @if ($tool->is_on_sale)
                <span class="tool-badge tool-badge--sale">Sale</span>
            @endif
            @if ($tool->is_featured)
                <span class="tool-badge tool-badge--featured">Featured</span>
            @endif
            @if ($tool->stock_status === 'out_of_stock')
                <span class="tool-badge tool-badge--oos">Out of Stock</span>
            @endif
        </div>
    </a>

    {{-- Body --}}
    <div class="tool-card-body part-card-body">
        @if ($tool->brand)
            <div class="tool-card-brand part-card-make">{{ $tool->brand }}</div>
        @endif

        <h3 class="tool-card-name part-card-name">
            <a href="{{ route('tools.show', $tool->slug) }}">{{ $tool->name }}</a>
        </h3>

        <div class="tool-card-meta part-card-part-no">
            @if ($tool->sku)
                <span>SKU: {{ $tool->sku }}</span>
            @endif
            @if ($tool->ships_worldwide)
                <span><i class="fa-solid fa-globe" style="color:var(--orange);"></i> Ships Worldwide</span>
            @endif
        </div>

        @if ($tool->short_description)
            <p class="tool-card-desc">{{ Str::limit($tool->short_description, 80) }}</p>
        @endif
    </div>

    {{-- Footer --}}
    <div class="tool-card-footer">
        <div class="tool-card-price part-card-footer">
            @if ($tool->is_on_sale)
                <span class="tool-price-sale">${{ number_format($tool->sale_price, 2) }}</span>
                <span class="tool-price-original">${{ number_format($tool->price, 2) }}</span>
            @else
                <span class="tool-price"
                    style="font-size:12px;padding:6px 12px;">${{ number_format($tool->price, 2) }}</span>
            @endif

            <a href="{{ route('tools.show', $tool->slug) }}" class="part-card-cta">
                View <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

    </div>

</article>
