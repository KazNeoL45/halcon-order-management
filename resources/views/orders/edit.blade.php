@extends('layouts.app')

@section('content')

<div class="card mt-5 max-w-7xl" x-data="orderForm()">
    <h2 class="card-header">Edit Order: {{ $order->invoice_number }}</h2>
    <div class="card-body">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
            <a class="btn btn-primary btn-sm" href="{{ route('orders.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
<form action="{{ route('orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="d-flex flex-wrap mb-4">
                <div class="flex-fill pe-3">
                    <h5>Purchase Details</h5>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="status" class="form-label"><strong>Status</strong></label>
                            <select name="status" id="status" class="form-control">
                                @foreach(['pending','paid','shipped','delivered','cancelled'] as $statusOption)
                                    <option value="{{ $statusOption }}" {{ old('status', $order->status) == $statusOption ? 'selected' : '' }}>
                                        {{ ucfirst($statusOption) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="invoice_number" class="form-label"><strong>Invoice Number</strong></label>
                            <input type="text" name="invoice_number" id="invoice_number" class="form-control" value="{{ old('invoice_number', $order->invoice_number) }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="invoice_date" class="form-label"><strong>Date & Time</strong></label>
                            <input type="datetime-local" name="invoice_date" id="invoice_date" class="form-control"
                                value="{{ old('invoice_date', $order->invoice_date) }}">
                        </div>
                        <div class="col-md-3">
                            <label for="total" class="form-label"><strong>Total</strong></label>
                            <input type="text" name="total" id="total" class="form-control" x-bind:value="total.toFixed(2)" readonly placeholder="0.00">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="client_id" class="form-label"><strong>Client</strong></label>
                            <select name="client_id" id="client_id" class="form-control">
                                <option value="" disabled>Select a client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id', $order->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label"><strong>Notes</strong></label>
                        <textarea name="notes" id="notes" class="form-control" rows="2">{{ old('notes', $order->notes) }}</textarea>
                    </div>
                </div>
                <div class="flex-fill pt-3">
                    <h5>Address Information</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="street" class="form-label"><strong>Street</strong></label>
                            <input type="text" name="street" id="street" class="form-control" value="{{ old('street', $order->address->street) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="external_number" class="form-label"><strong>External Number</strong></label>
                            <input type="text" name="external_number" id="external_number" class="form-control" value="{{ old('external_number', $order->address->external_number) }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="colony" class="form-label"><strong>Colony</strong></label>
                            <input type="text" name="colony" id="colony" class="form-control" value="{{ old('colony', $order->address->colony) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="city" class="form-label"><strong>City</strong></label>
                            <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $order->address->city) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="state_id" class="form-label"><strong>State</strong></label>
                            <select name="state_id" id="state_id" class="form-control">
                                <option value="" disabled>Select a state</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ old('state_id', $order->address->state_id) == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="zip_code" class="form-label"><strong>Zip Code</strong></label>
                            <input type="text" name="zip_code" id="zip_code" class="form-control" value="{{ old('zip_code', $order->address->zip_code) }}">
                        </div>
                        <div class="col-md-4">
                            <label for="country_id" class="form-label"><strong>Country</strong></label>
                            <select name="country_id" id="country_id" class="form-control">
                                <option value="" disabled>Select a country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id', $order->address->country_id) == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <h5>Order Items</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>
                                <button type="button" class="btn btn-sm btn-primary" @click="addItem">
                                    <i class="fa fa-plus"></i> Add Item
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr>
                                <td>
                                    <select :name="'product_id[' + index + ']'" class="form-control product-select" x-model="item.productId" @change="updateTotal" style="width:100%;">
                                        <option value="" disabled>Select a product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" :name="'quantity[' + index + ']'" class="form-control" min="1" x-model.number="item.quantity" @input="updateTotal">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger" @click="removeItem(index)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fa-solid fa-floppy-disk"></i>
                Update
            </button>
        </form>
    </div>
</div>
<script>
    function orderForm() {
        return {
            items: @json($order->items->map(function($item) { return ['productId' => $item->product_id, 'quantity' => $item->quantity]; })),
            productsData: {
                @foreach($products as $product)
                    "{{ $product->id }}": {{ $product->price ?? 0 }},
                @endforeach
            },
            total: 0.00,
            init() {
                this.updateTotal();
                this.$watch('items', () => {
                    this.updateTotal();
                }, { deep: true });
            },
            addItem() {
                this.items.push({ productId: '', quantity: 1 });
            },
            removeItem(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                } else {
                    this.items[index].productId = '';
                    this.items[index].quantity = 1;
                }
            },
            updateTotal() {
                let currentTotal = 0;
                this.items.forEach(item => {
                    const price = parseFloat(this.productsData[item.productId] || 0);
                    const quantity = parseInt(item.quantity, 10);
                    if (!isNaN(price) && !isNaN(quantity) && quantity > 0) {
                        currentTotal += price * quantity;
                    }
                });
                this.total = currentTotal;
            }
        };
    }
</script>
@endsection
