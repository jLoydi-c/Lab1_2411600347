@extends('layouts.app')
@section('title', 'Record Transaction')
@section('content')

    <h2 class="mb-3">Record Transaction</h2>

    <form action="{{ route('transactions.store') }}" method="POST" class="card p-4 bg-white">
        @csrf

        <div class="mb-3">
            <label class="form-label">Product</label>
            <select name="product_id" id="product_id" class="form-select">
                <option value="">-- Select a product --</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" data-stock="{{ $product->quantity }}"
                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->sku }} - {{ $product->name }}
                    </option>
                @endforeach
            </select>
            <div id="stockInfo" class="form-text">Select a product to see its current stock.</div>
            @error('product_id') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label d-block">Transaction Type</label>
            @foreach ($types as $value => $label)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="type" id="type_{{ $value }}"
                           value="{{ $value }}" {{ old('type', 'stock_in') == $value ? 'checked' : '' }}>
                    <label class="form-check-label" for="type_{{ $value }}">{{ $label }}</label>
                </div>
            @endforeach
            @error('type') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="{{ old('quantity') }}">
            @error('quantity') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Reference Number (PO / Sales Order)</label>
            <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number') }}">
            @error('reference_number') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            @error('notes') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div>
            <button type="submit" class="btn btn-login">Save Transaction</button>
            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>

@endsection

@push('scripts')
<script>
    // shows the current stock of the selected product
    // and limits the quantity when the type is stock out
    const productSelect = document.getElementById('product_id');
    const stockInfo = document.getElementById('stockInfo');
    const quantityInput = document.getElementById('quantity');
    const typeInputs = document.querySelectorAll('input[name="type"]');

    function updateStockInfo() {
        const option = productSelect.options[productSelect.selectedIndex];
        const checkedType = document.querySelector('input[name="type"]:checked');
        const type = checkedType ? checkedType.value : null;

        if (!option || !option.dataset.stock) {
            stockInfo.textContent = 'Select a product to see its current stock.';
            quantityInput.removeAttribute('max');
            return;
        }

        const stock = parseInt(option.dataset.stock);
        stockInfo.textContent = 'Current stock: ' + stock;

        if (type === 'stock_out') {
            quantityInput.max = stock;
        } else {
            quantityInput.removeAttribute('max');
        }
    }

    productSelect.addEventListener('change', updateStockInfo);
    typeInputs.forEach(function (radio) {
        radio.addEventListener('change', updateStockInfo);
    });
    updateStockInfo();
</script>
@endpush