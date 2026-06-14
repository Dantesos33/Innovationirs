{{-- resources/views/partials/tool-card-styles.blade.php --}}
{{-- Include on any page that renders tool-card partials --}}
@push('styles')
    <style>
        .tool-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform .2s, box-shadow .2s;
        }

        .tool-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .1);
        }

        .tool-card-img {
            display: block;
            aspect-ratio: 4/3;
            overflow: hidden;
            background: var(--gray-100);
            position: relative;
        }

        .tool-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .3s;
        }

        .tool-card:hover .tool-card-img img {
            transform: scale(1.04);
        }

        .tool-card-img-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-300);
            font-size: 2.5rem;
        }

        .tool-card-badges {
            position: absolute;
            top: 10px;
            left: 10px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .tool-badge {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            padding: 3px 8px;
            border-radius: 4px;
        }

        .tool-badge--sale {
            background: #ef4444;
            color: #fff;
        }

        .tool-badge--featured {
            background: #f59e0b;
            color: #fff;
        }

        .tool-badge--oos {
            background: var(--gray-600);
            color: #fff;
        }

        .tool-card-body {
            padding: 16px 16px 0;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .tool-card-brand {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--orange);
        }

        .tool-card-name {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.4;
        }

        .tool-card-name a {
            color: var(--gray-900);
            text-decoration: none;
        }

        .tool-card-name a:hover {
            color: var(--orange);
        }

        .tool-card-meta {
            font-size: 11px;
            color: var(--gray-400);
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tool-card-desc {
            font-size: 12px;
            color: var(--gray-500);
            line-height: 1.5;
            margin: 0;
        }

        .tool-card-footer {
            padding: 12px 16px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
        }

        .tool-card-price {
            display: flex;
            align-items: baseline;
            gap: 6px;
        }

        .tool-price {
            font-size: 17px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .tool-price-sale {
            font-size: 17px;
            font-weight: 700;
            color: #ef4444;
        }

        .tool-price-original {
            font-size: 13px;
            color: var(--gray-400);
            text-decoration: line-through;
        }

        .tool-card-cta {
            font-size: 12px;
            font-weight: 600;
            color: var(--orange);
            text-decoration: none;
            white-space: nowrap;
        }

        .tool-card-cta:hover {
            text-decoration: underline;
        }

        .tool-card-cta i {
            font-size: 10px;
        }
    </style>
@endpush
