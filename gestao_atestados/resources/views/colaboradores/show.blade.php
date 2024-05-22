@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header font-weight-bold">
                    <h2 class="float-left">Detalhes do Colaborador</h2>
                </div>
        
                <div class="card-body">
                    <p><strong>ID:</strong> {{ $colaborador->id }}</p>
                    <p><strong>Nome:</strong> {{ $colaborador->nome }}</p>
                    <p><strong>Setor:</strong> {{ $colaborador->setor }}</p>
                    <!-- Adicione outros campos conforme necessário -->
        
                    <a href="{{ route('colaboradores.index') }}" class="btn btn-outline-primary">Voltar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
