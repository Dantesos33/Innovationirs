{{-- resources/views/partials/breadcrumb.blade.php --}}
{{--
Usage:
@include('partials.breadcrumb', ['crumbs' => [
    ['label' => 'Parts',           'url' => route('parts.index')],
    ['label' => 'Hydraulic Pumps', 'url' => route('categories.show', 'hydraulic-pumps')],
    ['label' => 'CAT 320D Pump',   'url' => null],
]])
--}}

@php $crumbs = $crumbs ?? []; @endphp

@if (count($crumbs))
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ url('/') }}">
            <i class="fa-solid fa-house" style="font-size:11px;"></i>
            <span class="sr-only">Home</span>
        </a>
        <span class="breadcrumb-sep" aria-hidden="true"><i class="fa-solid fa-chevron-right"></i></span>

        @foreach ($crumbs as $i => $crumb)
            @if ($i === count($crumbs) - 1 || is_null($crumb['url'] ?? null))
                {{-- Last item = current page --}}
                <span class="breadcrumb-current" aria-current="page">
                    {{ $crumb['label'] }}
                </span>
            @else
                <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                <span class="breadcrumb-sep" aria-hidden="true"><i class="fa-solid fa-chevron-right"></i></span>
            @endif
        @endforeach
    </nav>
@endif
