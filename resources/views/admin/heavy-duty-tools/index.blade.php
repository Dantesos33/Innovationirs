@extends('layouts.admin')

@section('title', 'Heavy Duty Tools')

@section('breadcrumb')
    <span class="breadcrumb-current">Heavy Duty Tools</span>
@endsection

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Heavy Duty Tools</h1>
            <p class="page-subtitle">{{ number_format($tools->total()) }} total tools in catalog</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('admin.heavy-duty-tools.export') }}" class="btn btn--secondary">
                <i class="fa-solid fa-download"></i> Export CSV
            </a>
            <a href="{{ route('admin.heavy-duty-tools.create') }}" class="btn btn--primary">
                <i class="fa-solid fa-plus"></i> Add Tool
            </a>
        </div>
    </div>

    {{-- Bulk action bar --}}
    <div class="bulk-bar" id="bulkBar">
        <span>Selected: <strong class="bulk-count" id="bulkCount">0</strong></span>
        <button class="btn btn--secondary btn--sm" data-bulk-action="activate">Activate</button>
        <button class="btn btn--secondary btn--sm" data-bulk-action="deactivate">Deactivate</button>
        <button class="btn btn--danger btn--sm" data-bulk-action="delete">Delete</button>
        <form id="bulkForm" method="POST" action="{{ route('admin.heavy-duty-tools.bulk') }}">
            @csrf
            <input type="hidden" name="action" value="">
            <input type="hidden" name="ids" value="">
        </form>
    </div>

    <div class="card">

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.heavy-duty-tools.index') }}">
            <div class="filters-bar">
                <div class="filter-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search name, SKU, brand…">
                </div>
                <select name="brand" class="filter-select">
                    <option value="">All Brands</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand }}" {{ request('brand') === $brand ? 'selected' : '' }}>
                            {{ $brand }}
                        </option>
                    @endforeach
                </select>
                <select name="stock_status" class="filter-select">
                    <option value="">All Stock</option>
                    <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>In Stock
                    </option>
                    <option value="out_of_stock" {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Out of
                        Stock</option>
                    <option value="on_order" {{ request('stock_status') === 'on_order' ? 'selected' : '' }}>On Order
                    </option>
                </select>
                <select name="status" class="filter-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                <div class="filter-actions">
                    @if (request()->hasAny(['search', 'brand', 'stock_status', 'status']))
                        <a href="{{ route('admin.heavy-duty-tools.index') }}" class="btn btn--ghost btn--sm">
                            <i class="fa-solid fa-xmark"></i> Clear
                        </a>
                    @endif
                    <button type="submit" class="btn btn--secondary btn--sm">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="col-check">
                            <input type="checkbox" id="selectAll" class="check-all">
                        </th>
                        <th class="col-img"></th>
                        <th>Tool Name</th>
                        <th>SKU / Part #</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tools as $tool)
                        <tr>
                            <td class="col-check">
                                <input type="checkbox" class="row-check" value="{{ $tool->id }}">
                            </td>
                            <td class="col-img">
                                @if ($tool->primaryImage)
                                    <img src="{{ $tool->primaryImage->public_url }}" alt="{{ $tool->name }}"
                                        class="table-thumb">
                                @else
                                    <div class="table-thumb table-thumb--empty">
                                        <i class="fa-solid fa-hammer"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $tool->name }}</strong>
                                @if ($tool->is_featured)
                                    <span class="badge badge-success"
                                        style="margin-left:6px;font-size:10px;">Featured</span>
                                @endif
                                @if ($tool->short_description)
                                    <br><span class="table-meta">{{ Str::limit($tool->short_description, 70) }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($tool->sku)
                                    <span class="table-code">{{ $tool->sku }}</span>
                                @endif
                                @if ($tool->part_number)
                                    <br><span class="table-meta">{{ $tool->part_number }}</span>
                                @endif
                            </td>
                            <td>{{ $tool->brand ?? '—' }}</td>
                            <td>
                                @if ($tool->is_on_sale)
                                    <span style="color:var(--color-text-danger);font-weight:500;">
                                        ${{ number_format($tool->sale_price, 2) }}
                                    </span>
                                    <br><span class="table-meta" style="text-decoration:line-through;">
                                        ${{ number_format($tool->price, 2) }}
                                    </span>
                                @else
                                    <span style="font-weight:500;">${{ number_format($tool->price, 2) }}</span>
                                @endif
                            </td>
                            <td>
                                <span
                                    class="badge {{ $tool->stock_status === 'in_stock' ? 'badge-success' : ($tool->stock_status === 'out_of_stock' ? 'badge-danger' : 'badge-open') }}">
                                    {{ $tool->stock_status_label }}
                                </span>
                                <br><span class="table-meta">Qty: {{ $tool->stock_quantity }}</span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.heavy-duty-tools.toggle', $tool) }}"
                                    style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="badge badge-{{ $tool->status === 'active' ? 'success' : 'secondary' }} btn-status-toggle"
                                        title="Click to toggle">
                                        {{ ucfirst($tool->status) }}
                                    </button>
                                </form>
                            </td>
                            <td class="col-actions">
                                <div class="action-btns">
                                    <a href="{{ route('admin.heavy-duty-tools.edit', $tool) }}"
                                        class="btn btn--ghost btn--sm" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.heavy-duty-tools.destroy', $tool) }}"
                                        onsubmit="return confirm('Delete \'{{ addslashes($tool->name) }}\'? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn--ghost btn--sm btn--danger-hover"
                                            title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="empty-state">
                                <i class="fa-solid fa-hammer" style="font-size:2rem;opacity:.3;"></i>
                                <p>No tools found.
                                    <a href="{{ route('admin.heavy-duty-tools.create') }}">Add the first one</a>.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tools->hasPages())
            <div class="table-footer">
                {{ $tools->links('vendor.pagination.simple-admin') }}
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        // Select all / bulk
        const selectAll = document.getElementById('selectAll');
        const bulkBar = document.getElementById('bulkBar');
        const bulkCount = document.getElementById('bulkCount');

        function updateBulkBar() {
            const checked = document.querySelectorAll('.row-check:checked');
            bulkCount.textContent = checked.length;
            bulkBar.classList.toggle('active', checked.length > 0);
        }

        selectAll?.addEventListener('change', () => {
            document.querySelectorAll('.row-check').forEach(cb => cb.checked = selectAll.checked);
            updateBulkBar();
        });

        document.querySelectorAll('.row-check').forEach(cb =>
            cb.addEventListener('change', updateBulkBar)
        );

        document.querySelectorAll('[data-bulk-action]').forEach(btn => {
            btn.addEventListener('click', () => {
                const ids = [...document.querySelectorAll('.row-check:checked')].map(c => c.value);
                if (!ids.length) return;
                const action = btn.dataset.bulkAction;
                if (action === 'delete' && !confirm(`Delete ${ids.length} tool(s)?`)) return;
                const form = document.getElementById('bulkForm');
                form.querySelector('[name=action]').value = action;
                form.querySelector('[name=ids]').value = ids.join(',');
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: new URLSearchParams({
                        action,
                        ids: ids.join(','),
                        _token: '{{ csrf_token() }}'
                    }),
                }).then(() => location.reload());
            });
        });
    </script>
@endpush
