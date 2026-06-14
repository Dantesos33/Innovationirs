{{-- resources/views/partials/part-card.blade.php --}}
{{-- Usage: @include('partials.part-card', ['part' => $part, 'delay' => 0]) --}}

@php
    $delay = $delay ?? 0;
    $imgSrc = $part->images->first()?->public_url ?? ($part->image_url ?? null);
    $makeSlug = $part->make?->slug;
    $makeName = $part->make?->name;
@endphp

<article class="part-card" data-reveal data-reveal-delay="{{ $delay }}">
    {{-- Image --}}
    <a href="{{ route('parts.show', $part->slug) }}" class="part-card-img" tabindex="-1">
        @if ($imgSrc)
            <img src="{{ $imgSrc }}" alt="{{ $part->name }}" loading="lazy">
        @else
            <div
                style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--gray-100);">
                <i class="fa-solid fa-gear" style="font-size:40px;color:var(--gray-300);"></i>
            </div>
        @endif

        {{-- Badges --}}
        <div class="part-card-badges">
            <span
                class="badge badge-{{ $part->condition_type === 'new' ? 'new' : ($part->condition_type === 'rebuilt' ? 'rebuilt' : 'used') }}">
                {{ ucfirst($part->condition_type ?? 'new') }}
            </span>
            @if ($part->is_featured)
                <span class="badge badge-amber">Featured</span>
            @endif
            @if ($part->sample_image_shown)
                <span class="badge badge-gray" style="font-size:10px;">*Sample Image</span>
            @endif
        </div>
    </a>

    {{-- Body --}}
    <div class="part-card-body">
        @if ($makeName)
            <div class="part-card-make">
                <a href="{{ route('makes.show', $makeSlug) }}" style="color:inherit;">{{ $makeName }}</a>
            </div>
        @endif

        <h3 class="part-card-name">
            <a href="{{ route('parts.show', $part->slug) }}" style="color:inherit;text-decoration:none;">
                {{ $part->name }}
            </a>
        </h3>

        @if ($part->part_number)
            <div class="part-card-part-no">Part #: {{ $part->part_number }}</div>
        @endif

        @if (isset($part->compatibleModels) && $part->compatibleModels->count())
            <div class="part-card-compat">
                <i class="fa-solid fa-check" style="color:var(--success);font-size:10px;"></i>
                Fits:
                {{ $part->compatibleModels->take(2)->pluck('name')->join(', ') }}{{ $part->compatibleModels->count() > 2 ? ' +' . ($part->compatibleModels->count() - 2) . ' more' : '' }}
            </div>
        @endif
    </div>

    {{-- Footer — NO price. Quote CTA only, matching amsparts.com business model --}}
    <div class="part-card-footer">
        <a href="{{ route('quote.create') }}?part_number={{ urlencode($part->part_number ?? $part->name) }}&make_slug={{ $part->make?->slug }}&part_name={{ urlencode($part->name) }}&part_desc={{ urlencode($part->short_description ?? '') }}&condition={{ $part->condition_type ?? '' }}&oem={{ urlencode($part->oem_part_number ?? '') }}"
            class="btn btn-sm btn-outline-primary" style="font-size:12px;padding:6px 12px;">
            <i class="fa-solid fa-file-lines" style="font-size:10px;"></i> Get a Quote
        </a>
        <a href="{{ route('parts.show', $part->slug) }}" class="part-card-cta">
            View <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
</article>
