@extends('layouts.app')
@section('title', 'Add Product')
@section('content')

    <h2 class="mb-3">Add New Product</h2>
    <form action="{{ route('products.store') }}" method="POST" class="card p-4 bg-white">
        @csrf

        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
            @error('name')
            <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">SKU</label>
            <input type="text" name="sku" class="form-control" value="{{ old('sku') }}">
            @error('sku')
            <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select">
                <option value="Lumber" {{ old('category') == 'Lumber' ? 'selected' : '' }}>Lumber</option>
                <option value="Hardware" {{ old('category') == 'Hardware' ? 'selected' : '' }}>Hardware</option>
                <option value="Tools" {{ old('category') == 'Tools' ? 'selected' : '' }}>Tools</option>
                <option value="Electrical" {{ old('category') == 'Electrical' ? 'selected' : '' }}>Electrical</option>
                <option value="Plumbing" {{ old('category') == 'Plumbing' ? 'selected' : '' }}>Plumbing</option>
                <option value="Paint" {{ old('category') == 'Paint' ? 'selected' : '' }}>Paint</option>
            </select>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 0) }}">
                @error('quantity')
                <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Reorder Level</label>
                <input type="number" name="reorder_level" class="form-control" value="{{ old('reorder_level', 10) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Unit Price</label>
                <input type="number" step="0.01" name="unit_price" class="form-control" value="{{ old('unit_price') }}">
                @error('unit_price')
                <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Supplier</label>
            <input type="text" name="supplier" class="form-control" value="{{ old('supplier') }}">
        </div>

        <button type="submit" class="btn btn-login">Save Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>

@endsection