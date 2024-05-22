@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header font-weight-bold">
                    <h2 class="float-left">Editar Colaborador</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('colaboradores.update', $colaborador->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nome"><strong>Nome do Colaborador</strong></label>
                            <input type="text" name="nome" id="nome" class="form-control" value="{{ $colaborador->nome }}" required>
                        </div>
                        <div class="form-group">
                            <label for="setor"><strong>Setor do Colaborador</strong></label>
                            <input type="text" name="setor" id="setor" class="form-control" value="{{ $colaborador->setor }}" required>
                        </div>
                        <!-- Adicione outros campos conforme necessário -->
                
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        <a href="{{ route('colaboradores.index') }}" class="btn btn-outline-primary">Voltar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
