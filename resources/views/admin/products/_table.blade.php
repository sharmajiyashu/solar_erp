<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead style="background-color: #f8f9fa;">
            <tr>
                <th class="ps-2 py-1" style="width: 50px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">#</th>
                <th class="py-1" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Category</th>
                <th class="py-1" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Details</th>
                <th class="py-1 text-end" style="text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Pricing (₹)</th>
                <th class="py-1 text-center" style="width: 100px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">3KW DCR</th>
                <th class="py-1 text-center" style="width: 100px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Stock</th>
                <th class="py-1 text-center" style="width: 100px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Status</th>
                <th class="py-1 text-center pe-2" style="width: 150px; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5e5873;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $key => $product)
                <tr style="transition: all 0.2s ease;">
                    <td class="ps-2 py-1 align-middle text-muted small fw-bold">
                        {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                    </td>
                    <td class="py-1 align-middle">
                        <span class="badge rounded-pill" style="background-color: rgba(113, 187, 178, 0.12); color: #71bbb2; font-weight: 600;">
                            {{ $product->category->name ?? 'Uncategorized' }}
                        </span>
                    </td>
                    <td class="py-1 align-middle">
                        <div class="d-flex flex-column">
                            <span class="fw-bolder" style="color: #444; font-size: 0.95rem;">{{ $product->subtype ?? 'N/A' }}</span>
                            <small class="text-muted">{{ $product->company ?? 'No Company' }}</small>
                        </div>
                    </td>
                    <td class="py-1 align-middle text-end">
                        <div class="d-flex flex-column align-items-end">
                            <span class="fw-bolder text-dark" style="font-size: 1rem;">₹{{ number_format($product->final_landing_with_gst, 2) }}</span>
                            <div style="font-size: 0.75rem;">
                                <span class="text-muted">Base: ₹{{ number_format($product->total_landing_wo_gst, 2) }}</span>
                                <span class="text-success ms-50">+{{ $product->gst_percentage }}% GST</span>
                            </div>
                        </div>
                    </td>
                    <td class="py-1 align-middle text-center">
                        <span class="fw-bold text-secondary">{{ $product->three_kw_dcr_qnt ?? '-' }}</span>
                    </td>
                    <td class="py-1 align-middle text-center">
                        <span class="badge badge-light-info current-stock-display-{{ $product->id }}" style="font-size: 0.9rem;">{{ $product->stock }}</span>
                    </td>
                    <td class="py-1 align-middle text-center">
                        <div class="form-check form-switch d-flex justify-content-center">
                            <input type="checkbox" class="form-check-input status-toggle" 
                                data-id="{{ $product->id }}" {{ $product->status ? 'checked' : '' }} role="switch" style="cursor: pointer;">
                        </div>
                    </td>
                    <td class="py-1 align-middle text-center pe-2">
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-icon btn-flat-success btn-sm manage-stock me-25" data-id="{{ $product->id }}" data-name="{{ $product->subtype }} ({{ $product->company }})" title="Manage Stock">
                                <i data-feather="plus-circle" style="width: 14px; height: 14px;"></i>
                            </button>
                            <button class="btn btn-icon btn-flat-info btn-sm view-history me-25" data-id="{{ $product->id }}" title="Stock History">
                                <i data-feather="list" style="width: 14px; height: 14px;"></i>
                            </button>
                            <button class="btn btn-icon btn-flat-primary btn-sm edit-product me-25" data-id="{{ $product->id }}" title="Edit">
                                <i data-feather="edit" style="width: 14px; height: 14px;"></i>
                            </button>
                            <button class="btn btn-icon btn-flat-danger btn-sm delete-product" data-id="{{ $product->id }}" title="Delete">
                                <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center">
                            <i data-feather="box" class="text-muted mb-1" style="width: 48px; height: 48px; opacity: 0.5;"></i>
                            <p class="text-muted font-medium-2">Your inventory is currently empty.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="px-2 py-1 border-top d-flex justify-content-between align-items-center">
    <div class="text-muted small">
        Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} entries
    </div>
    <div id="ajax-pagination">
        @include('admin._pagination', ['data' => $products])
    </div>
</div>

<script>
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
