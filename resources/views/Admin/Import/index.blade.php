@extends('layouts.admin')

@section('title', 'Import Records')

@section('content')
    <div class="page-header-premium">
        <div class="header-left">
            <div class="header-icon-box" style="background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);">
                <i class="fas fa-file-import"></i>
            </div>
            <div class="header-text">
                <h1 class="page-title">Import Records</h1>
                <p class="page-subtitle">Import products from Excel/CSV files</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.import.template') }}" class="btn-header-action btn-header-secondary">
                <i class="fas fa-download"></i>
                <span>Download Template</span>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Import Products</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Import products from Excel or CSV file. 
                        <a href="{{ route('admin.import.template') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </p>

                    <form action="{{ route('admin.import.products') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label>Choose File</label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" 
                                   accept=".xlsx,.xls,.csv" required>
                            @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">Max file size: 10MB. Allowed formats: .xlsx, .xls, .csv</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Import Products
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Import Instructions</h5>
                </div>
                <div class="card-body">
                    <h6>Required Columns:</h6>
                    <ul>
                        <li><strong>product_code</strong> - Unique product code</li>
                        <li><strong>product_name</strong> - Product name</li>
                        <li><strong>category_name</strong> - Category name (will be created if not exists)</li>
                        <li><strong>cost_price</strong> - Purchase price</li>
                        <li><strong>selling_price</strong> - Retail price</li>
                        <li><strong>stock_quantity</strong> - Initial stock quantity</li>
                    </ul>

                    <h6>Optional Columns:</h6>
                    <ul>
                        <li>barcode, description, supplier_name, brand, unit</li>
                        <li>wholesale_price, tax_rate, reorder_level, status</li>
                    </ul>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Categories and Suppliers will be automatically created if they don't exist.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
