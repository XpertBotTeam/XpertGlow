<link rel="stylesheet" href="{{ asset('assets/css/user/carousel.css') }}">


<div class="carousel_container">
    <button id="prevBtn" class="prevButton"><i class="fa-solid fa-chevron-left"></i></button>
    <div class="carousel_slide">
    @foreach ($carousels as $carousel)
    @if($carousel->carouselable_type == "App\Models\Product")
        <a href="/product/{{$carousel->carouselable_id}}">
            <img src="{{ asset('storage/images/carousels/' . $carousel->images->first()->path) }}">
        </a>
    @endif

    @if($carousel->carouselable_type == "App\Models\SubCategory")
        <a href="/subcategory/{{$carousel->carouselable_id}}">
            <img src="{{ asset('storage/images/carousels/' . $carousel->images->first()->path) }}">
        </a>
    @endif
    @endforeach
    </div>
    <button id="nextBtn" class="nextButton"><i class="fa-solid fa-chevron-right"></i></button>
</div>

<script src="{{ asset('assets/js/user/carousel.js') }}"></script>
