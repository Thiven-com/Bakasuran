<?php $page = 'pos'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper pos-pg-wrapper">
        <div class="content pos-design p-0">
            <div class="row align-items-start pos-wrapper">
                <div class="col-md-12 col-lg-7">
                    <div class="pos-categories tabs_wrapper">
                        <div class="pos-products">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-3">Products</h5>
                            </div>
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <div class="mb-3">
                                <input type="text" id="sku_search" class="form-control"
                                    placeholder="Enter SKU and press Enter">
                            </div>
                            <div class="tabs_container">
                                <div class="tab_content active" data-tab="all">
                                    <div class="row">
                                        @foreach ($products as $product)
                                            <div class="col-sm-2 col-md-6 col-lg-3 col-xl-3">
                                                <a onclick="addtoCart(this.id)" id="{{ $product['sku'] }}">
                                                    <div class="product-info default-cover card">
                                                        <img src="{{ asset($product['image']) }}" alt="Products" class="img-bg">
                                                        <span><i data-feather="check" class="feather-16"></i></span>
                                                        <h6 class="product-name">
                                                            <a
                                                                href="javascript:void(0);">{{ mb_strimwidth($product['name'], 0, 25, '..') }}</a>
                                                        </h6>

                                                        <div class="d-flex align-items-center justify-content-between price">
                                                            <span>{{ $product['stock'] ?? '' }} Pcs</span>
                                                            <p>₹{{ $product['sale_price'] ?? '' }}</p>
                                                        </div>
                                                        <p>
                                                            {{ $product['sku'] }}
                                                        </p>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-lg-5 ps-0">
                    <aside class="product-order-list">
                        <div class="head d-flex align-items-center justify-content-between w-100">
                            <div>
                                <h5>Order List</h5>
                            </div>
                            <div>
                            </div>
                        </div>
                        <form action="{{ route('admin.manualCheckout') }}" method="POST" id="payment_form">
                            @csrf
                            <div class="customer-info block-section">
                                <h6>Customer Information</h6>
                                <div class="input-block d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <select class="form-select" name="customer_id" id="customerSelect" required>
                                            <option value="">Add New Customer</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">
                                                    {{ $customer->name }} - {{ $customer->mobile }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <!-- ADD ID -->
                                        <button id="addCustomerBtn" class="btn btn-primary btn-sm mt-3" type="button"
                                            data-bs-toggle="modal" data-bs-target="#addCustomerModal"
                                            style="display: none;">
                                            + New Customer
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="customer-address block-section" id="customerAddressSection">
                                <h6>Customer Address</h6>
                                <div class="input-block d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <select class="form-select" name="customer_address" id="customerAddress">
                                            <option value="">Select Address</option>
                                        </select>

                                        <!-- ADD ID -->
                                        <button id="addAddressBtn" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            type="button" data-bs-target="#addAddressModal" style="display: none;">
                                            + Address
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="product-added block-section">
                                <div class="head-text d-flex align-items-center justify-content-between">
                                    <h6 class="d-flex align-items-center mb-0">Product Added<span
                                            class="count">({{ count($carts) }})</span></h6>
                                    <a href="{{ route('admin.clearSession') }}"
                                        class="d-flex align-items-center text-danger">
                                        <span class="me-1"><i data-feather="x" class="feather-16"></i></span>Clear all
                                    </a>
                                </div>
                                <div class="product-wrap" id="cart-table-body">
                                    @foreach ($carts as $cart)
                                        <div class="product-list d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center product-info" data-sku="{{ $cart['sku'] }}">
                                                <a href="javascript:void(0);" class="img-bg">
                                                    <img src="{{ asset($cart['image']) }}" alt="Products" style="width: 80px;">
                                                </a>
                                                <div class="info">
                                                    <h6><a
                                                            href="javascript:void(0);">{{ mb_strimwidth($cart['name'], 0, 20, '..') }}</a>
                                                    </h6>
                                                    <p>₹ {{ $cart['total'] }}</p>
                                                </div>
                                            </div>
                                            <div class="qty-item text-center">
                                                <a href="javascript:void(0);"
                                                    class="dec d-flex justify-content-center align-items-center"
                                                    data-sku="{{ $cart['sku'] }}">
                                                    <i data-feather="minus-circle" class="feather-14"></i>
                                                </a>
                                                <input type="text" class="form-control text-center qty-input" name="qty"
                                                    value="{{ $cart['quantity'] }}" readonly>
                                                <a href="javascript:void(0);"
                                                    class="inc d-flex justify-content-center align-items-center"
                                                    data-sku="{{ $cart['sku'] }}">
                                                    <i data-feather="plus-circle" class="feather-14"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>


                            </div>

                            <div class="block-section">
                                <div class="order-total">
                                    <table class="table table-responsive table-borderless">

                                        <tr>
                                            <td>Sub Total</td>
                                            <td class="text-end" id="actual_displayed_amount">0</td>
                                        </tr>

                                        <tr>
                                            <td>Total</td>
                                            <td class="text-end" id="total_displayed_amount">0</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="block-section payment-method">
                                <h6>Payment Method</h6>
                                <div class="row d-flex align-items-center justify-content-center methods mt-3">
                                    {{-- <div class="col-md-4 col-lg-4 item">
                                        <div class="default-cover">
                                            <a href="javascript:void(0);" class="payment-option" data-method="cash">
                                                <img src="{{ asset('media/cash-pay.svg') }}" alt="Payment Method">
                                                <span>Cash</span>
                                            </a>
                                        </div>
                                    </div> --}}

                                    <div class="col-md-6 col-lg-6 item">
                                        <div class="default-cover">
                                            <a href="javascript:void(0);" class="payment-option" data-method="upi">
                                                <img src="{{ asset('media/upi.svg') }}" alt="Payment Method">
                                                <span>UPI</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 item">
                                        <div class="default-cover">
                                            <a href="javascript:void(0);" class="payment-option" data-method="card">
                                                <img src="{{ asset('media/credit-card.svg') }}" alt="Payment Method">
                                                <span>Card</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="">Ref Id</label>
                                <input type="text" name="reference_id" class="form-control" id="">
                            </div>

                            <div id="payment-error" class="text-danger mb-2" style="display: none;">
                                Please select the payment method.
                            </div>


                            <div class="d-grid btn-block mt-3">
                                <a class="btn btn-secondary" href="javascript:void(0);">
                                    Grand Total : ₹ <span id="total_displayedAmount_button"></span>
                                </a>
                            </div>

                            <input type="hidden" name="sub_total" id="total_displayedAmount">
                            <input type="hidden" name="actual_total" id="actual_displayedAmount">
                            <input type="hidden" name="payment_method" id="payment_method" value="">

                            <div class="btn-row d-sm-flex align-items-center justify-content-between">
                                <button type="submit" class="btn btn-success btn-icon flex-fill" id="payment_button">
                                    <span class="me-1 d-flex align-items-center">
                                        <i data-feather="credit-card" class="feather-16"></i>
                                    </span>Payment
                                </button>
                            </div>

                        </form>
                    </aside>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addCustomerModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="customerForm">
                    @csrf
                    <div class="modal-header">
                        <h5>Add Customer</h5>
                    </div>

                    <div class="modal-body">
                        <input type="text" name="name" class="form-control mb-2" placeholder="Name">
                        <input type="text" name="mobile" class="form-control mb-2" placeholder="Mobile" required>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addAddressModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addressForm">
                    @csrf

                    <input type="hidden" name="customer_id" id="address_customer_id">

                    <div class="modal-header">
                        <h5>Add Address</h5>
                    </div>

                    <div class="modal-body">

                        <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>

                        <input type="text" name="mobile" class="form-control mb-2" placeholder="Mobile" required>

                        <input type="email" name="email" class="form-control mb-2" placeholder="Email">

                        <input type="text" name="gst" class="form-control mb-2" placeholder="GST">

                        <textarea name="address" class="form-control mb-2" placeholder="Address" required></textarea>

                        <input type="text" name="address_2" class="form-control mb-2" placeholder="Address 2">

                        <input type="text" name="city" class="form-control mb-2" placeholder="City" required>

                        <!-- ✅ STATE DROPDOWN -->
                        <select name="state_id" class="form-control mb-2" id="stateSelect" required>
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                        </select>

                        <!-- hidden state name -->
                        <input type="hidden" name="state" id="state_name">

                        <input type="text" name="pincode" class="form-control mb-2" placeholder="Pincode" required>

                        <input type="text" name="landmark" class="form-control mb-2" placeholder="Landmark">

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success">Save Address</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
        // Initialize Feather Icons
        function initializeFeatherIcons() {
            feather.replace(); // Replace all icons with SVG
        }

        $(document).ready(function () {
            initializeFeatherIcons(); // Initialize icons on page load

            fetchCart(); // Fetch cart data on page load


            $(document).on('click', '.delete-product', function () {
                var sku = $(this).data('sku');
                deleteProductFromCart(sku);
            });
            // Event listener for increment button
            $(document).on('click', '.inc', function () {
                var sku = $(this).data('sku');
                updateQuantity(sku, 'increase');
            });

            // Event listener for decrement button
            $(document).on('click', '.dec', function () {
                var sku = $(this).data('sku');
                updateQuantity(sku, 'decrease');
            });
        });

        // Function to add product to cart
        function addtoCart(sku) {
            var formData = new FormData();
            formData.append('sku', sku);
            $.ajax({
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                url: "{{ route('admin.manualCart') }}",
                success: function (data) {
                    if (data.success == 1) {
                        fetchCart(); // Refresh cart after adding product
                        calculateTotals();
                    }
                }
            });
        }

        // Function to update quantity
        function updateQuantity(sku, action) {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                type: 'POST',
                url: "{{ route('admin.manualCart') }}",
                data: {
                    sku: sku,
                    action: action
                },
                success: function (response) {
                    if (response.success == 1) {
                        fetchCart(); // Refresh cart after updating quantity
                        calculateTotals();
                    }
                },
                error: function (xhr) {
                    console.error("Error updating quantity:", xhr.responseText);
                }
            });
        }

        // Function to fetch cart data
        function fetchCart() {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                type: 'GET',
                url: "{{ route('admin.fetchCart') }}",
                success: function (response) {
                    if (response.success == 1) {
                        updateCartUI(response.cart); // Update cart UI
                        calculateTotals(); // Calculate totals
                    }
                },
                error: function (xhr) {
                    console.error("Error fetching cart:", xhr.responseText);
                }
            });
        }

        // Function to update cart UI
        function updateCartUI(cart) {
            var cartTableBody = $('#cart-table-body');
            cartTableBody.empty(); // Clear existing cart items

            cart.forEach(function (item) {
                var total = item.total;

                var cartRow = `
                                                                                                                                        <div class="product-list d-flex align-items-center justify-content-between">
                                                                                                                                            <div class="d-flex align-items-center product-info" data-sku="${item.sku}">
                                                                                                                                                <a href="javascript:void(0);" class="img-bg">
                                                                                                                                                    <img src="{{ asset('${item.image}') }}" alt="Products" style="width: 80px;">
                                                                                                                                                </a>
                                                                                                                                                <div class="info">
                                                                                                                                                    <h6><a href="javascript:void(0);">${item.name.substring(0, 20)}...</a></h6>
                                                                                                                                                    <p>₹ ${item.total}</p>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                            <div class="qty-item text-center">
                                                                                                                                                <a href="javascript:void(0);" class="dec d-flex justify-content-center align-items-center" data-sku="${item.sku}">
                                                                                                                                                    <i data-feather="minus-circle" class="feather-14"></i>
                                                                                                                                                </a>
                                                                                                                                                <input type="text" class="form-control text-center qty-input" name="qty" value="${item.quantity}" readonly>
                                                                                                                                                <a href="javascript:void(0);" class="inc d-flex justify-content-center align-items-center" data-sku="${item.sku}">
                                                                                                                                                    <i data-feather="plus-circle" class="feather-14"></i>
                                                                                                                                                </a>
                                                                                                                                                    <a href="javascript:void(0);" class="delete-product mt-1 text-danger" data-sku="${item.sku}">
                                                                                                                                <i data-feather="trash-2" class="feather-14"></i>
                                                                                                                            </a>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                                 <input type="hidden" class="actual_total" value="${item.total}">
                                                                                                                                                <input type="hidden" class="total_amount" value="${total}">
                                                                                                                                    `;
                cartTableBody.append(cartRow); // Append new row to cart
            });

            initializeFeatherIcons(); // Reinitialize Feather Icons after updating the UI
        }
        // Function to delete product from cart
        function deleteProductFromCart(sku) {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                type: 'POST',
                url: "{{ route('admin.manualCart') }}", // Same route as for add/update
                data: {
                    sku: sku,
                    action: 'delete'
                },
                success: function (response) {
                    if (response.success == 1) {
                        fetchCart(); // Refresh cart after deleting product
                        calculateTotals();
                    }
                },
                error: function (xhr) {
                    console.error("Error deleting product:", xhr.responseText);
                }
            });
        }
        // Function to calculate totals
        function calculateTotals() {
            let totalAmount = 0;
            let actualTotal = 0;

            // Debug: Log all hidden inputs
            console.log("Total Amount Inputs:", $('.total_amount'));
            console.log("Actual Total Inputs:", $('.actual_total'));
            $('.total_amount').each(function () {
                totalAmount += parseFloat($(this).val()) || 0;
            });

            $('.actual_total').each(function () {
                actualTotal += parseFloat($(this).val()) || 0;
            });


            $('#total_displayed_amount').html(`₹ ${totalAmount.toFixed(2)}`);
            $('#actual_displayed_amount').html(`₹ ${actualTotal.toFixed(2)}`);
            $('#total_displayedAmount').val(totalAmount.toFixed(2));
            $('#total_displayedAmount_button').html(totalAmount.toFixed(2));
            $('#actual_displayedAmount').val(actualTotal.toFixed(2));

            // Debug: Log calculated values
            console.log("Total Amount:", totalAmount);
            console.log("Actual Total:", actualTotal);
        }

        $(document).ready(function () {
            $('#customerSelect').change(function () {
                let customerId = $(this).val();

                // Reset Address Dropdown
                $('#customerAddress').html('<option value="">Select Address</option>');

                if (customerId) {
                    $.ajax({
                        url: "{{ route('admin.getCustomerAddresses') }}", // Ensure this route is correct
                        type: "GET",
                        data: { customer_id: customerId },
                        success: function (response) {
                            if (response.length > 0) {
                                $('#customerAddress').empty(); // Clear previous options
                                response.forEach(function (address) {
                                    console.log(address);
                                    let addressValue = `${address.id}`;
                                    let addressLabel = `${address.name}, ${address.address}, ${address.pincode}`;

                                    $('#customerAddress').append(`<option value="${addressValue}">${addressLabel}</option>`);
                                });
                            }
                        },
                        error: function () {
                            alert("Error fetching addresses.");
                        }
                    });
                }

            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const paymentMethodInput = document.getElementById("payment_method");
            const paymentOptions = document.querySelectorAll(".payment-option");

            paymentOptions.forEach(option => {
                option.addEventListener("click", function () {
                    const selectedMethod = this.getAttribute("data-method");
                    paymentMethodInput.value = selectedMethod;

                    // Remove active class from all and add to selected one
                    paymentOptions.forEach(opt => opt.classList.remove("active"));
                    this.classList.add("active");
                });
            });
        });

        $("#payment_button").click(function (e) {
            e.preventDefault();

            let customer = $('#customerSelect').val();
            let address = $('#customerAddress').val();
            let method = $('#payment_method').val();
            let total = parseFloat($('#total_displayedAmount').val()) || 0;

            // ❌ Customer validation
            if (!customer) {
                $('#customerSelect').focus().css('border', '1px solid red');
                alert('⚠️ Please select customer');
                return false;
            }

            // ❌ Address validation
            if (!address) {
                $('#customerAddress').focus().css('border', '1px solid red');
                alert('⚠️ Please select address');
                return false;
            }

            // ❌ Cart validation
            if (total <= 0) {
                alert('⚠️ Cart is empty');
                return false;
            }

            // ❌ Payment method validation
            if (!method) {
                $("#payment-error").show();
                return false;
            }

            // ✅ All good
            $("#payment-error").hide();
            $("#payment_form").submit();
        });

        $(".payment-option").click(function () {
            var selectedMethod = $(this).data("method");
            $("#payment_method").val(selectedMethod);
            $(".payment-option").removeClass("active");
            $(this).addClass("active");

            $("#payment-error").hide();
        });
    </script>
    <style>
        .payment-option.active {
            border: 2px solid #007bff;
            border-radius: 8px;
            padding: 3px;
        }
    </style>

    <script>
        $('#sku_search').on('keypress', function (e) {
            if (e.which == 13) { // Enter key
                e.preventDefault();

                let sku = $(this).val().trim();

                if (sku != '') {
                    addtoCart(sku); // existing function
                    $(this).val('');
                }
            }
        });
    </script>
    <script>
        $('#customerForm').submit(function (e) {
            e.preventDefault();

            $.post("{{ route('admin.addCustomer') }}", $(this).serialize(), function (res) {

                if (res.success == 1) {

                    $('#customerSelect').append(
                        `<option value="${res.customer.id}" selected>
                                                                                                    ${res.customer.name}
                                                                                                </option>`
                    );

                    $('#addCustomerModal').modal('hide');
                    $('#customerForm')[0].reset();

                    // load addresses (empty initially)
                    $('#customerSelect').trigger('change');
                }
            });
        });
    </script>
    <script>
        $('#addAddressBtn').on('click', function () {

            let customerId = $('#customerSelect').val();

            if (!customerId) {
                alert('⚠️ Please select customer first');
                return false;
            }

            $('#address_customer_id').val(customerId);

            console.log('Customer ID set:', customerId);
        });
        $('#addressForm').submit(function (e) {
            e.preventDefault();

            $.post("{{ route('admin.addAddress') }}", $(this).serialize(), function (res) {

                if (res.success == 1) {

                    $('#customerAddress').append(
                        `<option value="${res.address.id}" selected>
                                                                    ${res.address.label}
                                                                </option>`
                    );

                    $('#addAddressModal').modal('hide');
                    $('#addressForm')[0].reset();
                }
            });
        });
        $('#stateSelect').change(function () {
            let stateName = $(this).find('option:selected').text();
            $('#state_name').val(stateName);
        });
    </script>

    <script>
        $(document).ready(function () {

            function toggleCustomerButton() {
                let value = $('#customerSelect').val();

                if (value === "" || value === null) {
                    $('#addCustomerBtn').show();
                } else {
                    $('#addCustomerBtn').hide();
                }
            }

            // Run on change
            $('#customerSelect').change(function () {
                toggleCustomerButton();
            });

            // Run on page load
            toggleCustomerButton();
        });

        $(document).ready(function () {

            function toggleAddressButton() {
                let value = $('#customerAddress').val();

                if (value === "" || value === null) {
                    $('#addAddressBtn').show();
                } else {
                    $('#addAddressBtn').hide();
                }
            }

            // On address change
            $('#customerAddress').change(function () {
                toggleAddressButton();
            });

            // Run on page load
            toggleAddressButton();
        });
    </script>
@endsection