@extends('layouts.app', ['current'=>$here])

@section('headTitle', "{$headTitle} - Alteração")

@section('content')
    <div class="card border w-75">
        <div class="card-header">
            <div class="row">
                <div class="col-auto">
                    <h5 class="card-title">{{ $result['name'] }}</h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('components.form_template', ['route'=>route($routes['update'], $data['id']),'formID'=>"edit_{$here}",'visibiles'=>[1,3,4]])
        </div>
        <div class="card-footer">
            @include('components.form_buttons', ['backToPage'=>$routes['index'],'formID'=>"edit_{$here}"])
        </div>
    </div>
@endsection
