@extends('layouts._layout')
@section('content')
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        .checkout-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            padding: 15px 20px;
            font-size: 1.25rem;
            font-weight: bold;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 1.1rem;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 1.05rem;
        }

        .summary-total {
            font-weight: bold;
            font-size: 1.2rem;
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: 15px;
        }

        #responseMessage {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            display: none;
            /* Hidden by default */
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .address-selection-options label {
            margin-right: 20px;
        }
    </style>

    <body>
        <div class="container checkout-container">
            <h2 class="text-center mb-4">Complete Your Order</h2>

            <div class="row">
                <div class="col-md-7">
                    <div class="card mb-4">
                        <div class="card-header">
                            Shipping Information
                        </div>
                        <div class="card-body">
                            <form id="checkoutForm">
                                @csrf <!-- Laravel CSRF token -->

                                {{-- Conditional display for guest email if user is not logged in --}}
                                @guest
                                    <div class="mb-3">
                                        <label for="guest_email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control rounded" id="guest_email" name="guest_email"
                                            required>
                                    </div>
                                @endguest

                                <div class="mb-3">
                                    <label class="form-label">Delivery Address</label>
                                    <div class="address-selection-options">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="address_option"
                                                id="existingAddress" value="existing"
                                                {{ count($addresses) > 0 ? 'checked' : 'disabled' }}>
                                            <label class="form-check-label" for="existingAddress">Use Existing
                                                Address</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="address_option"
                                                id="newAddress" value="new"
                                                {{ count($addresses) == 0 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="newAddress">Add New Address</label>
                                        </div>
                                    </div>
                                </div>

                                <div id="existingAddressSection" class="mb-3"
                                    style="{{ count($addresses) > 0 ? '' : 'display: none;' }}">
                                    <label for="address_id" class="form-label">Select Address</label>
                                    <select class="form-select rounded" id="address_id" name="address_id"
                                        {{ count($addresses) > 0 ? 'required' : '' }}>
                                        <option value="">Choose...</option>
                                        @foreach ($addresses as $address)
                                            <option value="{{ $address->id }}">
                                                {{ $address->first_name }} {{ $address->last_name }},
                                                {{ $address->address_line_1 }}, {{ $address->city }},
                                                {{ $address->zip_code }}
                                                @if ($address->is_default)
                                                    (Default)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="newAddressSection" class="mb-3"
                                    style="{{ count($addresses) == 0 ? '' : 'display: none;' }}">
                                    <h5 class="mb-3">New Delivery Address</h5>
                                    <input type="hidden" name="use_new_address" id="use_new_address_flag"
                                        value="{{ count($addresses) == 0 ? '1' : '0' }}">

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="delivery_first_name" class="form-label">First Name</label>
                                            <input type="text" class="form-control rounded" id="delivery_first_name"
                                                name="delivery_first_name">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="delivery_last_name" class="form-label">Last Name</label>
                                            <input type="text" class="form-control rounded" id="delivery_last_name"
                                                name="delivery_last_name">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="delivery_company_name" class="form-label">Company Name
                                            (Optional)</label>
                                        <input type="text" class="form-control rounded" id="delivery_company_name"
                                            name="delivery_company_name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="delivery_type" class="form-label">Address Type (e.g., Home,
                                            Work)</label>
                                        <input type="text" class="form-control rounded" id="delivery_type"
                                            name="delivery_type" value="shipping" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="delivery_address_line_1" class="form-label">Address Line 1</label>
                                        <input type="text" class="form-control rounded" id="delivery_address_line_1"
                                            name="delivery_address_line_1" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="delivery_address_line_2" class="form-label">Address Line 2
                                            (Optional)</label>
                                        <input type="text" class="form-control rounded" id="delivery_address_line_2"
                                            name="delivery_address_line_2">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="delivery_city" class="form-label">City</label>
                                            <input type="text" class="form-control rounded" id="delivery_city"
                                                name="delivery_city" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="delivery_state" class="form-label">State/Province
                                                (Optional)</label>
                                            <input type="text" class="form-control rounded" id="delivery_state"
                                                name="delivery_state">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="delivery_zip_code" class="form-label">Zip/Postal Code</label>
                                            <input type="text" class="form-control rounded" id="delivery_zip_code"
                                                name="delivery_zip_code" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="delivery_country" class="form-label">Country</label>
                                            <input type="text" class="form-control rounded" id="delivery_country"
                                                name="delivery_country" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="delivery_phone_number" class="form-label">Phone Number
                                            (Optional)</label>
                                        <input type="text" class="form-control rounded" id="delivery_phone_number"
                                            name="delivery_phone_number">
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" value="1"
                                            id="delivery_is_default" name="delivery_is_default">
                                        <label class="form-check-label" for="delivery_is_default">
                                            Set as default address
                                        </label>
                                    </div>
                                </div>


                                <div class="mb-3">
                                    <label for="shipping_id" class="form-label">Select Shipping Method</label>
                                    <select class="form-select rounded" id="shipping_id" name="shipping_id" required>
                                        <option value="">Choose...</option>
                                        @foreach ($shippingMethods as $method)
                                            <option value="{{ $method->id }}">{{ $method->name }} -
                                                ${{ number_format($method->cost, 2) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <select class="form-select rounded" id="payment_method" name="payment_method"
                                        required>
                                        <option value="">Choose...</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="paypal">PayPal</option>
                                        <option value="cash_on_delivery">Cash on Delivery</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Order Notes (Optional)</label>
                                    <textarea class="form-control rounded" id="notes" name="notes" rows="3"></textarea>
                                </div>

                                <input type="hidden" id="total_amount" name="total_amount"
                                    value="{{ number_format($totalAmount, 2, '.', '') }}">

                                <button type="submit" class="btn btn-primary w-100 rounded">Place Order</button>
                            </form>
                            <div id="responseMessage" class="mt-3"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">
                            Order Summary
                        </div>
                        <div class="card-body">
                            @php $subtotal = 0; @endphp
                            @foreach ($cartItems as $key => $item)
                                <div class="summary-item">
                                    <span>{{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                                    <span>${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                </div>
                                @php $subtotal += ($item['price'] * $item['quantity']); @endphp
                            @endforeach
                            <div class="summary-item">
                                <span>Subtotal:</span>
                                <span>${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="summary-item">
                                <span>Shipping:</span>
                                <span id="shipping-cost">$0.00</span>
                            </div>
                            <div class="summary-total">
                                <span>Total:</span>
                                <span id="final-total">${{ number_format($totalAmount, 2) }}</span>
                            </div>
                            @if (count($cartItems) == 0)
                                <p class="text-muted mt-3">
                                    Your cart is empty. Please add items to proceed.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    
        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const checkoutForm = document.getElementById('checkoutForm');
                const responseMessage = document.getElementById('responseMessage');
                const shippingSelect = document.getElementById('shipping_id');
                const totalAmountInput = document.getElementById('total_amount');
                const shippingCostSpan = document.getElementById('shipping-cost');
                const finalTotalSpan = document.getElementById('final-total');

                const existingAddressRadio = document.getElementById('existingAddress');
                const newAddressRadio = document.getElementById('newAddress');
                const existingAddressSection = document.getElementById('existingAddressSection');
                const newAddressSection = document.getElementById('newAddressSection');
                const addressIdSelect = document.getElementById('address_id');
                const useNewAddressFlag = document.getElementById('use_new_address_flag');

                // Get all new address input fields
                const newAddressFields = [
                    document.getElementById('delivery_first_name'),
                    document.getElementById('delivery_last_name'),
                    document.getElementById('delivery_company_name'),
                    document.getElementById('delivery_type'),
                    document.getElementById('delivery_address_line_1'),
                    document.getElementById('delivery_address_line_2'),
                    document.getElementById('delivery_city'),
                    document.getElementById('delivery_state'),
                    document.getElementById('delivery_zip_code'),
                    document.getElementById('delivery_country'),
                    document.getElementById('delivery_phone_number'),
                    document.getElementById('delivery_is_default')
                ];


                let baseSubtotal = parseFloat("{{ number_format($subtotal, 2, '.', '') }}");
                let currentShippingCost = 0;

                // Function to update total based on shipping selection
                function updateOrderTotal() {
                    const selectedShippingOption = shippingSelect.options[shippingSelect.selectedIndex];
                    const shippingText = selectedShippingOption.textContent;
                    const shippingMatch = shippingText.match(/\$([\d.]+)/);

                    if (shippingMatch) {
                        currentShippingCost = parseFloat(shippingMatch[1]);
                    } else {
                        currentShippingCost = 0; // Default if no cost found
                    }

                    const newTotal = baseSubtotal + currentShippingCost;
                    shippingCostSpan.textContent = `$${currentShippingCost.toFixed(2)}`;
                    finalTotalSpan.textContent = `$${newTotal.toFixed(2)}`;
                    totalAmountInput.value = newTotal.toFixed(2); // Update hidden input
                }

                // Function to toggle address sections and required attributes
                function toggleAddressSections() {
                    if (newAddressRadio.checked) {
                        existingAddressSection.style.display = 'none';
                        newAddressSection.style.display = 'block';
                        addressIdSelect.removeAttribute('required');
                        newAddressFields.forEach(field => {
                            if (field.id === 'delivery_company_name' || field.id ===
                                'delivery_address_line_2' || field.id === 'delivery_state' || field.id ===
                                'delivery_phone_number' || field.id === 'delivery_is_default') {
                                // These fields are nullable, no required attribute needed
                            } else {
                                field.setAttribute('required', 'required');
                            }
                        });
                        useNewAddressFlag.value = '1';
                    } else {
                        existingAddressSection.style.display = 'block';
                        newAddressSection.style.display = 'none';
                        addressIdSelect.setAttribute('required', 'required');
                        newAddressFields.forEach(field => {
                            field.removeAttribute('required');
                        });
                        useNewAddressFlag.value = '0';
                    }
                }

                // Initial update on page load
                updateOrderTotal();
                toggleAddressSections(); // Set initial state for address sections

                // Listen for changes in shipping method
                shippingSelect.addEventListener('change', updateOrderTotal);

                // Listen for changes in address selection radio buttons
                existingAddressRadio.addEventListener('change', toggleAddressSections);
                newAddressRadio.addEventListener('change', toggleAddressSections);


                checkoutForm.addEventListener('submit', function(e) {
                    e.preventDefault(); // Prevent default form submission

                    const formData = new FormData(checkoutForm);
                    const data = {};
                    for (let [key, value] of formData.entries()) {
                        data[key] = value;
                    }

                    // Ensure total_amount is the dynamically calculated one
                    data['total_amount'] = totalAmountInput.value;

                    // Handle the boolean conversion for is_default checkbox
                    data['delivery_is_default'] = document.getElementById('delivery_is_default').checked ? 1 :
                    0;

                    fetch('/checkout', { // This is the route we will define in web.php
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': data['_token'] // Send CSRF token
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => {
                                    throw err;
                                });
                            }
                            return response.json();
                        })
                        .then(result => {
                            responseMessage.style.display = 'block';
                            if (result.success) {
                                responseMessage.className = 'alert alert-success';
                                responseMessage.textContent = result.message;
                                checkoutForm.reset(); // Clear the form on success
                                // Optionally redirect or update UI
                                window.location.href = '/order-confirmation/' + result
                                .order_id; // Example redirect
                            } else {
                                responseMessage.className = 'alert alert-danger';
                                responseMessage.textContent = result.message ||
                                'An unknown error occurred.';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            responseMessage.style.display = 'block';
                            responseMessage.className = 'alert alert-danger';
                            if (error.errors) {
                                let errorMessages = Object.values(error.errors).flat().join('<br>');
                                responseMessage.innerHTML = 'Validation Error:<br>' + errorMessages;
                            } else {
                                responseMessage.textContent = error.message ||
                                    'Failed to process checkout. Please try again.';
                            }
                        });
                });
            });
        </script>
    </body>
@endsection
