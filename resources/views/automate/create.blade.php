@extends('layouts.app', ['current'=>$here])

@section('headTitle', "{$headTitle}")

@section('content')
@if ($step === 'presences')
<div class="card border w-75">
    <div class="card-header">
        <div class="row">
            <div class="col-auto">
                <h5 class="card-title">Automatizar lista de presença</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        Use esse formulário para convidar todos os jogadores registrados para participarem de uma partidas.<br />
        @include('components.form_template', ['route'=>route('autoPresenceStore'),'formID'=>"autoPresence",'visibiles'=>[0,1,3],'fields'=>$fields['matchesPresence']])
    </div>
    <div class="card-footer">
        @include('components.form_buttons', ['formID'=>'autoPresence','faIcon'=>'fas fa-users-cog','confirmButton'=>'Enviar convite','backToPage'=>$routes['index']])
    </div>
</div>
@endif

@if ($step === 'teams')
<div class="card border w-75">
    <div class="card-header">
        <div class="row">
            <div class="col-auto">
            <h5 class="card-title">Sortear times</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        Use esse quadro para sortear os jogadores em um times para uma partidas.<br />
        Só serão sorteados os jogadores que confirmaram presença na partida<br />
        @include('components.form_template', ['route'=>route('autoTeamStore'),'formID'=>"drawTeams",'visibiles'=>[0,1,3],'fields'=>$fields['matchesTeam']])
    </div>
    <div class="card-footer">
        @include('components.form_buttons', ['formID'=>'drawTeams','faIcon'=>'fas fa-cogs','confirmButton'=>'Sortear time','backToPage'=>$routes['index']])
    </div>
</div>
@endif
@endsection

@section('javascript')
<!-- Script for teste alert -->
<script type="text/javascript">
    var text = 'Teste';
    $(document).ready(function() {
        console.log(text);
    });
</script>
@endsection
