@extends('layouts.app')

@section('content')

<div class="card mt-5 max-w-7xl" x-data="orderForm()">
    <h2 class="card-header">Create a new Order</h2>
    <div class="card-body">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a class="btn btn-primary btn-sm" href="{{ route('orders.index') }}">
            <i class="fa fa-arrow-left"></i>Back</a>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <!-- Invoice & Order Details -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="invoice_number" class="form-label"><strong>Invoice Number</strong></label>
                    <input type="text" name="invoice_number" id="invoice_number" class="form-control" value="{{ old('invoice_number', $nextInvoice) }}" readonly>
                </div>
                <div class="col-md-3">
                    <label for="invoice_date" class="form-label"><strong>Date & Time</strong></label>
                    <input type="datetime-local" name="invoice_date" id="invoice_date" class="form-control" value="{{ old('invoice_date', now()->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="col-md-3">
                    <label for="total" class="form-label"><strong>Total</strong></label>
                    <input type="text" name="total" id="total" class="form-control" x-bind:value="total.toFixed(2)" readonly placeholder="0.00">
                </div>
            </div>
            <!-- Client & Delivery -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="client_id" class="form-label"><strong>Client</strong></label>
                    <select name="client_id" id="client_id" class="form-control">
                        <option value="" disabled selected>Select a client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="delivery_address" class="form-label"><strong>Delivery Address</strong></label>
                    <textarea name="delivery_address" id="delivery_address" class="form-control" rows="2">{{ old('delivery_address') }}</textarea>
                </div>
            </div>
            <!-- Additional Notes -->
            <div class="mb-3">
                <label for="notes" class="form-label"><strong>Notes</strong></label>
                <textarea name="notes" id="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
            </div>
            <!-- Order items component -->
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
                Submit
            </button>
        </form>
    </div>
    </div>
<script>
    function orderForm() {
        return {
            items: [{ productId: '', quantity: 1 }],
            productsData: {
                @foreach($products as $product)
                    "{{ $product->id }}": {{ $product->price ?? 0 }},
                @endforeach
            },
            total: 0.00,
            init() {
                this.updateTotal();
                // Watch for changes in items to recalculate total
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
                    // Clear the fields of the last item
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
        }
    }
</script>
@endsection
