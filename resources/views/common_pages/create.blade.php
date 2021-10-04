@extends('layouts.app', ['current'=>$here])

@section('headTitle', "{$headTitle} - Novo")

@section('content')
    <div class="card border w-75">
        <div class="card-header">
            <div class="row">
                <div class="col-auto">
                <h5 class="card-title">Novo cadastro</h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('components.form_template', ['route'=>route($routes['store']),'formID'=>"create_{$here}",'visibiles'=>[0,1,3]])
        </div>
        <div class="card-footer">
            @include('components.form_buttons', ['backToPage'=>$routes['index'],'formID'=>"create_{$here}"])
        </div>
    </div>
@endsection
