@extends('layouts.app', ['current'=>'home'])


@section('stylesheet')
<style>
    .carousel-item {
        height: 32rem;
        background: #777;
        position: relative;
    }
    .carousel-item p {
        color: #000;
        float: right;
        bottom: 0;
        left: 0;
        right:0;
        top: 10px;
        margin-right: 30px !important;
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
@endsection

@section('content')
<div class="row flex-lg-row-reverse align-items-center g-5 py-5">
    <div class="col-10 col-sm-8 col-lg-6">
        {{-- fazer carrosel --}}
        <div id="galery" class="carousel carousel-dark slide carousel-fade" data-ride="carousel" data-interval="false">
            <ol class="carousel-indicators">
                @foreach ($galery as $key => $item)
                <li data-target="#galery" data-slide-to="{{$key}}" class="{{$item['active']}}" aria-current="true" aria-label="Slide {{($key++)}}"></li>
                @endforeach
            </ol>

            <div class="carousel-inner">
                @foreach ($galery as $key => $item)
                <div class="carousel-item {{$item['active']}}">
                    <div class="overlay-image" style="background-image: url({{$item['url']}})"></div>
                    <p>{{$item['alt']}}</p>
                </div>
                @endforeach
            </div>
            <a href="#galery" class="carousel-control-prev abortDT" role="button" data-target="#galery" data-slide="prev">
                <span class="sr-only">Anterior</span>
                <i class="fas fa-chevron-left fa-2x fa-fw text-light"></i>
            </a>
            <a href="#galery" class="carousel-control-next abortDT" role="button" data-target="#galery" data-slide="next">
                <span class="sr-only">Próximo</span>
                <i class="fas fa-chevron-right fa-2x fa-fw text-light"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-6">
      <h1 class="display-5 fw-bold lh-1 mb-3">Venha jogar uma bilinha com a gente</h1>
      <p class="lead">Você acredita no poder do esporte?<br />
        Nós acreditamos.<br />
        Venha fazer parte da nossa equipe de jogadores, inscreva-se em nossa plataforma e receba um convite para participar em uma de nossas partidas.
      </p>
      @guest
      <div class="d-grid gap-2 d-md-flex justify-content-md-start">
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 me-md-2">{{ __('Login') }}</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg px-4">Registre-se</a>
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
