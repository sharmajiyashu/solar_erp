@extends('admin.layouts.app')

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Current Stock Report</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">Reports</li>
                                <li class="breadcrumb-item active">Current Stock</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1">
                    @can('reports export')
                    <a href="{{ route('admin.reports.stock.export', request()->all()) }}" class="btn btn-primary">
                        <i data-feather="download"></i> Export CSV
                    </a>
                    @endcan
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Filter Section -->
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.reports.stock') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="search">Search</label>
                                    <input type="text" id="search" name="search" class="form-control" placeholder="Product, Company" value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4 col-12 d-flex align-items-end">
                                <div class="mb-1">
                                    <button type="submit" class="btn btn-outline-primary me-1">Filter</button>
                                    <a href="{{ route('admin.reports.stock') }}" class="btn btn-outline-secondary">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Section -->
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Product Subtype</th>
                                <th>Company</th>
                                <th>Current Stock</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td>
                                    <span class="badge badge-light-primary">{{ $product->category->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $product->subtype }}</span>
                                </td>
                                <td>
                                    {{ $product->company }}
                                </td>
                                <td>
                                    @if($product->stock <= 5)
                                        <span class="badge rounded-pill badge-light-danger">{{ $product->stock ?? 0 }}</span>
                                    @elseif($product->stock <= 15)
                                        <span class="badge rounded-pill badge-light-warning">{{ $product->stock ?? 0 }}</span>
                                    @else
                                        <span class="badge rounded-pill badge-light-success">{{ $product->stock ?? 0 }}</span>
                                    @endif
                                </td>
                                <td>{{ $product->updated_at->format('d M Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No products found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    @include('admin._pagination', ['data' => $products])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
