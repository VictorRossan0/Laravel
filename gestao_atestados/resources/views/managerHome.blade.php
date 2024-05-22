@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard Gerente') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>{{ __('Você está logado!') }}</p>
                    <p>{{ __('Seja Bem Vindo')}}, {{ Auth::user()->name }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection