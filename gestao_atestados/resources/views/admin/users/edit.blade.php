@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header font-weight-bold">
                    <h2 class="float-left">Editar Usuário</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Nova Senha (opcional)</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="type">Tipo</label>
                            <select class="form-control" id="type" name="type">
                                <option value="0" @if($user->type === 0) selected @endif>Analista</option>
                                <option value="1" @if($user->type === 1) selected @endif>Admin</option>
                                <option value="2" @if($user->type === 2) selected @endif>Gerente</option>
                            </select>
                        </div>                        
                        <div class="form-group">
                            <label for="setor">Nome do Projeto</label>
                            <input type="text" class="form-control" id="setor" name="setor" value="{{ $user->setor }}">
                        </div>
                        <div class="form-group">
                            <label for="gestor_imediato">Gestor Imediato</label>
                            <input type="text" class="form-control" id="gestor_imediato" name="gestor_imediato" value="{{ $user->gestor_imediato }}">
                        </div>                      
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Voltar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection