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
            <div class="mb-3 d-flex gap-3">
              <div class="d-flex flex-column flex-grow-1">
                <label for="client_id" class="form-label"><strong>Client:</strong></label>
                <select name="client_id" id="client_id" class="form-control">
                  <option value="" disabled selected>Select a client</option>
                  @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="d-flex flex-column flex-grow-1">
                <label for="inputName" class="form-label"><strong>Total:</strong></label>
                <input
                    type="text"
                    name="total"
                    class="form-control"
                    id="Inputtotal"
                    placeholder="Total">
              </div>
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
    // Assumes $products is available and each product has 'id' and 'price'
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
