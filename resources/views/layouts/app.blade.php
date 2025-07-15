<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MSGM Dashboard</title>

    <script src="https://kit.fontawesome.com/275c3c76a3.js" crossorigin="anonymous"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Favicon -->
    <link href="{{ asset('images/logo.png') }}" rel="icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #wrapper {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        #sidebar-wrapper {
            width: 250px;
            background-color: #343a40;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            z-index: 1000;
            overflow-y: auto;
            transition: left 0.3s ease;
        }

        #page-content-wrapper {
            margin-left: 250px;
            width: 100%;
            overflow-y: auto;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        @media (max-width: 991.98px) {
            #sidebar-wrapper {
                left: -250px;
            }

            #sidebar-wrapper.show {
                left: 0;
            }

            #page-content-wrapper {
                margin-left: 0;
            }

            #page-content-wrapper.shifted {
                margin-left: 250px;
            }
        }
    </style>
</head>

<body class="font-sans">
    <div id="wrapper">
        <!-- Sidebar -->
        <div class="admin-sidebar" id="sidebar-wrapper">
            <nav class="navbar navbar-expand-lg flex-column align-items-start h-100 p-3">
                <a href="{{ route('dashboard') }}" class="mb-1">
                    <img class="d-flex align-items-center justify-content center " src="{{ asset('images/logo.png') }}" width="180px" alt="MSGM Logo">
                </a>
                <hr class="text-white w-100">

                <ul class="nav nav-pills flex-column w-100">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-gauge me-2"></i> Dashboard
                        </a>
                    </li>
                   {{-- Orders  --}}
                        <li class="nav-item">
                            @php
                                $orderRoutesActive = request()->routeIs(['orders.*', 'order_items.*']);
                            @endphp
                            <a class="nav-link link-light d-flex justify-content-between align-items-center  }}"
                                data-bs-toggle="collapse" href="#ordersSubmenu" role="button"
                                aria-expanded="{{ $orderRoutesActive ? 'true' : 'false' }}"
                                aria-controls="ordersSubmenu">
                                <span class="d-flex align-items-center">
                                    <i class="fa-solid fa-cart-shopping me-2"></i> Orders
                                </span>
                                <span class="bi bi-chevron-down"></span>
                            </a>

                            <div class="collapse {{ $orderRoutesActive ? 'show' : '' }}" id="ordersSubmenu">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-3">
                                    <li>
                                        <a href="{{ route('orders.index') }}"  class="nav-link link-light {{ request()->routeIs('orders.index') || request()->routeIs('orders.show') || request()->routeIs('orders.edit') || request()->routeIs('orders.create') ? 'active' : '' }}">
                                            <i class="fa-solid fa-list me-2"></i> All Orders
                                        </a>
                                     </li>

                                    <li>
                                        <a href="{{ route('order_items.index') }}"
                                            class="nav-link link-light {{ request()->routeIs('order_items.*') ? 'active' : '' }}">
                                            <i class="fa-solid fa-receipt me-2"></i> Order Items
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    <li class="nav-item">
                        @php
                            $productRoutesActive = request()->routeIs([
                                'products.*',
                                'product_attributes.*',
                                'attribute_values.*',
                                'product_variants.*',
                            ]);
                        @endphp
                        <a class="nav-link link-light d-flex justify-content-between align-items-center "
                            data-bs-toggle="collapse" href="#productsSubmenu" role="button"
                            aria-expanded="{{ $productRoutesActive ? 'true' : 'false' }}"
                            aria-controls="productsSubmenu">
                            <span class="d-flex align-items-center">
                                <i class="fa-solid fa-shirt me-2"></i> Products
                            </span>
                            <span class="bi bi-chevron-down"></span>
                        </a>
                        <div class="collapse {{ $productRoutesActive ? 'show' : '' }}" id="productsSubmenu">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-3">
                                <li><a href="{{ route('products.index') }}"
                                        class="nav-link link-light {{ request()->routeIs('products.index') || request()->routeIs('products.show') || request()->routeIs('products.edit') || request()->routeIs('products.create') ? 'active' : '' }}">
                                        <i class="fa-solid fa-list me-2"></i> All Products
                                    </a>
                                </li>
                                <li><a href="{{ route('product_variants.index') }}"
                                        class="nav-link link-light {{ request()->routeIs('product_variants.*') ? 'active' : '' }}">
                                        <i class="fa-solid fa-boxes-stacked me-2"></i> Product Variants
                                    </a>
                                </li>
                                <li><a href="{{ route('product_attributes.index') }}"
                                        class="nav-link link-light {{ request()->routeIs('product_attributes.*') ? 'active' : '' }}">
                                        <i class="fa-solid fa-puzzle-piece me-2"></i> Product Attributes
                                    </a>
                                </li>
                                <li><a href="{{ route('attribute_values.index') }}"
                                        class="nav-link link-light {{ request()->routeIs('attribute_values.*') ? 'active' : '' }}">
                                        <i class="fa-solid fa-tags me-2"></i> Attribute Values
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                     <li class="nav-item">
                    <a href="{{ route('categories.index') }}"
                        class="nav-link text-white {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-list me-2"></i> Categories
                    </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('inventories.index') }}"
                            class="nav-link text-white {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-warehouse me-2"></i> Inventory
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('sales.index')}}"
                            class="nav-link text-white {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-line me-2"></i> Sales
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('shipping_methods.index') }}"
                            class="nav-link text-white {{ request()->routeIs('shipping_methods.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-truck me-2"></i> Shipping Methods
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('coupons.index') }}"
                            class="nav-link text-white {{ request()->routeIs('coupons.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-tags me-2"></i> Discounts
                        </a>
                    </li>

                    {{-- Carts  --}}
                        <li class="nav-item">
                            @php
                                $cartRoutesActive = request()->routeIs(['carts.*', 'cart_items.*']);
                            @endphp

                            <a class="nav-link link-light d-flex justify-content-between align-items-center "
                                data-bs-toggle="collapse" href="#cartsSubmenu" role="button"
                                aria-expanded="{{ $cartRoutesActive ? 'true' : 'false' }}"
                                aria-controls="cartsSubmenu">
                                <span class="d-flex align-items-center">
                                    <i class="fa-solid fa-shopping-cart me-2"></i> Carts
                                </span>
                                <span class="bi bi-chevron-down"></span>
                            </a>

                            <div class="collapse {{ $cartRoutesActive ? 'show' : '' }}" id="cartsSubmenu">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-3">
                                    <li>
                                        <a href="{{ route('carts.index') }}"
                                                class="nav-link link-light {{ request()->routeIs('carts.index') || request()->routeIs('carts.show') || request()->routeIs('carts.edit') || request()->routeIs('carts.create') ? 'active' : '' }}">
                                                <i class="fa-solid fa-list me-2"></i> All Carts
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('cart_items.index') }}"
                                            class="nav-link link-light {{ request()->routeIs('cart_items.*') ? 'active' : '' }}">
                                            <i class="fa-solid fa-receipt me-2"></i> Cart Items
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>


                    <li class="nav-item">
                        <a href="{{route("reviews.index")}}"
                            class="nav-link text-white {{ request()->routeIs('reviews.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-star me-2"></i> Reviews
                        </a>
                    </li>



                    <li class="nav-item">
                        <a href="{{route("top_headlines.index")}}"
                            class="nav-link text-white {{ request()->routeIs('top_headlines.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-bullhorn me-2"></i> Top Headline
                        </a>
                    </li>

                     {{-- Users Submenu --}}
                        <li class="nav-item">
                            @php
                                $userRoutesActive = request()->routeIs(['guestUser', 'users', 'addresses.*']);
                            @endphp
                            <a class="nav-link link-light d-flex justify-content-between align-items-center "
                                data-bs-toggle="collapse" href="#usersSubmenu" role="button"
                                aria-expanded="{{ $userRoutesActive ? 'true' : 'false' }}"
                                aria-controls="usersSubmenu">
                                <span class="d-flex align-items-center">
                                    <i class="fa-solid fa-users me-2"></i> Users
                                </span>
                                <span class="bi bi-chevron-down"></span>
                            </a>
                            <div class="collapse {{ $userRoutesActive ? 'show' : '' }}" id="usersSubmenu">
                                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-3">
                                    <li><a href="{{ url(route('guestUser')) }}"
                                            class="nav-link link-light {{ request()->routeIs('guestUser') ? 'active' : '' }}">
                                            <i class="fa-solid fa-user-secret"></i> Guest Users</a>
                                    </li>

                                    <li><a href="{{ url(route('users')) }}"
                                            class="nav-link link-light {{ request()->routeIs('users') ? 'active' : '' }}">
                                            <i class="fa-solid fa-users"></i> Registered Users</a>
                                    </li>

                                    <li><a href="{{ route('addresses.index') }}"
                                            class="nav-link link-light {{ request()->routeIs('addresses.*') ? 'active' : '' }}">
                                            <i class="fa-solid fa-address-book me-2"></i> Addresses
                                        </a></li>
                                </ul>
                            </div>
                        </li>

                    {{-- User Profile/Logout Submenu --}}
                    <li class="nav-item">
                        <a class="nav-link link-light d-flex justify-content-between align-items-center"
                            data-bs-toggle="collapse" href="#userMenu" role="button" aria-expanded="false"
                            aria-controls="userMenu">
                            <span class="d-flex align-items-center">
                                <i class="fa-solid fa-user-circle me-2"></i> {{ Auth::user()->name }}
                            </span>
                            <span class="bi bi-chevron-down"></span>
                        </a>
                        <div class="collapse" id="userMenu">
                            <ul class="list-unstyled fw-normal pb-1 small ps-3">
                                <li>
                                    <a href="{{ route('profile.edit') }}" class="nav-link link-light">Profile</a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="nav-link link-light logout-button link-light">
                                            Log Out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div id="page-content-wrapper">
            <!-- Toggler for mobile -->
            <button class="btn btn-outline-dark d-lg-none mb-3" id="sidebarToggle">
                <i class="bi bi-list"></i> Menu
            </button>

            <!-- Header slot -->
            @if (isset($header))
                <header class="bg-white shadow py-3 ">
                    <div class="container-fluid">
                        <h3 class="mb-0">{{ $header }}</h3>
                    </div>
                </header>
            @endif

            <!-- Blade slot -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>

    <!-- Toggle Sidebar Script -->
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar-wrapper');
            const content = document.getElementById('page-content-wrapper');
            sidebar.classList.toggle('show');
            content.classList.toggle('shifted');
        });
    </script>
</body>

</html>
