@extends('layouts._layout')
@section('content')

<link rel="stylesheet" href="{{ asset('css/home.css') }}">


<section class="image-section">
  <div class="image-slider">
    <a href="{{ route('categories.show', 'formal-wear') }}">
        <img src=" {{ asset('images/home/daily.jpg')}}" alt="Daily Wear Collection">
    </a>
    <a href="{{ route('categories.show', 'printed') }}">
        <img src="{{ asset('images/home/summer.jpg')}}" alt="Summer Collection">
    </a>
    <a href="{{ route('categories.show', 'essential-pret') }}">
        <img src="{{ asset('images/home/luxury.jpg')}}" alt="Luxury Pret Collection">
    </a>
</div>
</section>

<section class="shop-by-category-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4 fs-1 heading-font text-dark">Shop by Category</h2>

        @if($categories->isEmpty())
            <p class="text-center text-muted">No categories available at the moment.</p>
        @else
            <div class="categories-carousel-container" id="categoriesCarousel">
                <button class="carousel-button left" id="scrollLeftBtn"><i class="fas fa-chevron-left"></i></button>
                <div class="d-flex flex-nowrap pb-3" id="categoriesWrapper">
                    @foreach($categories as $category)
                        <div class="category-card card shadow-sm">
                            <a href="{{ route('categories.show', $category->slug) }}">
                                @if($category->image)
                                    <img src="{{ asset($category->image) }}" class="card-img-top" alt="{{ $category->name }}">
                                @else
                                    <img src="https://placehold.co/180x120/E0E0E0/333333?text={{ urlencode($category->name) }}" class="card-img-top" alt="{{ $category->name }}">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $category->name }}</h5>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-button right" id="scrollRightBtn"><i class="fas fa-chevron-right"></i></button>
            </div>
        @endif
    </div>
</section>>

{{-- Gallery Section --}}
<section class="category-gallery-section py-5 bg-white">
    <div class="container">
        <h2 class="text-center mb-4 fs-1 heading-font text-dark">
            @if(isset($parentCategory))
                {{ $parentCategory->name }}
            @else
                Featured Collection
            @endif
        </h2>

        @if(isset($parentCategory) && $parentCategory->children->isNotEmpty())
            <div class="gallery-grid">
                @foreach($parentCategory->children as $subCategory)
                    <a href="{{ route('categories.show', $subCategory->slug) }}" class="gallery-item">
                        @if($subCategory->image)
                            <img src="{{ asset( $subCategory->image) }}" class="gallery-item-image" alt="{{ $subCategory->name }}">
                        @else
                            <img src="https://placehold.co/400x300/E0E0E0/333333?text={{ urlencode($subCategory->name) }}" class="gallery-item-image" alt="{{ $subCategory->name }}">
                        @endif
                        <div class="gallery-item-overlay">
                            {{ $subCategory->name }}
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-center text-muted">No subcategories found for this collection.</p>
        @endif
    </div>
</section>

</body>
<script>
    // Automatic image rotation 
    const imageSlider = document.querySelector('.image-slider');
    const images = imageSlider.querySelectorAll('img');
    let currentIndex = 0;

    function showImage(index) {
        images.forEach(image => image.style.display = 'none');
        images[index].style.display = 'block';
    }

    function rotateImages() {
        currentIndex = (currentIndex + 1) % images.length;
        showImage(currentIndex);
    }

    setInterval(rotateImages, 3000); 
    showImage(currentIndex);

    //category

    document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('categoriesCarousel');
    const categoriesWrapper = document.getElementById('categoriesWrapper');
    const scrollLeftBtn = document.getElementById('scrollLeftBtn');
    const scrollRightBtn = document.getElementById('scrollRightBtn');
    const scrollAmount = 200;

    let scrollInterval;
    const autoScrollSpeed = 30;
    const autoScrollStep = 1;

    function startAutoScroll() {
        if (categoriesWrapper.scrollWidth <= categoriesWrapper.clientWidth) {
            return;
        }

        scrollInterval = setInterval(() => {
            if (categoriesWrapper.scrollLeft + categoriesWrapper.clientWidth >= categoriesWrapper.scrollWidth) {
                categoriesWrapper.scrollLeft = 0;
            } else {
                categoriesWrapper.scrollLeft += autoScrollStep;
            }
        }, autoScrollSpeed);
    }

    function stopAutoScroll() {
        clearInterval(scrollInterval);
    }

    scrollLeftBtn.addEventListener('click', () => {
        stopAutoScroll();
        categoriesWrapper.scrollLeft -= scrollAmount;
    });

    scrollRightBtn.addEventListener('click', () => {
        stopAutoScroll();
        categoriesWrapper.scrollLeft += scrollAmount;
    });

    carousel.addEventListener('mouseenter', stopAutoScroll);
    carousel.addEventListener('mouseleave', startAutoScroll);

    startAutoScroll();

    window.addEventListener('resize', () => {
        if (window.innerWidth < 992) {
            scrollLeftBtn.style.display = 'none';
            scrollRightBtn.style.display = 'none';
            stopAutoScroll();
        } else {
            scrollLeftBtn.style.display = 'block';
            scrollRightBtn.style.display = 'block';
            startAutoScroll();
        }
    });

    if (window.innerWidth < 992) {
        scrollLeftBtn.style.display = 'none';
        scrollRightBtn.style.display = 'none';
        stopAutoScroll();
    } else {
        scrollLeftBtn.style.display = 'block';
        scrollRightBtn.style.display = 'block';
        startAutoScroll();
    }
    });

</script>
@endsection