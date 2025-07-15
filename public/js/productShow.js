
//sidebar cart
function addToCart(id, name, price, image) {
    fetch('{{ route('cart.add') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id, name: name, price: price, image: image })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            openCart(); // refresh & show sidebar
        }
    });
}
    function openCart() {
        document.getElementById('cartSidebar').style.display = 'block';
        fetchCartItems();
    }

    function closeCart() {
        document.getElementById('cartSidebar').style.display = 'none';
    }

    function fetchCartItems() {
        fetch("/cart/items")
            .then(res => res.json())
            .then(data => {
                let html = '';
                let subtotal = 0;
                data.forEach(item => {
                    html += `
                    <div class="cart-item d-flex align-items-start gap-2">
                        <img src="${item.image}" alt="${item.name}">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>${item.name}</strong>
                                    <div>Rs. ${item.price}</div>
                                </div>
                                <button class="btn btn-sm" onclick="removeCartItem(${item.id})">
                                    🗑
                                </button>
                            </div>
                            <div class="quantity-control mt-2">
                                <button onclick="updateQuantity(${item.id}, -1)">−</button>
                                <span class="mx-2">${item.quantity}</span>
                                <button onclick="updateQuantity(${item.id}, 1)">+</button>
                            </div>
                        </div>
                    </div>`;
                    subtotal += item.price * item.quantity;
                });

                document.getElementById('cart-items').innerHTML = html;
                document.getElementById('cart-subtotal').innerText = 'Rs. ' + subtotal;
            });
    }

    function updateQuantity(id, delta) {
        fetch(`/cart/update/${id}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify({ delta: delta })
        }).then(() => fetchCartItems());
    }

    function removeCartItem(id) {
        fetch(`/cart/remove/${id}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => fetchCartItems());
    }