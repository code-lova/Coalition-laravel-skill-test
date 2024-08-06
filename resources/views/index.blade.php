<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Coalition Tech - Laravel skill test</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
       
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div class="container mt-5">
            <h1 class="mb-4">Product Management Test</h1>
        
            
            <form id="productForm">
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity in Stock</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                </div>
                <div class="form-group">
                    <label for="price">Price per Item</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                </div>
                <button type="submit" class="btn btn-primary">Create Product</button>
            </form>

            <div></div>

            <!-- Table -->
            <div class="mt-5">
                <h2>Submitted Products</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity in Stock</th>
                            <th>Price per Item</th>
                            <th>Datetime Submitted</th>
                            <th>Total Value</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                     <!-- Data will be populated here by JavaScript -->
                    <tbody id="productList">
                    </tbody>
                </table>
            </div>

        </div>


        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

        <script>
            $(document).ready(function() {
                let editingProductId = null; // Help to track the product being edited
            
                // Set CSRF token in the AJAX request headers
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            
                // Submit form via AJAX
                $('#productForm').submit(function(e) {
                    e.preventDefault();
            
                    let url = editingProductId ? `/products/${editingProductId}` : '/products';
                    let type = editingProductId ? 'PUT' : 'POST';
            
                    $.ajax({
                        url: url,
                        type: type,
                        data: $(this).serialize(),
                        success: function(response) {
                            // Clear the form and reset editing state
                            $('#productForm')[0].reset();
                            editingProductId = null;
                            // Update the product list
                            loadProductList(response);
                        }
                    });
                });
            
                // Function to load product list
                function loadProductList(products) {
                    $('#productList').empty(); // Clear the table body
                    let totalValue = 0;
                    products.forEach(product => {
                        totalValue += product.total_value;
                        $('#productList').append(`
                            <tr>
                                <td>${product.name}</td>
                                <td>${product.quantity}</td>
                                <td>${product.price}</td>
                                <td>${product.datetime_submitted}</td>
                                <td>${product.total_value}</td>
                                <td>
                                    <button class="btn btn-primary editBtn" data-id="${product.id}">Edit</button>
                                </td>
                            </tr>
                        `);
                    });
                    // Append total value row
                    $('#productList').append(`
                        <tr>
                            <td colspan="4"><strong>Total</strong></td>
                            <td><strong>${totalValue}</strong></td>
                            <td></td>
                        </tr>
                    `);
            
                    // Attach click event to edit buttons needed more time...to debug
                    $('.editBtn').click(function() {
                        const productId = $(this).data('id');
                        const product = products.find(p => p.id === productId);
                        if (product) {
                            // Set the form values to the selected product's values
                            editingProductId = productId;
                            $('#name').val(product.name);
                            $('#quantity').val(product.quantity);
                            $('#price').val(product.price);
                        }
                    });
                }
            
                // Load products on page load
                $.get('/products', function(response) {
                    loadProductList(response);
                });
            });
        </script>
            
            
    </body>
</html>
