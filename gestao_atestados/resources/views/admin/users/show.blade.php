@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header font-weight-bold">
                    <h2 class="float-left">Detalhes do Usuario</h2>
                </div>
        
                <div class="card-body">
                    <p>Nome:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Tipo:</strong> {{ $user->type}}</p>
                    <p><strong>Nome do Projeto:</strong> {{ $user->setor }}</p>
                    <p><strong>Gestor Imediato:</strong> {{ $user->gestor_imediato }}</p>
                    <p><strong>Criado em:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Atualizado em:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Voltar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection