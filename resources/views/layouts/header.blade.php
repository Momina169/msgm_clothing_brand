  <style>
      .nav-link {
          color: black;
      }
  </style>
  </head>

  <body>

      <!-- Top Headline Section (Optional) -->
      @if (isset($topHeadline) && $topHeadline)
          <div class="bg-dark text-white text-center py-2">
              <p class="mb-0">{{ $topHeadline->headline }}</p>
          </div>
      @endif
      <nav class="navbar navbar-expand-lg shadow-lg sticky-top bg-white">
          <div class="container">
              <!-- Logo on the left -->
              <a class="navbar-brand" href="">
                  <img src="{{ asset('images/logo.png') }}" width="150px" height="76px">
              </a>

              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav"
                  aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
              </button>

              <!-- Navigation items on the right -->
              <div class="collapse navbar-collapse" id="main-nav">
                  <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                      <li class="nav-item">
                          <a class="nav-link " aria-current="page" href="{{ route('home') }}">Home</a>
                      </li>

                      <li class="nav-item"><a class="nav-link"
                              href="{{ route('categories.show', 'formal-wear') }}">Formal Wear</a></li>
                      <li class="nav-item"><a class="nav-link"
                              href="{{ route('categories.show', 'essential-pret') }}">Essential Pret</a></li>
                      <li class="nav-item"><a class="nav-link"
                              href="{{ route('categories.show', 'luxury') }}">Luxury</a></li>
                      <li class="nav-item"><a class="nav-link" href="{{route('about_us')}}">About Us</a></li>

                      <li class="nav-item">
                          <a class="nav-link " aria-current="page" href="{{route('contact_us')}}">Contact us</a>
                      </li>

                      <ul class="navbar-nav ms-auto d-flex align-items-center">
                          
                          <li class="nav-item me-3">
                              <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary position-relative">
                                  <i class="fa-solid fa-cart"></i> Cart
                                  @php
                                      $cart = session('cart', []);
                                      $cartCount = array_sum(array_column($cart, 'quantity'));
                                  @endphp
                                  @if ($cartCount > 0)
                                      <span
                                          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                          {{ $cartCount }}
                                      </span>
                                  @endif
                              </a>
                          </li>

                          {{-- Checkout button --}}
                          <li class="nav-item">
                              <a href="{{ route('checkout.show') }}" class="btn btn-primary">
                                  <i class="fa-solid fa-bag"></i> Checkout
                              </a>
                          </li>
                      </ul>



                  </ul>

              </div>
          </div>
      </nav>
