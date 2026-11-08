@extends('layouts.app')
@section('title', 'Stock Adjustment')
@section('content')

    <h2 class="mb-3">Stock Adjustment</h2>

    <form action="{{ route('adjustments.store') }}" method="POST" class="card p-4 bg-white">
        @csrf

        <div class="mb-3">
            <label class="form-label">Product</label>
            <select name="product_id" id="product_id" class="form-select">
                <option value="">-- Select a product --</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->sku }} - {{ $product->name }}
                    </option>
                @endforeach
            </select>
            @error('product_id') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Current System Quantity</label>
                <input type="text" id="systemQty" class="form-control" value="-" readonly>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Physical Count</label>
                <input type="number" name="physical_count" id="physicalCount" class="form-control" min="0" value="{{ old('physical_count') }}">
                <div id="differenceInfo" class="form-text"></div>
                @error('physical_count') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Reason</label>
            <select name="reason" class="form-select">
                @foreach ($reasons as $value => $label)
                    <option value="{{ $value }}" {{ old('reason') == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('reason') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
        </div>

        <div>
            <button type="submit" class="btn btn-login">Save Adjustment</button>
            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>

@endsection

@push('scripts')
<script>
    const productSelect = document.getElementById('product_id');
    const systemQty = document.getElementById('systemQty');
    const physicalCount = document.getElementById('physicalCount');
    const differenceInfo = document.getElementById('differenceInfo');
    let currentQuantity = null;

    // shows the difference between the physical count and the system count
    function updateDifference() {
        if (currentQuantity === null || physicalCount.value === '') {
            differenceInfo.textContent = '';
            return;
        }

        const difference = parseInt(physicalCount.value) - currentQuantity;
        const sign = difference > 0 ? '+' : '';
        differenceInfo.textContent = 'Adjustment: ' + sign + difference;
    }

    // loads the current quantity from the api when a product is picked (AJAX)
    async function loadProductQuantity() {
        const id = productSelect.value;

        if (!id) {
            currentQuantity = null;
            systemQty.value = '-';
            updateDifference();
            return;
        }

        try {
            const response = await fetch("{{ url('/api/products') }}/" + id, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            });
            const data = await response.json();
            currentQuantity = data.quantity;
            systemQty.value = data.quantity;
        } catch (error) {
            currentQuantity = null;
            systemQty.value = 'Could not load';
        }

        updateDifference();
    }

    productSelect.addEventListener('change', loadProductQuantity);
    physicalCount.addEventListener('input', updateDifference);
    loadProductQuantity();
</script>
@endpush