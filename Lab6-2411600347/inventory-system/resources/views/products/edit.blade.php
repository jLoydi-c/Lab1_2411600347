@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')

    <h2 class="mb-3">Edit Product</h2>

    <form action="{{ route('products.update', $product) }}" method="POST" class="card p-4 bg-white">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}">
            @error('name')
            <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">SKU</label>
            <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
            @error('sku')
            <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select">
                @foreach (['Lumber', 'Hardware', 'Tools', 'Electrical', 'Plumbing', 'Paint'] as $cat)
                    <option value="{{ $cat }}" {{ old('category', $product->category) == $cat ? 'selected' : '' }}>{{ $cat }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', $product->quantity) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Reorder Level</label>
                <input type="number" name="reorder_level" class="form-control"
                    value="{{ old('reorder_level', $product->reorder_level) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Unit Price</label>
                <input type="number" step="0.01" name="unit_price" class="form-control"
                    value="{{ old('unit_price', $product->unit_price) }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Supplier</label>
            <input type="text" name="supplier" class="form-control" value="{{ old('supplier', $product->supplier) }}">
        </div>

        <button type="submit" class="btn btn-login">Update Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>

@endsection