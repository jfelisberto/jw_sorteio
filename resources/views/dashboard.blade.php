@extends('layouts.app', ['current'=>'home'])

@section('content')
    <div class="card">
        <div class="card-header">{{ __('Dashboard') }}</div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

           Seja bem vindo.
        </div>
    </div>
@endsection
