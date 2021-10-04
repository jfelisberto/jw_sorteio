@extends('layouts.app', ['current'=>'home'])


@section('stylesheet')
<style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }

    .carousel-item {
        height: 32rem;
        background: #777;
        color: #fff;
        position: relative;
    }



    .overlay-image {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        top: 0;
        background-position: center;
        background-size: cover;
        opacity: 0.8;
    }
</style>
{{-- <link rel="stylesheet" href="{{ asset('css/carousel.min.css') }}" /> --}}
@endsection

@section('content')
<div class="row flex-lg-row-reverse align-items-center g-5 py-5">
    <div class="col-10 col-sm-8 col-lg-6">
        {{-- fazer carrosel --}}
        <div id="galery" class="carousel carousel-dark slide carousel-fade" data-bs-ride="carousel">
            <ol class="carousel-indicators">
                @foreach ($galery as $key => $item)
                <li data-bs-target="#galery" data-bs-slide-to="{{$key}}" class="{{$item['active']}}" aria-current="true" aria-label="Slide {{($key++)}}"></li>
                @endforeach
            </ol>

            <div class="carousel-inner">
                @foreach ($galery as $key => $item)
                <div class="carousel-item {{$item['active']}}">
                    <div class="overlay-image" style="background-image: url({{$item['url']}})"></div>
                    <p>{{$item['alt']}}</p>
                    {{-- <img src="{{$item['url']}}" class="d-block w-100" alt="{{$item['alt']}}"> --}}
                </div>
                @endforeach
            </div>
            <a href="#galery" class="carousel-control-prev" role="button" data-bs-target="#galery" data-bs-slide="prev">
                <span class="sr-only">Anterior</span>
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </a>
            <a href="#galery" class="carousel-control-next" role="button" data-bs-target="#galery" data-bs-slide="next">
                <span class="sr-only">Próximo</span>
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </a>
        </div>
    </div>

    <div class="col-lg-6">
      <h1 class="display-5 fw-bold lh-1 mb-3">Responsive left-aligned hero with image</h1>
      <p class="lead">Quickly design and customize responsive mobile-first sites with Bootstrap, the world’s most popular front-end open source toolkit, featuring Sass variables and mixins, responsive grid system, extensive prebuilt components, and powerful JavaScript plugins.</p>
      @guest
      <div class="d-grid gap-2 d-md-flex justify-content-md-start">
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 me-md-2">{{ __('Login') }}</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg px-4">{{ __('Register') }}</a>
      </div>
      @endguest
    </div>
</div>
@endsection

@section('javascript')
  <!-- Script for home -->
  <script type="text/javascript">
    var galery = document.querySelector('#galery');
    var carousel = new bootstrap.Carousel(galery, {
        interval: 2000,
        wrap: true,
        pause: 'hover',
        touch: true
    });
  </script>
@endsection
