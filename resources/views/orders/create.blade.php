@extends('orders.layout')

@section('content')

<div class="card mt-5">
    <h2 class="card-header">Create a new Order</h2>
    <div class="card-body">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a class="btn btn-primary btn-sm" href="{{ route('orders.index') }}">
            <i class="fa fa-arrow-left"></i>Back</a>
        </div>

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
                    <label for="status" class="form-label"><strong>Status</strong></label>
                    <input type="text" name="status" id="status" class="form-control" value="{{ old('status', 'pending') }}">
                </div>
                <div class="col-md-3">
                    <label for="total" class="form-label"><strong>Total</strong></label>
                    <input type="text" name="total" id="total" class="form-control" readonly placeholder="0.00">
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
            <!-- Client Info Preview -->
            <div id="client_info" class="mb-3">
                <div><strong>Client Name/Company:</strong> <span id="client_name_display"></span></div>
                <div><strong>Client Number:</strong> <span id="client_number_display"></span></div>
                <div><strong>Tax Information:</strong> <span id="tax_info_display"></span></div>
            </div>
            <!-- Additional Notes -->
            <div class="mb-3">
                <label for="notes" class="form-label"><strong>Notes</strong></label>
                <textarea name="notes" id="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
            </div>
            <!-- Order items component -->
            <div class="mb-3">
                <h5>Order Items</h5>
                <table class="table table-bordered" id="items_table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>
                                <button type="button" class="btn btn-sm btn-primary" id="add_item">
                                    <i class="fa fa-plus"></i> Add Item
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="selector-">
                                <select name="product_id[]" class="form-control product-select" style="width:100%;">
                                    <option></option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="quantity[]" class="form-control" min="1" value="1"></td>
                            <td><button type="button" class="btn btn-sm btn-danger remove-item"><i class="fa fa-trash"></i></button></td>
                        </tr>
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
    var productsData = {
        @foreach($products as $product)
            "{{ $product->id }}": {{ $product->price ?? 0 }},
        @endforeach
    };

    $(document).ready(function(){
        function updateTotal() {
            var total = 0;
            $('#items_table tbody tr').each(function() {
                var $row = $(this);
                var productId = $row.find('.product-select').val();
                var quantity = parseInt($row.find('input[name="quantity[]"]').val(), 10);
                var price = 0;

                if (productId && productsData.hasOwnProperty(productId)) {
                    price = parseFloat(productsData[productId]);
                }

                if (!isNaN(price) && !isNaN(quantity) && quantity > 0) {
                    total += price * quantity;
                }
            });
            $('#Inputtotal').val(total.toFixed(2));
        }

        $('#add_item').click(function(){
            var row = $('#items_table tbody tr:first').clone();
            row.find('select.product-select').val(''); // Reset product selection
            row.find('input[name="quantity[]"]').val(1); // Reset quantity to 1
            $('#items_table tbody').append(row);
            updateTotal(); // Update total after adding a new row
        });

        $(document).on('click', '.remove-item', function(){
            if ($('#items_table tbody tr').length > 1) {
                $(this).closest('tr').remove();
                updateTotal(); // Update total after removing a row
            } else {
                // Optionally clear the fields of the last row instead of removing it
                $(this).closest('tr').find('.product-select').val('');
                $(this).closest('tr').find('input[name="quantity[]"]').val(1);
                updateTotal();
            }
        });

        // Update total when product or quantity changes
        $(document).on('change', '.product-select', updateTotal);
        $(document).on('input change', 'input[name="quantity[]"]', updateTotal); // 'input' for immediate update, 'change' for fallback

        // Initial calculation on page load
        updateTotal();
    });
</script>
@endsection
