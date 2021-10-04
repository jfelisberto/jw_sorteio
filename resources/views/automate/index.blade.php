@extends('layouts.app', ['current'=>$here])

@section('headTitle', "{$headTitle}")

@section('content')
<div class="row">

    <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Convidar jogadores</h5>
                <p class="card-text">
                    Aqui você pode convidar todos os jogadores registrados para participarem de uma partidas.
                    <br /><br />
                </p>
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('autoPresenceCreate') }}" class="btn btn-light btn-sm text-primary">Convidar jogadores</a>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Sortear times</h5>
                <p class="card-text">
                    Aqui você pode sortear os jogadores em um times para participarem de uma partidas.<br />
                    Só serão sorteados os jogadores que confirmarem presença na partida<br />
                </p>
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('autoTeamCreate') }}" class="btn btn-light btn-sm text-primary">Sortear times</a>
            </div>
        </div>
    </div>

</div>
@endsection
